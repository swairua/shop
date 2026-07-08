<?php
class Cart extends Model {
    protected $table = 'cart';

    public function getCustomerCart($customerId) {
        $sql = "SELECT c.*, co.code as coupon_code, co.type as coupon_type, co.value as coupon_value FROM cart c
                LEFT JOIN coupons co ON c.coupon_id = co.id
                WHERE c.customer_id = ? ORDER BY c.id DESC LIMIT 1";
        $cart = $this->queryRow($sql, [$customerId]);
        if ($cart) {
            $cart['items'] = $this->query(
                "SELECT ci.*, p.name, p.slug, p.selling_price, p.discount_price, p.featured_image, p.quantity as stock_quantity, p.status as product_status
                 FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?",
                [$cart['id']]
            );
        }
        return $cart;
    }

    public function getSessionCart($sessionId) {
        $sql = "SELECT c.*, co.code as coupon_code, co.type as coupon_type, co.value as coupon_value FROM cart c
                LEFT JOIN coupons co ON c.coupon_id = co.id
                WHERE c.session_id = ? ORDER BY c.id DESC LIMIT 1";
        $cart = $this->queryRow($sql, [$sessionId]);
        if ($cart) {
            $cart['items'] = $this->query(
                "SELECT ci.*, p.name, p.slug, p.selling_price, p.discount_price, p.featured_image, p.quantity as stock_quantity, p.status as product_status
                 FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?",
                [$cart['id']]
            );
        }
        return $cart;
    }

    public function getOrCreateCart($customerId = null, $sessionId = null) {
        if ($customerId) {
            $cart = $this->getCustomerCart($customerId);
        } else {
            $cart = $this->getSessionCart($sessionId);
        }
        if (!$cart) {
            $cartId = $this->create([
                'customer_id' => $customerId,
                'session_id' => $sessionId
            ]);
            $cart = $this->find($cartId);
            $cart['items'] = [];
        }
        return $cart;
    }

    public function addItem($cartId, $productId, $quantity = 1, $variantId = null, $unitPrice = null) {
        if (!$unitPrice) {
            $product = (new Product())->find($productId);
            $unitPrice = $product['discount_price'] ?: $product['selling_price'];
        }

        $existing = $this->queryRow("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ? AND (variant_id = ? OR (variant_id IS NULL AND ? IS NULL))",
            [$cartId, $productId, $variantId, $variantId]);

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $stmt = $this->db->prepare("UPDATE cart_items SET quantity = ?, total_price = ? * ? WHERE id = ?");
            $stmt->bind_param("iddi", $newQty, $unitPrice, $newQty, $existing['id']);
            $stmt->execute();
        } else {
            $totalPrice = $unitPrice * $quantity;
            $stmt = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, variant_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiidd", $cartId, $productId, $variantId, $quantity, $unitPrice, $totalPrice);
            $stmt->execute();
        }

        $this->recalculate($cartId);
        return true;
    }

    public function updateItemQuantity($itemId, $quantity) {
        $item = $this->queryRow("SELECT * FROM cart_items WHERE id = ?", [$itemId]);
        if (!$item) return false;

        if ($quantity <= 0) {
            return $this->removeItem($itemId);
        }

        $stmt = $this->db->prepare("UPDATE cart_items SET quantity = ?, total_price = unit_price * ? WHERE id = ?");
        $stmt->bind_param("idi", $quantity, $quantity, $itemId);
        $stmt->execute();

        $this->recalculate($item['cart_id']);
        return true;
    }

    public function removeItem($itemId) {
        $item = $this->queryRow("SELECT cart_id FROM cart_items WHERE id = ?", [$itemId]);
        if (!$item) return false;
        $this->deleteItem($itemId);
        $this->recalculate($item['cart_id']);
        return true;
    }

    private function deleteItem($itemId) {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
    }

    public function recalculate($cartId) {
        $result = $this->queryRow("SELECT COALESCE(SUM(total_price), 0) as subtotal FROM cart_items WHERE cart_id = ?", [$cartId]);
        $subtotal = $result['subtotal'];

        $cart = $this->find($cartId);
        $discount = 0;
        if ($cart && $cart['coupon_id']) {
            $coupon = (new Coupon())->find($cart['coupon_id']);
            if ($coupon && $coupon['status'] == 'active') {
                $discount = (new Coupon())->calculateDiscount($coupon, $subtotal);
            }
        }

        $tax = 0;
        $taxEnabled = App::getSetting('tax_enabled');
        if ($taxEnabled) {
            $taxRate = floatval(App::getSetting('tax_rate') ?: 0);
            $tax = ($subtotal - $discount) * ($taxRate / 100);
        }

        $total = $subtotal - $discount + $tax + ($cart['shipping_cost'] ?? 0);

        $stmt = $this->db->prepare("UPDATE cart SET subtotal = ?, discount = ?, tax = ?, total = ? WHERE id = ?");
        $stmt->bind_param("ddddi", $subtotal, $discount, $tax, $total, $cartId);
        $stmt->execute();
    }

    public function applyCoupon($cartId, $code) {
        $coupon = (new Coupon())->findBy('code', $code, 1);
        if (!$coupon || $coupon['status'] != 'active') {
            return ['success' => false, 'message' => 'Invalid or expired coupon'];
        }

        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Coupon has expired'];
        }

        if ($coupon['usage_limit'] && $coupon['used_count'] >= $coupon['usage_limit']) {
            return ['success' => false, 'message' => 'Coupon usage limit reached'];
        }

        $stmt = $this->db->prepare("UPDATE cart SET coupon_id = ?, coupon_code = ? WHERE id = ?");
        $stmt->bind_param("isi", $coupon['id'], $code, $cartId);
        $stmt->execute();

        $this->recalculate($cartId);
        return ['success' => true, 'message' => 'Coupon applied successfully'];
    }

    public function removeCoupon($cartId) {
        $stmt = $this->db->prepare("UPDATE cart SET coupon_id = NULL, coupon_code = NULL, discount = 0 WHERE id = ?");
        $stmt->bind_param("i", $cartId);
        $stmt->execute();
        $this->recalculate($cartId);
        return true;
    }

    public function clearCart($cartId) {
        $this->db->query("DELETE FROM cart_items WHERE cart_id = {$cartId}");
        $this->db->query("UPDATE cart SET subtotal = 0, discount = 0, tax = 0, shipping_cost = 0, total = 0 WHERE id = {$cartId}");
    }

    public function mergeGuestCart($sessionId, $customerId) {
        $guestCart = $this->getSessionCart($sessionId);
        if (!$guestCart || empty($guestCart['items'])) return;

        $customerCart = $this->getOrCreateCart($customerId);
        foreach ($guestCart['items'] as $item) {
            $this->addItem($customerCart['id'], $item['product_id'], $item['quantity'], $item['variant_id'], $item['unit_price']);
        }

        $this->db->query("DELETE FROM cart_items WHERE cart_id = {$guestCart['id']}");
        $this->db->query("DELETE FROM cart WHERE id = {$guestCart['id']}");
    }
}
