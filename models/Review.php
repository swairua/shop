<?php
class Review extends Model {
    protected $table = 'reviews';

    public function getByProduct($productId, $status = 'approved') {
        $sql = "SELECT r.*, c.name as customer_name FROM reviews r
                LEFT JOIN customers c ON r.customer_id = c.id
                WHERE r.product_id = ? AND r.status = ? ORDER BY r.created_at DESC";
        return $this->query($sql, [$productId, $status]);
    }

    public function getPendingCount() {
        return $this->count("status = 'pending'");
    }
}
