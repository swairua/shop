<?php
class MpesaService {
    private $consumerKey;
    private $consumerSecret;
    private $passkey;
    private $shortcode;
    private $tillNumber;
    private $initiatorName;
    private $initiatorPassword;
    private $environment;
    private $callbackUrl;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->loadConfig();
    }

    private function loadConfig() {
        $settings = App::getSettings('mpesa');
        $this->consumerKey = $settings['mpesa_consumer_key'] ?? '';
        $this->consumerSecret = $settings['mpesa_consumer_secret'] ?? '';
        $this->passkey = $settings['mpesa_passkey'] ?? '';
        $this->shortcode = $settings['mpesa_shortcode'] ?? '174379';
        $this->tillNumber = $settings['mpesa_till_number'] ?? '';
        $this->initiatorName = $settings['mpesa_initiator_name'] ?? '';
        $this->initiatorPassword = $settings['mpesa_initiator_password'] ?? '';
        $this->environment = $settings['mpesa_environment'] ?? 'sandbox';
        $this->callbackUrl = $settings['mpesa_callback_url'] ?? 'http://localhost/shop/api/mpesa/callback';
    }

    private function getBaseUrl() {
        return $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    public function getAccessToken() {
        $url = $this->getBaseUrl() . '/oauth/v1/generate?grant_type=client_credentials';
        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('get_access_token', [], $response, $httpCode);

        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    public function stkPush($phone, $amount, $accountReference, $transactionDesc = 'Payment') {
        $token = $this->getAccessToken();
        if (!$token) {
            $this->logError('STK Push failed: Could not get access token');
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $phone = $this->formatPhone($phone);

        $url = $this->getBaseUrl() . '/mpesa/stkpush/v1/processrequest';
        $data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => intval($amount),
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('stk_push', $data, $response, $httpCode);
        $result = json_decode($response, true);

        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
            $this->saveTransaction([
                'order_id' => null,
                'customer_id' => null,
                'transaction_type' => 'stk_push',
                'merchant_request_id' => $result['MerchantRequestID'] ?? '',
                'checkout_request_id' => $result['CheckoutRequestID'] ?? '',
                'phone_number' => $phone,
                'amount' => $amount,
                'status' => 'pending'
            ]);
            return [
                'success' => true,
                'merchant_request_id' => $result['MerchantRequestID'] ?? '',
                'checkout_request_id' => $result['CheckoutRequestID'] ?? '',
                'message' => 'STK Push sent successfully'
            ];
        }

        $this->logError('STK Push failed: ' . ($result['errorMessage'] ?? 'Unknown error'));
        return [
            'success' => false,
            'message' => $result['errorMessage'] ?? 'STK Push failed',
            'result' => $result
        ];
    }

    public function stkQuery($checkoutRequestId) {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Authentication failed'];

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $url = $this->getBaseUrl() . '/mpesa/stkpushquery/v1/query';
        $data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('stk_query', $data, $response, $httpCode);
        return json_decode($response, true);
    }

    public function processStkCallback($callbackData) {
        $this->logCallback('stk_push', $callbackData);

        $body = json_decode($callbackData, true);
        $stmt = $this->db->prepare("INSERT INTO mpesa_callbacks (transaction_type, merchant_request_id, checkout_request_id, result_code, result_desc, payload, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $merchantRequestId = $body['Body']['stkCallback']['MerchantRequestID'] ?? '';
        $checkoutRequestId = $body['Body']['stkCallback']['CheckoutRequestID'] ?? '';
        $resultCode = $body['Body']['stkCallback']['ResultCode'] ?? null;
        $resultDesc = $body['Body']['stkCallback']['ResultDesc'] ?? '';
        $stmt->bind_param("ssssiss", $type = 'stk_push', $merchantRequestId, $checkoutRequestId, $resultCode, $resultDesc, $callbackData, $ip);
        $stmt->execute();

        if ($resultCode == 0) {
            $metadata = $body['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];
            $mpesaReceipt = '';
            $transactionDate = '';
            $phone = '';
            $amount = 0;

            foreach ($metadata as $item) {
                switch ($item['Name']) {
                    case 'MpesaReceiptNumber': $mpesaReceipt = $item['Value'] ?? ''; break;
                    case 'TransactionDate': $transactionDate = $item['Value'] ?? ''; break;
                    case 'PhoneNumber': $phone = $item['Value'] ?? ''; break;
                    case 'Amount': $amount = $item['Value'] ?? 0; break;
                }
            }

            if ($transactionDate) {
                $transactionDate = date('Y-m-d H:i:s', strtotime($transactionDate));
            }

            $this->completeTransaction($checkoutRequestId, $mpesaReceipt, $transactionDate, $phone, $amount);
        } else {
            $this->failTransaction($checkoutRequestId, $resultCode, $resultDesc);
        }

        return ['ResultCode' => 0, 'ResultDesc' => 'Success'];
    }

    private function completeTransaction($checkoutRequestId, $receipt, $transactionDate, $phone, $amount) {
        $stmt = $this->db->prepare("UPDATE mpesa_transactions SET mpesa_receipt_number = ?, transaction_date = ?, phone_number = ?, amount = ?, result_code = 0, status = 'completed', raw_callback = ? WHERE checkout_request_id = ?");
        $callbackJson = json_encode(['receipt' => $receipt, 'date' => $transactionDate, 'phone' => $phone, 'amount' => $amount]);
        $stmt->bind_param("ssssss", $receipt, $transactionDate, $phone, $amount, $callbackJson, $checkoutRequestId);
        $stmt->execute();

        $stmt = $this->db->prepare("UPDATE mpesa_callbacks SET processed = 1 WHERE checkout_request_id = ?");
        $stmt->bind_param("s", $checkoutRequestId);
        $stmt->execute();
    }

    private function failTransaction($checkoutRequestId, $resultCode, $resultDesc) {
        $stmt = $this->db->prepare("UPDATE mpesa_transactions SET result_code = ?, result_desc = ?, status = 'failed' WHERE checkout_request_id = ?");
        $stmt->bind_param("iss", $resultCode, $resultDesc, $checkoutRequestId);
        $stmt->execute();
    }

    public function processB2CPayment($phone, $amount, $remarks = 'Payment') {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Authentication failed'];

        $phone = $this->formatPhone($phone);

        $url = $this->getBaseUrl() . '/mpesa/b2c/v1/paymentrequest';
        $data = [
            'InitiatorName' => $this->initiatorName,
            'SecurityCredential' => $this->getSecurityCredential(),
            'CommandID' => 'BusinessPayment',
            'Amount' => intval($amount),
            'PartyA' => $this->shortcode,
            'PartyB' => $phone,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => App::getSetting('mpesa_queue_timeout_url', $this->callbackUrl . '/timeout'),
            'ResultURL' => App::getSetting('mpesa_result_url', $this->callbackUrl . '/result'),
            'Occasion' => $remarks
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('b2c_payment', $data, $response, $httpCode);
        $result = json_decode($response, true);

        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
            $this->saveTransaction([
                'transaction_type' => 'b2c',
                'merchant_request_id' => $result['ConversationID'] ?? '',
                'phone_number' => $phone,
                'amount' => $amount,
                'status' => 'pending'
            ]);
            return ['success' => true, 'message' => 'B2C payment initiated', 'conversation_id' => $result['ConversationID'] ?? ''];
        }

        return ['success' => false, 'message' => $result['errorMessage'] ?? 'B2C payment failed'];
    }

    public function accountBalance() {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Authentication failed'];

        $url = $this->getBaseUrl() . '/mpesa/accountbalance/v1/query';
        $data = [
            'Initiator' => $this->initiatorName,
            'SecurityCredential' => $this->getSecurityCredential(),
            'CommandID' => 'AccountBalance',
            'PartyA' => $this->shortcode,
            'IdentifierType' => '4',
            'Remarks' => 'Balance Query',
            'QueueTimeOutURL' => App::getSetting('mpesa_queue_timeout_url', $this->callbackUrl . '/timeout'),
            'ResultURL' => App::getSetting('mpesa_result_url', $this->callbackUrl . '/result')
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('account_balance', $data, $response, $httpCode);
        return json_decode($response, true);
    }

    public function transactionStatus($transactionId) {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Authentication failed'];

        $url = $this->getBaseUrl() . '/mpesa/transactionstatus/v1/query';
        $data = [
            'Initiator' => $this->initiatorName,
            'SecurityCredential' => $this->getSecurityCredential(),
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $transactionId,
            'PartyA' => $this->shortcode,
            'IdentifierType' => '4',
            'ResultURL' => App::getSetting('mpesa_result_url', $this->callbackUrl . '/result'),
            'QueueTimeOutURL' => App::getSetting('mpesa_queue_timeout_url', $this->callbackUrl . '/timeout'),
            'Remarks' => 'Status Query',
            'Occasion' => 'Query'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('transaction_status', $data, $response, $httpCode);
        return json_decode($response, true);
    }

    public function reversal($transactionId, $amount) {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Authentication failed'];

        $url = $this->getBaseUrl() . '/mpesa/reversal/v1/request';
        $data = [
            'Initiator' => $this->initiatorName,
            'SecurityCredential' => $this->getSecurityCredential(),
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $transactionId,
            'Amount' => intval($amount),
            'ReceiverParty' => $this->shortcode,
            'RecieverIdentifierType' => '11',
            'ResultURL' => App::getSetting('mpesa_result_url', $this->callbackUrl . '/result'),
            'QueueTimeOutURL' => App::getSetting('mpesa_queue_timeout_url', $this->callbackUrl . '/timeout'),
            'Remarks' => 'Reversal',
            'Occasion' => 'Reversal'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('reversal', $data, $response, $httpCode);
        return json_decode($response, true);
    }

    public function registerC2BUrls() {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Authentication failed'];

        $validationUrl = App::getSetting('mpesa_validation_url', $this->callbackUrl . '/validate');
        $confirmationUrl = App::getSetting('mpesa_confirmation_url', $this->callbackUrl . '/confirm');

        $url = $this->getBaseUrl() . '/mpesa/c2b/v1/registerurl';
        $data = [
            'ShortCode' => $this->shortcode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApiCall('register_c2b_urls', $data, $response, $httpCode);
        return json_decode($response, true);
    }

    private function formatPhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) == 9) {
            $phone = '254' . $phone;
        } elseif (strlen($phone) == 10 && substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (strlen($phone) == 12 && substr($phone, 0, 3) == '254') {
            $phone = $phone;
        } elseif (strlen($phone) == 13 && substr($phone, 0, 4) == '+254') {
            $phone = substr($phone, 1);
        }
        return $phone;
    }

    private function getSecurityCredential() {
        $cert = App::getSetting('mpesa_security_certificate', '');
        if ($this->environment === 'production' && $cert) {
            $publicKey = openssl_get_publickey($cert);
            if ($publicKey) {
                $encrypted = '';
                openssl_public_encrypt($this->initiatorPassword, $encrypted, $publicKey);
                return base64_encode($encrypted);
            }
        }
        return $this->initiatorPassword;
    }

    private function saveTransaction($data) {
        $stmt = $this->db->prepare("INSERT INTO mpesa_transactions (order_id, customer_id, transaction_type, merchant_request_id, checkout_request_id, phone_number, amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssds",
            $data['order_id'] ?? null,
            $data['customer_id'] ?? null,
            $data['transaction_type'],
            $data['merchant_request_id'] ?? '',
            $data['checkout_request_id'] ?? '',
            $data['phone_number'],
            $data['amount'],
            $data['status']
        );
        $stmt->execute();
        return $this->db->insertId();
    }

    private function logApiCall($action, $requestData, $response, $httpCode) {
        $stmt = $this->db->prepare("INSERT INTO payment_logs (action, status, request_data, response_data, ip_address) VALUES (?, ?, ?, ?, ?)");
        $status = $httpCode >= 200 && $httpCode < 300 ? 'success' : 'failed';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt->bind_param("sssss", $action, $status, json_encode($requestData), $response, $ip);
        $stmt->execute();
    }

    private function logCallback($type, $payload) {
        $stmt = $this->db->prepare("INSERT INTO callback_logs (source, type, method, payload, ip_address) VALUES (?, ?, ?, ?, ?)");
        $method = $_SERVER['REQUEST_METHOD'] ?? 'POST';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt->bind_param("sssss", $source = 'mpesa', $type, $method, $payload, $ip);
        $stmt->execute();
    }

    private function logError($message) {
        log_error('M-Pesa Error: ' . $message);
        $stmt = $this->db->prepare("INSERT INTO payment_failures (error_message, payload) VALUES (?, ?)");
        $stmt->bind_param("ss", $message, $json = '{}');
        $stmt->execute();
    }

    public function generateReceipt($transactionId) {
        $stmt = $this->db->prepare("SELECT t.*, o.order_number, c.name as customer_name
                FROM mpesa_transactions t
                LEFT JOIN orders o ON t.order_id = o.id
                LEFT JOIN customers c ON t.customer_id = c.id
                WHERE t.id = ?");
        $stmt->bind_param("i", $transactionId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getDailyCollections($date = null) {
        $date = $date ?: date('Y-m-d');
        return $this->db->query(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM mpesa_transactions
             WHERE status = 'completed' AND DATE(created_at) = '{$date}'"
        )->fetch_assoc();
    }

    public function getMonthlyCollections($year = null, $month = null) {
        $year = $year ?: date('Y');
        $month = $month ?: date('m');
        return $this->db->query(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM mpesa_transactions
             WHERE status = 'completed' AND YEAR(created_at) = {$year} AND MONTH(created_at) = {$month}"
        )->fetch_assoc();
    }

    public function getFailedTransactions($limit = 50) {
        return $this->db->query(
            "SELECT * FROM mpesa_transactions WHERE status = 'failed' ORDER BY created_at DESC LIMIT {$limit}"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingStkRequests() {
        return $this->db->query(
            "SELECT * FROM mpesa_transactions WHERE transaction_type = 'stk_push' AND status = 'pending' AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) ORDER BY created_at DESC"
        )->fetch_all(MYSQLI_ASSOC);
    }
}
