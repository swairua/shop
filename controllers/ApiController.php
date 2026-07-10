<?php
class ApiController extends Controller {
    public function mpesaCallback() {
        $mpesa = new MpesaService();
        $callbackData = file_get_contents('php://input');
        $result = $mpesa->processStkCallback($callbackData);
        $this->json($result);
    }

    public function mpesaValidate() {
        $callbackData = file_get_contents('php://input');
        $stmt = $this->db->prepare("INSERT INTO callback_logs (source, type, method, payload, ip_address) VALUES ('mpesa', 'validation', 'POST', ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt->bind_param("ss", $callbackData, $ip);
        $stmt->execute();
        $this->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function mpesaConfirm() {
        $callbackData = file_get_contents('php://input');
        $stmt = $this->db->prepare("INSERT INTO callback_logs (source, type, method, payload, ip_address) VALUES ('mpesa', 'confirmation', 'POST', ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt->bind_param("ss", $callbackData, $ip);
        $stmt->execute();
        $this->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function mpesaTimeout() {
        $callbackData = file_get_contents('php://input');
        $stmt = $this->db->prepare("INSERT INTO callback_logs (source, type, method, payload, ip_address) VALUES ('mpesa', 'timeout', 'POST', ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt->bind_param("ss", $callbackData, $ip);
        $stmt->execute();
        $this->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function mpesaResult() {
        $callbackData = file_get_contents('php://input');
        $stmt = $this->db->prepare("INSERT INTO callback_logs (source, type, method, payload, ip_address) VALUES ('mpesa', 'result', 'POST', ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt->bind_param("ss", $callbackData, $ip);
        $stmt->execute();
        $this->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function cartAdd() {
        $productId = intval($this->post('product_id'));
        $quantity = intval($this->post('quantity', 1));
        $variantId = $this->post('variant_id') ? intval($this->post('variant_id')) : null;

        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;

        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);

        if ($customerId && !$cartData['customer_id']) {
            $cart->update($cartData['id'], ['customer_id' => $customerId]);
            $cartData['customer_id'] = $customerId;
        }

        $cart->addItem($cartData['id'], $productId, $quantity, $variantId);
        $cartData = $cart->find($cartData['id']);
        $items = $this->db->query("SELECT COUNT(*) as count, COALESCE(SUM(quantity), 0) as qty, COALESCE(SUM(total_price), 0) as total FROM cart_items WHERE cart_id = {$cartData['id']}")->fetch_assoc();

        $this->json(['success' => true, 'message' => 'Item added to cart', 'cart' => $items]);
    }

    public function cartUpdate() {
        $itemId = intval($this->post('item_id'));
        $quantity = intval($this->post('quantity'));

        $cart = new Cart();
        $cart->updateItemQuantity($itemId, $quantity);

        $this->json(['success' => true, 'message' => 'Cart updated']);
    }

    public function cartRemove() {
        $itemId = intval($this->post('item_id'));
        $cart = new Cart();
        $cart->removeItem($itemId);
        $this->json(['success' => true, 'message' => 'Item removed']);
    }

    public function cartApplyCoupon() {
        $code = sanitize_input($this->post('code'));
        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;

        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);

        $result = $cart->applyCoupon($cartData['id'], $code);
        $this->json($result);
    }

    public function cartRemoveCoupon() {
        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;
        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);
        $cart->removeCoupon($cartData['id']);
        $this->json(['success' => true, 'message' => 'Coupon removed']);
    }

    public function cartGetCount() {
        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;
        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);
        $items = $this->db->query("SELECT COUNT(*) as count, COALESCE(SUM(quantity), 0) as qty, COALESCE(SUM(total_price), 0) as total FROM cart_items WHERE cart_id = {$cartData['id']}")->fetch_assoc();
        $this->json($items);
    }

    public function wishlistToggle() {
        customer_guard();
        $productId = intval($this->post('product_id'));
        $existing = $this->db->query("SELECT id FROM wishlist WHERE customer_id = {$_SESSION['customer_id']} AND product_id = {$productId}")->fetch_assoc();
        if ($existing) {
            $this->db->query("DELETE FROM wishlist WHERE id = {$existing['id']}");
            $this->json(['success' => true, 'action' => 'removed']);
        } else {
            $this->db->query("INSERT INTO wishlist (customer_id, product_id) VALUES ({$_SESSION['customer_id']}, {$productId})");
            $this->json(['success' => true, 'action' => 'added']);
        }
    }

    public function compareToggle() {
        $productId = intval($this->post('product_id'));
        $customerId = $_SESSION['customer_id'] ?? 0;

        if ($customerId) {
            $existing = $this->db->query("SELECT id FROM compare_list WHERE customer_id = {$customerId} AND product_id = {$productId}")->fetch_assoc();
            if ($existing) {
                $this->db->query("DELETE FROM compare_list WHERE id = {$existing['id']}");
                $this->json(['success' => true, 'action' => 'removed']);
            } else {
                $this->db->query("INSERT INTO compare_list (customer_id, product_id) VALUES ({$customerId}, {$productId})");
                $this->json(['success' => true, 'action' => 'added']);
            }
        } else {
            $compare = $_SESSION['compare'] ?? [];
            $key = array_search($productId, $compare);
            if ($key !== false) {
                unset($compare[$key]);
                $_SESSION['compare'] = array_values($compare);
                $this->json(['success' => true, 'action' => 'removed']);
            } else {
                $compare[] = $productId;
                $_SESSION['compare'] = $compare;
                $this->json(['success' => true, 'action' => 'added']);
            }
        }
    }

    public function search() {
        $query = sanitize_input($this->get('q', ''));
        $product = new Product();
        $results = $product->search($query, [], 1, 10);
        $this->json($results);
    }

    public function login() {
        if ($this->isPost()) {
            $auth = new Auth();
            $email = sanitize_input($this->post('email'));
            $password = $this->post('password');
            $remember = $this->post('remember') ? true : false;

            if ($auth->customerLogin($email, $password, $remember)) {
                $sessionId = session_id();
                $cart = new Cart();
                $cart->mergeGuestCart($sessionId, $_SESSION['customer_id']);
                $this->json(['success' => true, 'message' => 'Login successful']);
            } else {
                $this->json(['success' => false, 'message' => 'Invalid email or password']);
            }
        }
    }

    public function register() {
        if ($this->isPost()) {
            $data = [
                'name' => sanitize_input($this->post('name')),
                'email' => sanitize_input($this->post('email')),
                'phone' => sanitize_input($this->post('phone')),
                'password' => $this->post('password'),
            ];

            $errors = $this->validate($data, [
                'name' => 'required|min:2',
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            if (!empty($errors)) {
                $this->json(['success' => false, 'errors' => $errors]);
            }

            $existing = $this->db->query("SELECT id FROM customers WHERE email = '{$this->db->escape($data['email'])}'")->fetch_assoc();
            if ($existing) {
                $this->json(['success' => false, 'message' => 'Email already registered']);
            }

            $auth = new Auth();
            $auth->register($data);
            $sessionId = session_id();
            $cart = new Cart();
            $cart->mergeGuestCart($sessionId, $_SESSION['customer_id']);

            $this->json(['success' => true, 'message' => 'Registration successful']);
        }
    }

    public function submitReview() {
        $productId = intval($this->post('product_id'));
        $rating = intval($this->post('rating'));
        $title = sanitize_input($this->post('title'));
        $body = sanitize_input($this->post('review'));

        $customerId = $_SESSION['customer_id'] ?? null;
        $name = $customerId ? $_SESSION['customer_name'] : sanitize_input($this->post('name'));
        $email = $customerId ? $_SESSION['customer_email'] : sanitize_input($this->post('email'));

        $stmt = $this->db->prepare("INSERT INTO reviews (product_id, customer_id, name, email, rating, title, review, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iississ", $productId, $customerId, $name, $email, $rating, $title, $body);
        $stmt->execute();

        $this->json(['success' => true, 'message' => 'Review submitted for approval']);
    }

    public function paypalCreateOrder() {
        $sessionId = session_id();
        $customerId = $_SESSION['customer_id'] ?? null;
        $cart = new Cart();
        $cartData = $cart->getOrCreateCart($customerId, $sessionId);

        if (empty($cartData['items'])) {
            $this->json(['error' => 'Cart is empty'], 400);
        }

        $order = new Order();
        $customerId = $customerId ?: $this->createGuestCustomer();
        $addressId = intval($this->post('address_id')) ?: $this->createCheckoutAddress($customerId);

        $orderData = [
            'shipping_address_id' => $addressId,
            'billing_address_id' => $addressId,
            'coupon_id' => $cartData['coupon_id'],
            'subtotal' => $cartData['subtotal'] ?? 0,
            'discount' => $cartData['discount'] ?? 0,
            'tax' => $cartData['tax'] ?? 0,
            'shipping_cost' => $cartData['shipping_cost'] ?? 0,
            'total' => $cartData['total'] ?? 0,
            'shipping_method' => sanitize_input($this->post('shipping_method', 'standard')),
            'payment_method' => 'paypal',
            'notes' => sanitize_input($this->post('notes'))
        ];

        $orderId = $order->createFromCart($cartData['id'], $customerId, $orderData);
        if (!$orderId) {
            $this->json(['error' => 'Failed to create order'], 500);
        }

        if ($cartData['coupon_id']) {
            (new Coupon())->incrementUsage($cartData['coupon_id']);
        }

        $orderNumber = $order->find($orderId)['order_number'];
        $amount = $orderData['total'];

        $paypal = new PayPalService();
        $result = $paypal->createOrder($amount, $orderNumber);

        if (!$result['success']) {
            $order->addStatusHistory($orderId, 'failed', 'PayPal order creation failed: ' . $result['message']);
            $this->json(['error' => $result['message']], 500);
        }

        $stmt = $this->db->prepare("UPDATE orders SET paypal_order_id = ?, payment_status = 'pending' WHERE id = ?");
        $stmt->bind_param("si", $result['paypal_order_id'], $orderId);
        $stmt->execute();

        $_SESSION['paypal_order_id'] = $orderId;
        $this->json(['id' => $result['paypal_order_id'], 'order_id' => $orderId]);
    }

    public function paypalCaptureOrder() {
        $paypalOrderId = sanitize_input($this->post('paypal_order_id'));
        if (!$paypalOrderId) {
            $this->json(['success' => false, 'message' => 'Missing PayPal order ID'], 400);
        }

        $paypal = new PayPalService();
        $result = $paypal->captureOrder($paypalOrderId);

        if (!$result['success']) {
            $this->json(['success' => false, 'message' => $result['message'] ?? 'Payment capture failed']);
        }

        $orderRow = $this->db->query("SELECT id, total FROM orders WHERE paypal_order_id = '" . $this->db->escape($paypalOrderId) . "'")->fetch_assoc();
        if ($orderRow) {
            (new Order())->updatePayment($orderRow['id'], $result['amount']);
            $this->json(['success' => true, 'order_id' => $orderRow['id']]);
        } else {
            $this->json(['success' => false, 'message' => 'Order not found']);
        }
    }

    public function mpesaPaymentStatus() {
        $orderId = intval($this->get('order_id'));
        $txn = $this->db->query("SELECT * FROM mpesa_transactions WHERE order_id = {$orderId} ORDER BY id DESC LIMIT 1")->fetch_assoc();
        $this->json([
            'status' => $txn['status'] ?? 'unknown',
            'receipt' => $txn['mpesa_receipt_number'] ?? '',
            'amount' => $txn['amount'] ?? 0
        ]);
    }

    private function createGuestCustomer() {
        $name = sanitize_input($this->post('name', 'Guest'));
        $email = sanitize_input($this->post('email', 'guest_' . time() . '@example.com'));
        $phone = sanitize_input($this->post('phone', ''));
        $existing = (new Customer())->findBy('email', $email, 1);
        if ($existing) {
            $_SESSION['customer_id'] = $existing['id'];
            $_SESSION['customer_name'] = $existing['name'];
            $_SESSION['customer_email'] = $existing['email'];
            return $existing['id'];
        }
        $auth = new Auth();
        return $auth->register(['name' => $name, 'email' => $email, 'phone' => $phone, 'password' => bin2hex(random_bytes(8))]);
    }

    private function createCheckoutAddress($customerId) {
        $addrModel = new Model('customer_addresses');
        return $addrModel->create([
            'customer_id' => $customerId,
            'type' => 'both',
            'address_line1' => sanitize_input($this->post('address_line1', 'N/A')),
            'city' => sanitize_input($this->post('city', 'Nairobi')),
            'country' => sanitize_input($this->post('country', 'Kenya')),
            'phone' => sanitize_input($this->post('phone', '')),
            'is_default' => 1
        ]);
    }
}
