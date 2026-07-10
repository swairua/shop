<?php
class PayPalService {
    private $db;
    private $clientId;
    private $secret;
    private $mode;
    private $baseUrl;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->clientId = App::getSetting('paypal_client_id', '');
        $this->secret = App::getSetting('paypal_secret', '');
        $this->mode = App::getSetting('paypal_environment', 'sandbox');
        $this->baseUrl = $this->mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    private function getAccessToken() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ':' . $this->secret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            log_error('PayPal auth failed', ['http_code' => $httpCode, 'response' => $response]);
            return null;
        }

        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    public function createOrder($amount, $orderNumber) {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return ['success' => false, 'message' => 'Failed to authenticate with PayPal'];
        }

        $currency = App::getSetting('paypal_currency', 'KES');
        $payload = json_encode([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $orderNumber,
                    'description' => 'Order ' . $orderNumber,
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format((float)$amount, 2, '.', '')
                    ]
                ]
            ],
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                        'landing_page' => 'LOGIN',
                        'user_action' => 'PAY_NOW',
                        'return_url' => App::getSetting('paypal_return_url', base_url('orders/confirmation/')),
                        'cancel_url' => base_url('cart/checkout')
                    ]
                ]
            ]
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode >= 400 || !empty($data['error'])) {
            log_error('PayPal create order failed', ['http_code' => $httpCode, 'response' => $data]);
            return ['success' => false, 'message' => $data['message'] ?? 'PayPal order creation failed'];
        }

        return [
            'success' => true,
            'paypal_order_id' => $data['id'],
            'status' => $data['status']
        ];
    }

    public function captureOrder($paypalOrderId) {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return ['success' => false, 'message' => 'Failed to authenticate with PayPal'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders/' . $paypalOrderId . '/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode >= 400 || !empty($data['error'])) {
            log_error('PayPal capture failed', ['paypal_order_id' => $paypalOrderId, 'http_code' => $httpCode, 'response' => $data]);
            return ['success' => false, 'message' => $data['message'] ?? 'Payment capture failed'];
        }

        $capture = $data['purchase_units'][0]['payments']['captures'][0] ?? [];
        return [
            'success' => $capture['status'] === 'COMPLETED',
            'capture_id' => $capture['id'] ?? '',
            'status' => $capture['status'] ?? '',
            'amount' => $capture['amount']['value'] ?? 0,
            'paypal_response' => $data
        ];
    }
}