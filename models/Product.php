<?php
class Product extends Model {
    protected $table = 'products';

    public function getWithRelations($id) {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name, t.name as tax_name, t.rate as tax_rate
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                LEFT JOIN tax_classes t ON p.tax_class_id = t.id
                WHERE p.id = ?";
        return $this->queryRow($sql, [$id]);
    }

    public function getImages($productId) {
        return $this->query("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC, id ASC", [$productId]);
    }

    public function getMainImage($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = ? AND is_main = 1 LIMIT 1";
        $result = $this->queryRow($sql, [$productId]);
        if ($result) return $result;
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC, id ASC LIMIT 1";
        return $this->queryRow($sql, [$productId]);
    }

    public function getVariants($productId) {
        return $this->query("SELECT * FROM product_variants WHERE product_id = ? AND status = 'active'", [$productId]);
    }

    public function getReviews($productId, $limit = null) {
        $sql = "SELECT r.*, c.name as customer_name FROM reviews r
                LEFT JOIN customers c ON r.customer_id = c.id
                WHERE r.product_id = ? AND r.status = 'approved'
                ORDER BY r.created_at DESC";
        if ($limit) $sql .= " LIMIT " . intval($limit);
        return $this->query($sql, [$productId]);
    }

    public function getAverageRating($productId) {
        $result = $this->queryRow("SELECT AVG(rating) as avg, COUNT(*) as total FROM reviews WHERE product_id = ? AND status = 'approved'", [$productId]);
        return ['average' => round($result['avg'] ?? 0, 1), 'total' => $result['total'] ?? 0];
    }

    public function getRelated($productId, $categoryId, $limit = 4) {
        return $this->query(
            "SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' ORDER BY RAND() LIMIT ?",
            [$categoryId, $productId, $limit]
        );
    }

    public function getFeatured($limit = 8) {
        return $this->query("SELECT * FROM products WHERE status = 'active' AND is_featured = 1 ORDER BY created_at DESC LIMIT ?", [$limit]);
    }

    public function getNewArrivals($limit = 8) {
        return $this->query("SELECT * FROM products WHERE status = 'active' AND is_new_arrival = 1 ORDER BY created_at DESC LIMIT ?", [$limit]);
    }

    public function getBestSellers($limit = 8) {
        return $this->query("SELECT * FROM products WHERE status = 'active' AND is_best_seller = 1 ORDER BY created_at DESC LIMIT ?", [$limit]);
    }

    public function search($query, $filters = [], $page = 1, $perPage = 12) {
        $where = "p.status = 'active'";
        $params = [];

        if (!empty($query)) {
            $where .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.short_description LIKE ?)";
            $q = '%' . $query . '%';
            $params = array_merge($params, [$q, $q, $q]);
        }

        if (!empty($filters['category_id'])) {
            $cat = new Category();
            $catIds = $cat->getCategoryIdsRecursive($filters['category_id']);
            $ids = implode(',', $catIds);
            $where .= " AND p.category_id IN ({$ids})";
        }

        if (!empty($filters['brand_id'])) {
            $where .= " AND p.brand_id = ?";
            $params[] = $filters['brand_id'];
        }

        if (!empty($filters['min_price'])) {
            $where .= " AND p.selling_price >= ?";
            $params[] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where .= " AND p.selling_price <= ?";
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['rating'])) {
            $where .= " AND p.id IN (SELECT product_id FROM reviews WHERE rating >= ? AND status = 'approved')";
            $params[] = $filters['rating'];
        }

        if (!empty($filters['featured'])) {
            $where .= " AND p.is_featured = 1";
        }

        if (!empty($filters['type'])) {
            $where .= " AND p.type = ?";
            $params[] = $filters['type'];
        }

        $offset = ($page - 1) * $perPage;
        $countSql = "SELECT COUNT(*) as total FROM products p WHERE {$where}";
        $total = $this->queryRow($countSql, $params)['total'] ?? 0;
        $totalPages = ceil($total / $perPage);

        $orderBy = "p.created_at DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc': $orderBy = "p.selling_price ASC"; break;
                case 'price_desc': $orderBy = "p.selling_price DESC"; break;
                case 'name_asc': $orderBy = "p.name ASC"; break;
                case 'name_desc': $orderBy = "p.name DESC"; break;
                case 'newest': $orderBy = "p.created_at DESC"; break;
                case 'popular': $orderBy = "p.views DESC"; break;
            }
        }

        $sql = "SELECT p.* FROM products p WHERE {$where} ORDER BY {$orderBy} LIMIT ?, ?";
        $params[] = intval($offset);
        $params[] = intval($perPage);
        $data = $this->query($sql, $params);

        return compact('data', 'total', 'page', 'perPage', 'totalPages');
    }

    public function updateQuantity($productId, $quantity) {
        $stmt = $this->db->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $productId);
        return $stmt->execute();
    }

    public function getLowStock($threshold = null) {
        if ($threshold === null) {
            $threshold = 5;
        }
        return $this->query("SELECT * FROM products WHERE quantity <= ? AND quantity > 0 AND status = 'active' ORDER BY quantity ASC", [$threshold]);
    }

    public function getOutOfStock() {
        return $this->query("SELECT * FROM products WHERE quantity <= 0 AND status = 'active'");
    }

    public function getTopSelling($limit = 5) {
        $sql = "SELECT p.*, SUM(oi.quantity) as total_sold
                FROM products p
                JOIN order_items oi ON p.id = oi.product_id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.order_status NOT IN ('cancelled', 'refunded')
                GROUP BY p.id
                ORDER BY total_sold DESC LIMIT ?";
        return $this->query($sql, [$limit]);
    }
}
