<?php
class AuthController extends Controller {
    public function login() {
        if (is_customer()) $this->redirect('account');
        if ($this->isPost()) {
            $auth = new Auth();
            $email = sanitize_input($this->post('email'));
            $password = $this->post('password');
            $remember = $this->post('remember') ? true : false;

            if ($auth->customerLogin($email, $password, $remember)) {
                $sessionId = session_id();
                $cart = new Cart();
                $cart->mergeGuestCart($sessionId, $_SESSION['customer_id']);
                $redirect = $_SESSION['redirect_after_login'] ?? 'account';
                unset($_SESSION['redirect_after_login']);
                $this->redirect($redirect);
            }
            $this->setFlash('error', 'Invalid email or password');
        }
        $this->render('front/auth/login');
    }

    public function register() {
        if (is_customer()) $this->redirect('account');
        if ($this->isPost()) {
            $data = [
                'name' => sanitize_input($this->post('name')),
                'email' => sanitize_input($this->post('email')),
                'phone' => sanitize_input($this->post('phone')),
                'password' => $this->post('password'),
            ];

            $auth = new Auth();
            $result = $auth->register($data);
            if ($result) {
                $sessionId = session_id();
                $cart = new Cart();
                $cart->mergeGuestCart($sessionId, $_SESSION['customer_id']);
                $this->setFlash('success', 'Registration successful! Welcome.');
                $this->redirect('account');
            }
            $this->setFlash('error', 'Registration failed. Email may already exist.');
        }
        $this->render('front/auth/register');
    }

    public function forgotPassword() {
        if ($this->isPost()) {
            $email = sanitize_input($this->post('email'));
            $auth = new Auth();
            $token = $auth->forgotPassword($email, 'customer');
            if ($token) {
                $this->setFlash('success', 'Password reset link has been sent to your email.');
            } else {
                $this->setFlash('error', 'Email not found.');
            }
        }
        $this->render('front/auth/forgot');
    }

    public function resetPassword($token) {
        if ($this->isPost()) {
            $auth = new Auth();
            $password = $this->post('password');
            if ($auth->resetPassword($token, $password, 'customer')) {
                $this->setFlash('success', 'Password reset successful. Please login.');
                $this->redirect('login');
            }
            $this->setFlash('error', 'Invalid or expired token.');
        }
        $data = ['token' => $token];
        $this->render('front/auth/reset', $data);
    }
}
