<?php
class Customer extends Model {
    protected $table = 'customers';

    public function getWithOrders($id) {
        $customer = $this->find($id);
        if ($customer) {
            $customer['orders'] = $this->query("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC", [$id]);
            $customer['addresses'] = $this->query("SELECT * FROM customer_addresses WHERE customer_id = ?", [$id]);
            $customer['total_orders'] = count($customer['orders']);
            $customer['total_spent'] = array_sum(array_column($customer['orders'], 'total'));
            $customer['wishlist_count'] = $this->queryRow("SELECT COUNT(*) as cnt FROM wishlist WHERE customer_id = ?", [$id])['cnt'];
        }
        return $customer;
    }

    public function getRecent($limit = 10) {
        return $this->query("SELECT * FROM customers ORDER BY created_at DESC LIMIT ?", [$limit]);
    }
}
