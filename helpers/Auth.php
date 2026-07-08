<?php
class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function adminLogin($email, $password, $remember = false) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active' LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_image'] = $user['image'];

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
                $stmt = $this->db->prepare("UPDATE users SET remember_token = ?, remember_token_expiry = ? WHERE id = ?");
                $stmt->bind_param("ssi", $token, $expiry, $user['id']);
                $stmt->execute();
                setcookie('remember_token', $token, time() + 2592000, '/', '', false, true);
            }

            $this->logActivity($user['id'], 'login', 'Admin logged in');
            return true;
        }
        return false;
    }

    public function customerLogin($email, $password, $remember = false) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ? AND status = 'active' LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if ($customer && password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_name'] = $customer['name'];
            $_SESSION['customer_email'] = $customer['email'];
            $_SESSION['customer_phone'] = $customer['phone'];

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
                $stmt = $this->db->prepare("UPDATE customers SET remember_token = ?, remember_token_expiry = ? WHERE id = ?");
                $stmt->bind_param("ssi", $token, $expiry, $customer['id']);
                $stmt->execute();
                setcookie('customer_remember', $token, time() + 2592000, '/', '', false, true);
            }

            return true;
        }
        return false;
    }

    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare("INSERT INTO customers (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['name'], $data['email'], $data['phone'], $data['password'], $data['created_at']);
        if ($stmt->execute()) {
            $id = $this->db->insertId();
            $_SESSION['customer_id'] = $id;
            $_SESSION['customer_name'] = $data['name'];
            $_SESSION['customer_email'] = $data['email'];
            return $id;
        }
        return false;
    }

    public function forgotPassword($email, $type = 'customer') {
        $table = $type === 'admin' ? 'users' : 'customers';
        $stmt = $this->db->prepare("SELECT id FROM $table WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) return false;

        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->db->prepare("UPDATE $table SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        return $token;
    }

    public function resetPassword($token, $password, $type = 'customer') {
        $table = $type === 'admin' ? 'users' : 'customers';
        $stmt = $this->db->prepare("SELECT id FROM $table WHERE reset_token = ? AND reset_token_expiry > NOW() LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) return false;

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE $table SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed, $token);
        return $stmt->execute();
    }

    public function adminLogout() {
        if (isset($_SESSION['admin_id'])) {
            $this->logActivity($_SESSION['admin_id'], 'logout', 'Admin logged out');
        }
        unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email'], $_SESSION['admin_role'], $_SESSION['admin_image']);
        setcookie('remember_token', '', time() - 3600, '/');
        session_destroy();
    }

    public function customerLogout() {
        unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['customer_phone']);
        setcookie('customer_remember', '', time() - 3600, '/');
    }

    public function checkRememberMe($type = 'admin') {
        if ($type === 'admin' && empty($_SESSION['admin_id'])) {
            $token = $_COOKIE['remember_token'] ?? null;
            if ($token) {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = ? AND remember_token_expiry > NOW() AND status = 'active' LIMIT 1");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                if ($user) {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['name'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_role'] = $user['role'];
                }
            }
        }
        if ($type === 'customer' && empty($_SESSION['customer_id'])) {
            $token = $_COOKIE['customer_remember'] ?? null;
            if ($token) {
                $stmt = $this->db->prepare("SELECT * FROM customers WHERE remember_token = ? AND remember_token_expiry > NOW() AND status = 'active' LIMIT 1");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $customer = $stmt->get_result()->fetch_assoc();
                if ($customer) {
                    $_SESSION['customer_id'] = $customer['id'];
                    $_SESSION['customer_name'] = $customer['name'];
                    $_SESSION['customer_email'] = $customer['email'];
                }
            }
        }
    }

    private function logActivity($userId, $action, $details = '') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issss", $userId, $action, $details, $ip, $userAgent);
        $stmt->execute();
    }

    public function hasPermission($role, $requiredRole) {
        $roles = ['customer' => 0, 'staff' => 1, 'admin' => 2, 'super_admin' => 3];
        return ($roles[$role] ?? 0) >= ($roles[$requiredRole] ?? 0);
    }
}
