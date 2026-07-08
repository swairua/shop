<?php
class Brand extends Model {
    protected $table = 'brands';

    public function getWithProductCount() {
        $sql = "SELECT b.*, (SELECT COUNT(*) FROM products p WHERE p.brand_id = b.id AND p.status = 'active') as product_count
                FROM brands b ORDER BY b.name ASC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
