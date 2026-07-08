<?php
class Coupon extends Model {
    protected $table = 'coupons';

    public function calculateDiscount($coupon, $subtotal) {
        if ($coupon['type'] == 'free_shipping') return 0;

        $discount = 0;
        if ($coupon['type'] == 'percentage') {
            $discount = $subtotal * ($coupon['value'] / 100);
            if ($coupon['max_discount']) {
                $discount = min($discount, $coupon['max_discount']);
            }
        } elseif ($coupon['type'] == 'fixed') {
            $discount = min($coupon['value'], $subtotal);
        }

        return $discount;
    }

    public function validateCoupon($code, $subtotal, $customerId = null) {
        $coupon = $this->findBy('code', $code, 1);
        if (!$coupon) return ['valid' => false, 'message' => 'Invalid coupon code'];
        if ($coupon['status'] != 'active') return ['valid' => false, 'message' => 'Coupon is not active'];
        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) return ['valid' => false, 'message' => 'Coupon has expired'];
        if ($coupon['starts_at'] && strtotime($coupon['starts_at']) > time()) return ['valid' => false, 'message' => 'Coupon is not yet valid'];
        if ($coupon['usage_limit'] && $coupon['used_count'] >= $coupon['usage_limit']) return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        if ($coupon['min_order_amount'] && $subtotal < $coupon['min_order_amount']) return ['valid' => false, 'message' => 'Minimum order amount not met'];

        if ($coupon['usage_per_customer'] && $customerId) {
            $used = $this->queryRow("SELECT COUNT(*) as used FROM orders WHERE coupon_id = ? AND customer_id = ?", [$coupon['id'], $customerId])['used'];
            if ($used >= $coupon['usage_per_customer']) return ['valid' => false, 'message' => 'Coupon usage limit per customer reached'];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    public function incrementUsage($couponId) {
        $stmt = $this->db->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
        $stmt->bind_param("i", $couponId);
        $stmt->execute();
    }
}
