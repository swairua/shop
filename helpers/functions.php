<?php
function base_url($path = '') {
    $config = require __DIR__ . '/../config/app.php';
    return rtrim($config['url'], '/') . '/' . ltrim($path, '/');
}

function redirect($url) {
    header('Location: ' . base_url($url));
    exit;
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf($token) {
    if (empty($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function session_set($key, $value) {
    $_SESSION[$key] = $value;
}

function session_get($key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

function session_flash($key, $value = null) {
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return;
    }
    $val = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $val;
}

function has_flash($key) {
    return isset($_SESSION['flash'][$key]);
}

function is_admin() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function is_customer() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

function admin_guard() {
    if (!is_admin()) {
        redirect('admin/login');
    }
}

function customer_guard() {
    if (!is_customer()) {
        redirect('login');
    }
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

function truncate($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . $suffix;
}

function format_price($amount, $currency = null) {
    $config = require __DIR__ . '/../config/app.php';
    $symbol = $currency ?? $config['currency_symbol'];
    $pos = $config['currency_position'];
    $formatted = number_format((float)$amount, 2);
    return $pos === 'before' ? $symbol . $formatted : $formatted . $symbol;
}

function format_date($date, $format = 'Y-m-d H:i:s') {
    if (empty($date)) return '-';
    $dt = new DateTime($date);
    return $dt->format($format);
}

function format_date_short($date) {
    return format_date($date, 'd M Y');
}

function time_ago($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}

function is_active($path, $class = 'active') {
    $url = $_GET['url'] ?? '';
    return strpos($url, $path) === 0 ? $class : '';
}

function mask_string($string, $start = 4, $end = 4) {
    $len = strlen($string);
    if ($len <= $start + $end) return $string;
    return substr($string, 0, $start) . str_repeat('*', $len - $start - $end) . substr($string, -$end);
}

function log_error($message, $context = []) {
    $logFile = __DIR__ . '/../logs/error.log';
    $entry = date('Y-m-d H:i:s') . ' - ' . $message;
    if (!empty($context)) {
        $entry .= ' - ' . json_encode($context);
    }
    $entry .= PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

function get_status_badge($status) {
    $map = [
        'active' => 'bg-success',
        'inactive' => 'bg-secondary',
        'pending' => 'bg-warning text-dark',
        'processing' => 'bg-info text-dark',
        'shipped' => 'bg-primary',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-danger',
        'refunded' => 'bg-dark',
        'paid' => 'bg-success',
        'unpaid' => 'bg-danger',
        'draft' => 'bg-secondary',
        'published' => 'bg-success',
        'low_stock' => 'bg-warning text-dark',
        'out_of_stock' => 'bg-danger',
        'in_stock' => 'bg-success',
        'approved' => 'bg-success',
        'disapproved' => 'bg-danger',
    ];
    $class = $map[$status] ?? 'bg-secondary';
    return '<span class="badge ' . $class . '">' . ucfirst($status) . '</span>';
}

function generate_sku($prefix = 'PRD') {
    return $prefix . strtoupper(substr(uniqid(), -8));
}

function generate_barcode() {
    return '2' . str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
}

function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone($phone) {
    return preg_match('/^\+?[\d\s\-\(\)]{7,20}$/', $phone);
}

function paginate($currentPage, $totalPages, $url = '?page={page}') {
    if ($totalPages <= 1) return '';
    $html = '<nav><ul class="pagination">';
    $prev = $currentPage > 1 ? $currentPage - 1 : 1;
    $html .= '<li class="page-item ' . ($currentPage <= 1 ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . str_replace('{page}', $prev, $url) . '">Previous</a></li>';
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $i, $url) . '">' . $i . '</a></li>';
        }
    }
    $next = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
    $html .= '<li class="page-item ' . ($currentPage >= $totalPages ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . str_replace('{page}', $next, $url) . '">Next</a></li>';
    $html .= '</ul></nav>';
    return $html;
}

function upload_file($file, $targetDir, $allowedTypes = ['jpg','jpeg','png','gif','webp'], $maxSize = 5242880) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error code: ' . $file['error']];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large'];
    }
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $dest = $targetDir . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => true, 'filename' => $filename, 'path' => $dest];
    }
    return ['success' => false, 'message' => 'Failed to move uploaded file'];
}

function product_image($path, $size = '600x600', $text = 'No+Image') {
    if (empty($path)) return 'https://via.placeholder.com/' . $size . '?text=' . $text;
    if (strpos($path, 'http') === 0) return $path;
    return base_url('uploads/products/' . $path);
}

function wa_link($text = '', $number = null) {
    $num = $number ?: App::getSetting('whatsapp_number', '');
    $num = preg_replace('/[^0-9]/', '', $num);
    if (empty($num)) return '#';
    $msg = rawurlencode($text ?: App::getSetting('whatsapp_message', 'Hi! I want to order from your shop'));
    return 'https://wa.me/' . $num . '?text=' . $msg;
}

function get_gravatar($email, $size = 80) {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/$hash?s=$size&d=mm";
}
