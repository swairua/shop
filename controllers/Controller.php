<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function view($path, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $path . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("View not found: {$path}");
        }
    }

    protected function render($path, $data = [], $layout = 'front/layout') {
        $data['content'] = function() use ($path, $data) {
            extract($data);
            $viewFile = __DIR__ . '/../views/' . $path . '.php';
            if (file_exists($viewFile)) {
                require $viewFile;
            }
        };
        $this->view($layout, $data);
    }

    protected function renderAdmin($path, $data = [], $layout = 'admin/layout') {
        $data['content'] = function() use ($path, $data) {
            extract($data);
            $viewFile = __DIR__ . '/../views/' . $path . '.php';
            if (file_exists($viewFile)) {
                require $viewFile;
            }
        };
        $this->view($layout, $data);
    }

    protected function json($data, $statusCode = 200) {
        jsonResponse($data, $statusCode);
    }

    protected function redirect($url) {
        redirect($url);
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function post($key = null, $default = null) {
        if ($key === null) return $_POST;
        return $_POST[$key] ?? $default;
    }

    protected function get($key = null, $default = null) {
        if ($key === null) return $_GET;
        return $_GET[$key] ?? $default;
    }

    protected function files($key = null) {
        if ($key === null) return $_FILES;
        return $_FILES[$key] ?? null;
    }

    protected function validate($data, $rules) {
        $errors = [];
        foreach ($rules as $field => $ruleList) {
            $rulesArr = explode('|', $ruleList);
            foreach ($rulesArr as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $errors[$field] = ucfirst($field) . ' is required';
                }
                if (strpos($rule, 'min:') === 0) {
                    $min = explode(':', $rule)[1];
                    if (isset($data[$field]) && strlen($data[$field]) < $min) {
                        $errors[$field] = ucfirst($field) . ' must be at least ' . $min . ' characters';
                    }
                }
                if (strpos($rule, 'max:') === 0) {
                    $max = explode(':', $rule)[1];
                    if (isset($data[$field]) && strlen($data[$field]) > $max) {
                        $errors[$field] = ucfirst($field) . ' must not exceed ' . $max . ' characters';
                    }
                }
                if ($rule === 'email' && !empty($data[$field]) && !validate_email($data[$field])) {
                    $errors[$field] = 'Invalid email address';
                }
            }
        }
        return $errors;
    }

    protected function setFlash($key, $value) {
        session_flash($key, $value);
    }

    protected function getFlash($key) {
        return session_flash($key);
    }

    protected function hasFlash($key) {
        return has_flash($key);
    }

    protected function csrfVerification() {
        $token = $this->post('csrf_token');
        if (!$token || !verify_csrf($token)) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
    }
}
