<?php
class Order extends Model {
    protected $table = 'orders';

    public function generateOrderNumber() {
        $prefix = 'ORD-';
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $count = $result['total'] + 1;
        return $prefix . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function createFromCart($cartId, $customerId, $data) {
        $this->db->beginTransaction();
        try {
            $orderNumber = $this->generateOrderNumber();
            $createdAt = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare("INSERT INTO orders (order_number, customer_id, shipping_address_id, billing_address_id,
                coupon_id, subtotal, discount, tax, shipping_cost, total, payment_status, order_status,
                shipping_method, payment_method, notes, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?, ?, ?, ?, ?, ?)");

            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $stmt->bind_param("siiiiddddsssssss",
                $orderNumber, $customerId,
                $data['shipping_address_id'], $data['billing_address_id'],
                $data['coupon_id'], $data['subtotal'], $data['discount'],
                $data['tax'], $data['shipping_cost'], $data['total'],
                $data['shipping_method'], $data['payment_method'], $data['notes'],
                $ip, $ua, $createdAt
            );
            $stmt->execute();
            $orderId = $this->db->insertId();

            $cartItems = $this->query("SELECT ci.*, p.name, p.sku FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?", [$cartId]);
            foreach ($cartItems as $item) {
                $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissidds", $orderId, $item['product_id'], $item['product_name'], $item['product_sku'], $item['quantity'], $item['unit_price'], $item['total_price'], $createdAt);
                $stmt->execute();
                $this->updateQuantity($item['product_id'], -$item['quantity']);
            }

            $this->addStatusHistory($orderId, 'pending', 'Order placed');
            $this->db->query("DELETE FROM cart_items WHERE cart_id = {$cartId}");
            $this->db->query("DELETE FROM cart WHERE id = {$cartId}");

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollback();
            log_error('Order creation failed: ' . $e->getMessage());
            return false;
        }
    }

    public function updateQuantity($productId, $quantity) {
        $stmt = $this->db->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $productId);
        $stmt->execute();
    }

    public function addStatusHistory($orderId, $status, $comment = '') {
        $changedBy = $_SESSION['admin_name'] ?? $_SESSION['customer_name'] ?? 'System';
        $stmt = $this->db->prepare("INSERT INTO order_status_history (order_id, status, comment, changed_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $orderId, $status, $comment, $changedBy);
        $stmt->execute();
    }

    public function getWithItems($orderId) {
        $order = $this->find($orderId);
        if ($order) {
            $order['items'] = $this->query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
            $order['history'] = $this->query("SELECT * FROM order_status_history WHERE order_id = ? ORDER BY created_at DESC", [$orderId]);
        }
        return $order;
    }

    public function getByCustomer($customerId, $limit = null) {
        $sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC";
        if ($limit) $sql .= " LIMIT " . intval($limit);
        return $this->query($sql, [$customerId]);
    }

    public function getRecent($limit = 10) {
        return $this->query("SELECT o.*, c.name as customer_name FROM orders o LEFT JOIN customers c ON o.customer_id = c.id ORDER BY o.created_at DESC LIMIT ?", [$limit]);
    }

    public function getSalesStats($days = 30) {
        $sql = "SELECT COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_revenue, COALESCE(AVG(total), 0) as avg_order
                FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND order_status NOT IN ('cancelled', 'refunded')";
        return $this->queryRow($sql, [$days]);
    }

    public function getSalesChart($days = 30) {
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as orders, COALESCE(SUM(total), 0) as revenue
                FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND order_status NOT IN ('cancelled', 'refunded')
                GROUP BY DATE(created_at) ORDER BY date ASC";
        return $this->query($sql, [$days]);
    }

    public function getStatusCounts() {
        $sql = "SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function updatePayment($orderId, $amount, $status = 'paid') {
        $order = $this->find($orderId);
        if (!$order) return false;

        $paidAmount = $order['paid_amount'] + $amount;
        $outstanding = $order['total'] - $paidAmount;
        $paymentStatus = $outstanding <= 0 ? 'paid' : 'partially_paid';

        $stmt = $this->db->prepare("UPDATE orders SET paid_amount = ?, outstanding_balance = ?, payment_status = ?, order_status = CASE WHEN ? = 'paid' THEN 'processing' ELSE order_status END WHERE id = ?");
        $stmt->bind_param("ddssi", $paidAmount, $outstanding, $paymentStatus, $paymentStatus, $orderId);
        $stmt->execute();
        $this->addStatusHistory($orderId, $status, "Payment received: " . format_price($amount));
        return true;
    }

    public function getMonthlyRevenue($year = null) {
        if (!$year) $year = date('Y');
        $sql = "SELECT MONTH(created_at) as month, COUNT(*) as orders, COALESCE(SUM(total), 0) as revenue
                FROM orders WHERE YEAR(created_at) = ? AND order_status NOT IN ('cancelled', 'refunded')
                GROUP BY MONTH(created_at) ORDER BY month";
        return $this->query($sql, [$year]);
    }
}
