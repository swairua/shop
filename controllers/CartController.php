<?php
class CartController extends Controller {
    public function index() {
        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;
        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);
        $data = ['cart' => $cartData];
        $this->render('front/cart/index', $data);
    }

    public function checkout() {
        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;

        if (!$customerId && $this->post('email')) {
            $_SESSION['guest_email'] = sanitize_input($this->post('email'));
        }

        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);

        if (empty($cartData['items'])) {
            $this->setFlash('error', 'Your cart is empty');
            $this->redirect('cart');
        }

        if ($this->isPost() && $this->post('action') === 'checkout') {
            $this->processCheckout($cartData);
        }

        $data = [
            'cart' => $cartData,
            'customer' => $customerId ? (new Customer())->find($customerId) : null,
            'addresses' => $customerId ? (new Model())->query("SELECT * FROM customer_addresses WHERE customer_id = ?", [$customerId]) : [],
            'paymentMethods' => (new Model())->query("SELECT * FROM payment_methods WHERE status = 'active' ORDER BY sort_order"),
            'shippingMethods' => (new Model())->query("SELECT * FROM shipping_methods WHERE status = 'active' ORDER BY sort_order")
        ];
        $this->render('front/checkout/index', $data);
    }

    private function processCheckout($cartData) {
        $customerId = $_SESSION['customer_id'] ?? null;

        if (!$customerId) {
            $guestName = sanitize_input($this->post('name'));
            $guestEmail = sanitize_input($this->post('email'));
            $guestPhone = sanitize_input($this->post('phone'));

            $existing = (new Customer())->findBy('email', $guestEmail, 1);
            if ($existing) {
                $_SESSION['customer_id'] = $existing['id'];
                $_SESSION['customer_name'] = $existing['name'];
                $_SESSION['customer_email'] = $existing['email'];
                $customerId = $existing['id'];
            } else {
                $auth = new Auth();
                $customerId = $auth->register([
                    'name' => $guestName,
                    'email' => $guestEmail,
                    'phone' => $guestPhone,
                    'password' => bin2hex(random_bytes(8))
                ]);
            }
        }

        $addressId = intval($this->post('address_id'));
        if (!$addressId) {
            $addrData = [
                'customer_id' => $customerId,
                'type' => 'both',
                'address_line1' => sanitize_input($this->post('address_line1')),
                'address_line2' => sanitize_input($this->post('address_line2')),
                'city' => sanitize_input($this->post('city')),
                'state' => sanitize_input($this->post('state')),
                'postal_code' => sanitize_input($this->post('postal_code')),
                'country' => sanitize_input($this->post('country', 'Kenya')),
                'phone' => sanitize_input($this->post('phone')),
                'is_default' => 1
            ];
            $addrModel = new Model('customer_addresses');
            $addressId = $addrModel->create($addrData);
        }

        $shippingMethod = sanitize_input($this->post('shipping_method', 'standard'));
        $paymentMethod = sanitize_input($this->post('payment_method', 'cod'));

        $order = new Order();
        $orderData = [
            'shipping_address_id' => $addressId,
            'billing_address_id' => $addressId,
            'coupon_id' => $cartData['coupon_id'],
            'subtotal' => $cartData['subtotal'] ?? 0,
            'discount' => $cartData['discount'] ?? 0,
            'tax' => $cartData['tax'] ?? 0,
            'shipping_cost' => $cartData['shipping_cost'] ?? 0,
            'total' => $cartData['total'] ?? 0,
            'shipping_method' => $shippingMethod,
            'payment_method' => $paymentMethod,
            'notes' => sanitize_input($this->post('notes'))
        ];

        $orderId = $order->createFromCart($cartData['id'], $customerId, $orderData);

        if ($orderId) {
            if ($cartData['coupon_id']) {
                (new Coupon())->incrementUsage($cartData['coupon_id']);
            }

            if ($paymentMethod === 'mpesa') {
                $mpesa = new MpesaService();
                $orderNumber = $order->find($orderId)['order_number'];
                $amount = $orderData['total'];
                $phone = sanitize_input($this->post('phone'));

                $result = $mpesa->stkPush($phone, $amount, $orderNumber, 'Payment for order ' . $orderNumber);

                if ($result['success']) {
                    $this->db->query("UPDATE mpesa_transactions SET order_id = {$orderId}, customer_id = {$customerId} WHERE checkout_request_id = '{$this->db->escape($result['checkout_request_id'])}'");
                    $_SESSION['mpesa_checkout_id'] = $result['checkout_request_id'];
                    $_SESSION['mpesa_order_id'] = $orderId;
                    $this->redirect('checkout/payment?order_id=' . $orderId);
                } else {
                    $this->setFlash('error', 'M-Pesa payment failed: ' . $result['message']);
                    $this->redirect('cart/checkout');
                }
            } else {
                if ($paymentMethod === 'cod') {
                    $order->addStatusHistory($orderId, 'pending', 'Order placed with Cash on Delivery');
                } elseif ($paymentMethod === 'whatsapp') {
                    $order->addStatusHistory($orderId, 'pending', 'Order placed via WhatsApp');
                }
                $this->setFlash('success', 'Order placed successfully!');
                $this->redirect('orders/confirmation/' . $orderId);
            }
        } else {
            $this->setFlash('error', 'Failed to create order. Please try again.');
            $this->redirect('cart/checkout');
        }
    }

    public function payment() {
        $orderId = $_SESSION['mpesa_order_id'] ?? intval($this->get('order_id'));
        $checkoutId = $_SESSION['mpesa_checkout_id'] ?? '';

        if ($this->isPost() && $this->post('action') === 'check_status') {
            $mpesa = new MpesaService();
            $result = $mpesa->stkQuery($checkoutId);
            $this->json($result);
        }

        $order = new Order();
        $data = [
            'order' => $order->find($orderId),
            'checkout_id' => $checkoutId
        ];
        $this->render('front/checkout/payment', $data);
    }

    public function confirmation($orderId) {
        $order = new Order();
        $data = ['order' => $order->getWithItems($orderId)];
        if (!$data['order']) $this->redirect('');
        $this->render('front/orders/confirmation', $data);
    }
}
