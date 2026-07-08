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
}
