<?php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthPage()) {
            admin_guard();
        }
    }

    private function isAuthPage() {
        $url = $_GET['url'] ?? '';
        return in_array($url, ['admin/login', 'admin/forgot-password', 'admin/reset-password']);
    }

    public function login() {
        if (is_admin()) $this->redirect('admin/dashboard');

        if ($this->isPost()) {
            $auth = new Auth();
            $email = sanitize_input($this->post('email'));
            $password = $this->post('password');
            $remember = $this->post('remember') ? true : false;

            if ($auth->adminLogin($email, $password, $remember)) {
                $this->redirect('admin/dashboard');
            }
            $this->setFlash('error', 'Invalid email or password');
        }
        $this->view('admin/login');
    }

    public function logout() {
        $auth = new Auth();
        $auth->adminLogout();
        $this->redirect('admin/login');
    }

    public function dashboard() {
        $order = new Order();
        $product = new Product();
        $customer = new Customer();

        $salesStats = $order->getSalesStats(30);
        $data = [
            'totalOrders' => $order->count(),
            'totalRevenue' => $order->queryRow("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE order_status NOT IN ('cancelled', 'refunded')")['total'],
            'totalProducts' => $product->count("status = 'active'"),
            'totalCustomers' => $customer->count("status = 'active'"),
            'recentOrders' => $order->getRecent(10),
            'topSelling' => $product->getTopSelling(5),
            'lowStock' => $product->getLowStock(),
            'salesChart' => $order->getSalesChart(30),
            'salesStats' => $salesStats,
            'recentCustomers' => $customer->getRecent(5),
            'orderStatusCounts' => $order->getStatusCounts()
        ];
        $this->renderAdmin('admin/dashboard', $data);
    }

    public function categories() {
        $category = new Category();
        $data = ['categories' => $category->getTree()];
        $this->renderAdmin('admin/categories/index', $data);
    }

    public function categoryCreate() {
        $category = new Category();
        if ($this->isPost()) {
            $data = [
                'parent_id' => $this->post('parent_id') ?: null,
                'name' => sanitize_input($this->post('name')),
                'slug' => sanitize_input($this->post('slug') ?: slugify($this->post('name'))),
                'description' => sanitize_input($this->post('description')),
                'meta_title' => sanitize_input($this->post('meta_title')),
                'meta_description' => sanitize_input($this->post('meta_description')),
                'sort_order' => intval($this->post('sort_order', 0)),
                'status' => $this->post('status', 'active')
            ];
            if (!empty($_FILES['image']['name'])) {
                $upload = upload_file($_FILES['image'], __DIR__ . '/../uploads/categories');
                if ($upload['success']) $data['image'] = $upload['filename'];
            }
            $category->create($data);
            $this->setFlash('success', 'Category created successfully');
            $this->redirect('admin/categories');
        }
        $data = ['parents' => $category->getParentOptions(), 'modal' => !empty($_GET['partial'])];
        if (!empty($_GET['partial'])) {
            $this->view('admin/categories/form', $data);
        } else {
            $this->renderAdmin('admin/categories/form', $data);
        }
    }

    public function categoryEdit($id) {
        $category = new Category();
        $cat = $category->find($id);
        if (!$cat) $this->redirect('admin/categories');

        if ($this->isPost()) {
            $data = [
                'parent_id' => $this->post('parent_id') ?: null,
                'name' => sanitize_input($this->post('name')),
                'slug' => sanitize_input($this->post('slug') ?: slugify($this->post('name'))),
                'description' => sanitize_input($this->post('description')),
                'meta_title' => sanitize_input($this->post('meta_title')),
                'meta_description' => sanitize_input($this->post('meta_description')),
                'sort_order' => intval($this->post('sort_order', 0)),
                'status' => $this->post('status', 'active')
            ];
            if (!empty($_FILES['image']['name'])) {
                $upload = upload_file($_FILES['image'], __DIR__ . '/../uploads/categories');
                if ($upload['success']) $data['image'] = $upload['filename'];
            }
            $category->update($id, $data);
            $this->setFlash('success', 'Category updated successfully');
            $this->redirect('admin/categories');
        }
        $data = ['category' => $cat, 'parents' => $category->getParentOptions(null, $id), 'modal' => !empty($_GET['partial'])];
        if (!empty($_GET['partial'])) {
            $this->view('admin/categories/form', $data);
        } else {
            $this->renderAdmin('admin/categories/form', $data);
        }
    }

    public function categoryDelete($id) {
        $category = new Category();
        $category->delete($id);
        $this->setFlash('success', 'Category deleted');
        $this->redirect('admin/categories');
    }

    public function brands() {
        $brand = new Brand();
        $data = ['brands' => $brand->getWithProductCount()];
        $this->renderAdmin('admin/brands/index', $data);
    }

    public function brandCreate() {
        if ($this->isPost()) {
            $brand = new Brand();
            $data = [
                'name' => sanitize_input($this->post('name')),
                'slug' => sanitize_input($this->post('slug') ?: slugify($this->post('name'))),
                'description' => sanitize_input($this->post('description')),
                'website' => sanitize_input($this->post('website')),
                'meta_title' => sanitize_input($this->post('meta_title')),
                'meta_description' => sanitize_input($this->post('meta_description')),
                'sort_order' => intval($this->post('sort_order', 0)),
                'status' => $this->post('status', 'active')
            ];
            if (!empty($_FILES['logo']['name'])) {
                $upload = upload_file($_FILES['logo'], __DIR__ . '/../uploads/brands');
                if ($upload['success']) $data['logo'] = $upload['filename'];
            }
            $brand->create($data);
            $this->setFlash('success', 'Brand created');
            $this->redirect('admin/brands');
        }
        if (!empty($_GET['partial'])) {
            $this->view('admin/brands/form', ['modal' => true]);
        } else {
            $this->renderAdmin('admin/brands/form');
        }
    }

    public function brandEdit($id) {
        $brand = new Brand();
        $b = $brand->find($id);
        if (!$b) $this->redirect('admin/brands');

        if ($this->isPost()) {
            $data = [
                'name' => sanitize_input($this->post('name')),
                'slug' => sanitize_input($this->post('slug') ?: slugify($this->post('name'))),
                'description' => sanitize_input($this->post('description')),
                'website' => sanitize_input($this->post('website')),
                'meta_title' => sanitize_input($this->post('meta_title')),
                'meta_description' => sanitize_input($this->post('meta_description')),
                'sort_order' => intval($this->post('sort_order', 0)),
                'status' => $this->post('status', 'active')
            ];
            if (!empty($_FILES['logo']['name'])) {
                $upload = upload_file($_FILES['logo'], __DIR__ . '/../uploads/brands');
                if ($upload['success']) $data['logo'] = $upload['filename'];
            }
            $brand->update($id, $data);
            $this->setFlash('success', 'Brand updated');
            $this->redirect('admin/brands');
        }
        $data = ['brand' => $b, 'modal' => !empty($_GET['partial'])];
        if (!empty($_GET['partial'])) {
            $this->view('admin/brands/form', $data);
        } else {
            $this->renderAdmin('admin/brands/form', $data);
        }
    }

    public function brandDelete($id) {
        (new Brand())->delete($id);
        $this->setFlash('success', 'Brand deleted');
        $this->redirect('admin/brands');
    }

    public function products() {
        $product = new Product();
        $page = $this->get('page', 1);
        $results = $product->paginate($page, 20, "1=1", 'created_at', 'DESC');
        foreach ($results['data'] as &$p) {
            $p['category_name'] = '';
            $p['brand_name'] = '';
        }
        $data = ['products' => $results['data'], 'page' => $results['page'], 'totalPages' => $results['totalPages'], 'total' => $results['total']];
        $this->renderAdmin('admin/products/index', $data);
    }

    public function productCreate() {
        if ($this->isPost()) {
            $product = new Product();
            $data = [
                'category_id' => $this->post('category_id') ?: null,
                'brand_id' => $this->post('brand_id') ?: null,
                'tax_class_id' => $this->post('tax_class_id') ?: null,
                'sku' => sanitize_input($this->post('sku', generate_sku())),
                'barcode' => sanitize_input($this->post('barcode', generate_barcode())),
                'name' => sanitize_input($this->post('name')),
                'slug' => sanitize_input($this->post('slug') ?: slugify($this->post('name'))),
                'description' => $this->post('description'),
                'short_description' => sanitize_input($this->post('short_description')),
                'cost_price' => floatval($this->post('cost_price', 0)),
                'selling_price' => floatval($this->post('selling_price', 0)),
                'discount_price' => $this->post('discount_price') ? floatval($this->post('discount_price')) : null,
                'quantity' => intval($this->post('quantity', 0)),
                'reorder_level' => intval($this->post('reorder_level', 5)),
                'weight' => $this->post('weight') ? floatval($this->post('weight')) : null,
                'length' => $this->post('length') ? floatval($this->post('length')) : null,
                'width' => $this->post('width') ? floatval($this->post('width')) : null,
                'height' => $this->post('height') ? floatval($this->post('height')) : null,
                'type' => $this->post('type', 'physical'),
                'status' => $this->post('status', 'draft'),
                'is_featured' => $this->post('is_featured') ? 1 : 0,
                'is_new_arrival' => $this->post('is_new_arrival') ? 1 : 0,
                'is_best_seller' => $this->post('is_best_seller') ? 1 : 0,
                'meta_title' => sanitize_input($this->post('meta_title')),
                'meta_description' => sanitize_input($this->post('meta_description'))
            ];

            $productId = $product->create($data);

            if ($productId && !empty($_FILES['images']['name'][0])) {
                $this->handleProductImages($productId, $_FILES['images']);
            }

            $this->setFlash('success', 'Product created');
            $this->redirect('admin/products');
        }

        $data = [
            'categories' => (new Category())->getParentOptions(),
            'brands' => (new Brand())->where('status', 'active'),
            'taxClasses' => (new Model())->query("SELECT * FROM tax_classes WHERE status = 'active'")
        ];
        $this->renderAdmin('admin/products/form', $data);
    }

    public function productEdit($id) {
        $product = new Product();
        $prod = $product->find($id);
        if (!$prod) $this->redirect('admin/products');

        if ($this->isPost()) {
            $data = [
                'category_id' => $this->post('category_id') ?: null,
                'brand_id' => $this->post('brand_id') ?: null,
                'tax_class_id' => $this->post('tax_class_id') ?: null,
                'sku' => sanitize_input($this->post('sku')),
                'barcode' => sanitize_input($this->post('barcode')),
                'name' => sanitize_input($this->post('name')),
                'slug' => sanitize_input($this->post('slug') ?: slugify($this->post('name'))),
                'description' => $this->post('description'),
                'short_description' => sanitize_input($this->post('short_description')),
                'cost_price' => floatval($this->post('cost_price', 0)),
                'selling_price' => floatval($this->post('selling_price', 0)),
                'discount_price' => $this->post('discount_price') ? floatval($this->post('discount_price')) : null,
                'quantity' => intval($this->post('quantity', 0)),
                'reorder_level' => intval($this->post('reorder_level', 5)),
                'weight' => $this->post('weight') ? floatval($this->post('weight')) : null,
                'length' => $this->post('length') ? floatval($this->post('length')) : null,
                'width' => $this->post('width') ? floatval($this->post('width')) : null,
                'height' => $this->post('height') ? floatval($this->post('height')) : null,
                'type' => $this->post('type', 'physical'),
                'status' => $this->post('status', 'draft'),
                'is_featured' => $this->post('is_featured') ? 1 : 0,
                'is_new_arrival' => $this->post('is_new_arrival') ? 1 : 0,
                'is_best_seller' => $this->post('is_best_seller') ? 1 : 0,
                'meta_title' => sanitize_input($this->post('meta_title')),
                'meta_description' => sanitize_input($this->post('meta_description'))
            ];

            $product->update($id, $data);

            if (!empty($_FILES['images']['name'][0])) {
                $this->handleProductImages($id, $_FILES['images']);
            }

            $this->setFlash('success', 'Product updated');
            $this->redirect('admin/products');
        }

        $data = [
            'product' => $prod,
            'productImages' => $product->getImages($id),
            'categories' => (new Category())->getParentOptions($prod['category_id']),
            'brands' => (new Brand())->where('status', 'active'),
            'taxClasses' => (new Model())->query("SELECT * FROM tax_classes WHERE status = 'active'")
        ];
        $this->renderAdmin('admin/products/form', $data);
    }

    public function productDelete($id) {
        (new Product())->delete($id);
        $this->setFlash('success', 'Product deleted');
        $this->redirect('admin/products');
    }

    private function handleProductImages($productId, $files) {
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            $upload = upload_file($file, __DIR__ . '/../uploads/products');
            if ($upload['success']) {
                $isMain = $i === 0 ? 1 : 0;
                $stmt = $this->db->prepare("INSERT INTO product_images (product_id, image, thumbnail, sort_order, is_main) VALUES (?, ?, ?, ?, ?)");
                $thumb = 'thumb_' . $upload['filename'];
                $stmt->bind_param("issii", $productId, $upload['filename'], $thumb, $i, $isMain);
                $stmt->execute();

                if ($isMain) {
                    $stmt = $this->db->prepare("UPDATE products SET featured_image = ? WHERE id = ?");
                    $stmt->bind_param("si", $upload['filename'], $productId);
                    $stmt->execute();
                }
            }
        }
    }

    public function orders() {
        $order = new Order();
        $page = $this->get('page', 1);
        $status = $this->get('status', '');
        $where = $status ? "order_status = '" . $this->db->escape($status) . "'" : "1=1";
        $results = $order->paginate($page, 20, $where, 'created_at', 'DESC');
        $data = ['orders' => $results['data'], 'page' => $results['page'], 'totalPages' => $results['totalPages'], 'total' => $results['total'], 'currentStatus' => $status];
        $this->renderAdmin('admin/orders/index', $data);
    }

    public function orderView($id) {
        $order = new Order();
        $data = ['order' => $order->getWithItems($id)];
        if (!$data['order']) $this->redirect('admin/orders');
        $this->renderAdmin('admin/orders/view', $data);
    }

    public function orderUpdateStatus($id) {
        if ($this->isPost()) {
            $order = new Order();
            $status = $this->post('status');
            $comment = sanitize_input($this->post('comment'));
            $stmt = $this->db->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
            $order->addStatusHistory($id, $status, $comment);
            $this->setFlash('success', 'Order status updated');
        }
        $this->redirect('admin/orders/view/' . $id);
    }

    public function customers() {
        $customer = new Customer();
        $page = $this->get('page', 1);
        $results = $customer->paginate($page, 20, "1=1", 'created_at', 'DESC');
        $data = ['customers' => $results['data'], 'page' => $results['page'], 'totalPages' => $results['totalPages']];
        $this->renderAdmin('admin/customers/index', $data);
    }

    public function customerView($id) {
        $customer = new Customer();
        $data = ['customer' => $customer->getWithOrders($id)];
        if (!$data['customer']) $this->redirect('admin/customers');
        $this->renderAdmin('admin/customers/view', $data);
    }

    public function suppliers() {
        $supplier = new Model('suppliers');
        $page = $this->get('page', 1);
        $results = $supplier->paginate($page, 20);
        $data = ['suppliers' => $results['data'], 'page' => $results['page'], 'totalPages' => $results['totalPages']];
        $this->renderAdmin('admin/suppliers/index', $data);
    }

    public function supplierCreate() {
        if ($this->isPost()) {
            (new Model('suppliers'))->create(sanitize_input($this->post()));
            $this->setFlash('success', 'Supplier created');
            $this->redirect('admin/suppliers');
        }
        $this->renderAdmin('admin/suppliers/form');
    }

    public function supplierEdit($id) {
        $supplier = new Model('suppliers');
        $s = $supplier->find($id);
        if (!$s) $this->redirect('admin/suppliers');
        if ($this->isPost()) {
            $supplier->update($id, sanitize_input($this->post()));
            $this->setFlash('success', 'Supplier updated');
            $this->redirect('admin/suppliers');
        }
        $data = ['supplier' => $s];
        $this->renderAdmin('admin/suppliers/form', $data);
    }

    public function supplierDelete($id) {
        (new Model('suppliers'))->delete($id);
        $this->setFlash('success', 'Supplier deleted');
        $this->redirect('admin/suppliers');
    }

    public function inventory() {
        $page = $this->get('page', 1);
        $offset = ($page - 1) * 20;
        $data = [];
        $data['products'] = $this->db->query("SELECT id, name, sku, quantity, reorder_level FROM products ORDER BY quantity ASC LIMIT {$offset}, 20")->fetch_all(MYSQLI_ASSOC);
        $total = $this->db->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
        $data['page'] = $page;
        $data['totalPages'] = ceil($total / 20);
        $this->renderAdmin('admin/inventory/index', $data);
    }

    public function inventoryAdjust($id) {
        if ($this->isPost()) {
            $type = $this->post('type');
            $quantity = intval($this->post('quantity'));
            $notes = sanitize_input($this->post('notes'));

            $sign = in_array($type, ['sale', 'transfer_out']) ? -1 : 1;
            $stmt = $this->db->prepare("UPDATE products SET quantity = quantity + (? * ?) WHERE id = ?");
            $stmt->bind_param("iii", $sign, $quantity, $id);
            $stmt->execute();

            $stmt = $this->db->prepare("INSERT INTO inventory_movements (product_id, type, quantity, notes, created_by) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isisi", $id, $type, $quantity, $notes, $_SESSION['admin_id']);
            $stmt->execute();

            $this->setFlash('success', 'Inventory adjusted');
            $this->redirect('admin/inventory');
        }
        $data = ['product' => (new Product())->find($id)];
        $this->renderAdmin('admin/inventory/adjust', $data);
    }

    public function reviews() {
        $page = $this->get('page', 1);
        $offset = ($page - 1) * 20;
        $data['reviews'] = $this->db->query(
            "SELECT r.*, p.name as product_name, c.name as customer_name FROM reviews r
             LEFT JOIN products p ON r.product_id = p.id
             LEFT JOIN customers c ON r.customer_id = c.id
             ORDER BY r.created_at DESC LIMIT {$offset}, 20"
        )->fetch_all(MYSQLI_ASSOC);
        $total = $this->db->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];
        $data['page'] = $page;
        $data['totalPages'] = ceil($total / 20);
        $this->renderAdmin('admin/reviews/index', $data);
    }

    public function reviewApprove($id) {
        $this->db->query("UPDATE reviews SET status = 'approved' WHERE id = {$id}");
        $this->setFlash('success', 'Review approved');
        $this->redirect('admin/reviews');
    }

    public function reviewDisapprove($id) {
        $this->db->query("UPDATE reviews SET status = 'disapproved' WHERE id = {$id}");
        $this->setFlash('success', 'Review disapproved');
        $this->redirect('admin/reviews');
    }

    public function reviewDelete($id) {
        $this->db->query("DELETE FROM reviews WHERE id = {$id}");
        $this->setFlash('success', 'Review deleted');
        $this->redirect('admin/reviews');
    }

    public function coupons() {
        $coupon = new Coupon();
        $data = ['coupons' => $coupon->all()];
        $this->renderAdmin('admin/coupons/index', $data);
    }

    public function couponCreate() {
        if ($this->isPost()) {
            $coupon = new Coupon();
            $coupon->create([
                'code' => strtoupper(sanitize_input($this->post('code'))),
                'type' => $this->post('type'),
                'value' => floatval($this->post('value')),
                'min_order_amount' => $this->post('min_order_amount') ? floatval($this->post('min_order_amount')) : null,
                'max_discount' => $this->post('max_discount') ? floatval($this->post('max_discount')) : null,
                'usage_limit' => $this->post('usage_limit') ? intval($this->post('usage_limit')) : null,
                'usage_per_customer' => $this->post('usage_per_customer') ? intval($this->post('usage_per_customer')) : null,
                'starts_at' => $this->post('starts_at') ?: null,
                'expires_at' => $this->post('expires_at') ?: null,
                'status' => $this->post('status', 'active')
            ]);
            $this->setFlash('success', 'Coupon created');
            $this->redirect('admin/coupons');
        }
        if (!empty($_GET['partial'])) {
            $this->view('admin/coupons/form', ['modal' => true]);
        } else {
            $this->renderAdmin('admin/coupons/form');
        }
    }

    public function couponEdit($id) {
        $coupon = new Coupon();
        $c = $coupon->find($id);
        if (!$c) $this->redirect('admin/coupons');
        if ($this->isPost()) {
            $coupon->update($id, [
                'code' => strtoupper(sanitize_input($this->post('code'))),
                'type' => $this->post('type'),
                'value' => floatval($this->post('value')),
                'min_order_amount' => $this->post('min_order_amount') ? floatval($this->post('min_order_amount')) : null,
                'max_discount' => $this->post('max_discount') ? floatval($this->post('max_discount')) : null,
                'usage_limit' => $this->post('usage_limit') ? intval($this->post('usage_limit')) : null,
                'usage_per_customer' => $this->post('usage_per_customer') ? intval($this->post('usage_per_customer')) : null,
                'starts_at' => $this->post('starts_at') ?: null,
                'expires_at' => $this->post('expires_at') ?: null,
                'status' => $this->post('status', 'active')
            ]);
            $this->setFlash('success', 'Coupon updated');
            $this->redirect('admin/coupons');
        }
        $data = ['coupon' => $c, 'modal' => !empty($_GET['partial'])];
        if (!empty($_GET['partial'])) {
            $this->view('admin/coupons/form', $data);
        } else {
            $this->renderAdmin('admin/coupons/form', $data);
        }
    }

    public function couponDelete($id) {
        (new Coupon())->delete($id);
        $this->setFlash('success', 'Coupon deleted');
        $this->redirect('admin/coupons');
    }

    public function shipping() {
        $data = [
            'zones' => (new Model())->query("SELECT * FROM shipping_zones"),
            'methods' => (new Model())->query("SELECT * FROM shipping_methods ORDER BY sort_order")
        ];
        $this->renderAdmin('admin/shipping/index', $data);
    }

    public function shippingZoneCreate() {
        if ($this->isPost()) {
            (new Model('shipping_zones'))->create(sanitize_input($this->post()));
            $this->setFlash('success', 'Shipping zone created');
        }
        $this->redirect('admin/shipping');
    }

    public function shippingMethodCreate() {
        if ($this->isPost()) {
            (new Model('shipping_methods'))->create(sanitize_input($this->post()));
            $this->setFlash('success', 'Shipping method created');
        }
        $this->redirect('admin/shipping');
    }

    public function reports() {
        $this->renderAdmin('admin/reports/index');
    }

    public function reportsSales() {
        $year = $this->get('year', date('Y'));
        $order = new Order();
        $data = [
            'monthlyData' => $order->getMonthlyRevenue($year),
            'year' => $year,
            'dailyData' => $order->getSalesChart(30),
            'stats' => $order->getSalesStats(365),
            'statusCounts' => $order->getStatusCounts()
        ];
        $this->renderAdmin('admin/reports/sales', $data);
    }

    public function reportsProducts() {
        $product = new Product();
        $data = [
            'topSelling' => $product->getTopSelling(20),
            'lowStock' => $product->getLowStock(10),
            'outOfStock' => $product->getOutOfStock()
        ];
        $this->renderAdmin('admin/reports/products', $data);
    }

    public function reportsCustomers() {
        $customers = $this->db->query(
            "SELECT c.*, (SELECT COUNT(*) FROM orders o WHERE o.customer_id = c.id) as order_count,
             (SELECT COALESCE(SUM(o.total), 0) FROM orders o WHERE o.customer_id = c.id AND o.order_status NOT IN ('cancelled','refunded')) as total_spent
             FROM customers c ORDER BY total_spent DESC"
        )->fetch_all(MYSQLI_ASSOC);
        $data = ['customers' => $customers];
        $this->renderAdmin('admin/reports/customers', $data);
    }

    public function reportsMpesa() {
        $mpesa = new MpesaService();
        $date = $this->get('date', date('Y-m-d'));
        $data = [
            'daily' => $mpesa->getDailyCollections($date),
            'monthly' => $mpesa->getMonthlyCollections(),
            'failed' => $mpesa->getFailedTransactions(20),
            'pending' => $mpesa->getPendingStkRequests(),
            'transactions' => $this->db->query("SELECT * FROM mpesa_transactions ORDER BY created_at DESC LIMIT 50")->fetch_all(MYSQLI_ASSOC)
        ];
        $this->renderAdmin('admin/reports/mpesa', $data);
    }

    public function settings() {
        if ($this->isPost()) {
            $group = $this->post('group', 'general');
            $data = $this->post();
            unset($data['group'], $data['csrf_token']);

            if (!empty($_FILES['shop_logo']['name'])) {
                $upload = upload_file($_FILES['shop_logo'], __DIR__ . '/../uploads/settings');
                if ($upload['success']) $data['shop_logo'] = $upload['filename'];
            }
            if (!empty($_FILES['shop_favicon']['name'])) {
                $upload = upload_file($_FILES['shop_favicon'], __DIR__ . '/../uploads/settings');
                if ($upload['success']) $data['shop_favicon'] = $upload['filename'];
            }

            foreach ($data as $key => $value) {
                App::updateSetting($key, $value);
            }

            $this->setFlash('success', 'Settings saved');
            $this->redirect('admin/settings?group=' . $group);
        }
        $group = $this->get('group', 'general');
        $data = ['settings' => App::getSettings($group), 'group' => $group];
        $this->renderAdmin('admin/settings/index', $data);
    }

    public function mpesa() {
        if ($this->isPost()) {
            $data = $this->post();
            unset($data['csrf_token']);
            foreach ($data as $key => $value) {
                if (strpos($key, 'mpesa_') === 0) {
                    App::updateSetting($key, $value);
                }
            }
            $this->setFlash('success', 'M-Pesa settings saved');
            $this->redirect('admin/mpesa');
        }
        $data = ['settings' => App::getSettings('mpesa')];
        $this->renderAdmin('admin/mpesa/index', $data);
    }

    public function mpesaRegisterUrls() {
        $mpesa = new MpesaService();
        $result = $mpesa->registerC2BUrls();
        if (isset($result['ResponseDescription'])) {
            $this->setFlash('success', 'URLs registered: ' . $result['ResponseDescription']);
        } else {
            $this->setFlash('error', 'Registration failed: ' . json_encode($result));
        }
        $this->redirect('admin/mpesa');
    }

    public function mpesaTransactions() {
        $page = $this->get('page', 1);
        $offset = ($page - 1) * 20;
        $data['transactions'] = $this->db->query("SELECT * FROM mpesa_transactions ORDER BY created_at DESC LIMIT {$offset}, 20")->fetch_all(MYSQLI_ASSOC);
        $total = $this->db->query("SELECT COUNT(*) as total FROM mpesa_transactions")->fetch_assoc()['total'];
        $data['page'] = $page;
        $data['totalPages'] = ceil($total / 20);
        $this->renderAdmin('admin/mpesa/transactions', $data);
    }

    public function mpesaReceipt($id) {
        $mpesa = new MpesaService();
        $data['transaction'] = $mpesa->generateReceipt($id);
        if (!$data['transaction']) $this->redirect('admin/mpesa/transactions');
        $this->renderAdmin('admin/mpesa/receipt', $data);
    }

    public function users() {
        $data['users'] = $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
        $this->renderAdmin('admin/users/index', $data);
    }

    public function userCreate() {
        if ($this->isPost()) {
            $data = [
                'name' => sanitize_input($this->post('name')),
                'email' => sanitize_input($this->post('email')),
                'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
                'role' => $this->post('role', 'staff'),
                'phone' => sanitize_input($this->post('phone')),
                'status' => $this->post('status', 'active')
            ];
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, phone, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $data['name'], $data['email'], $data['password'], $data['role'], $data['phone'], $data['status']);
            $stmt->execute();
            $this->setFlash('success', 'User created');
            $this->redirect('admin/users');
        }
        $this->renderAdmin('admin/users/form');
    }

    public function userEdit($id) {
        $user = $this->db->query("SELECT * FROM users WHERE id = {$id}")->fetch_assoc();
        if (!$user) $this->redirect('admin/users');
        if ($this->isPost()) {
            $data = [
                'name' => sanitize_input($this->post('name')),
                'email' => sanitize_input($this->post('email')),
                'role' => $this->post('role', 'staff'),
                'phone' => sanitize_input($this->post('phone')),
                'status' => $this->post('status', 'active')
            ];
            if ($this->post('password')) {
                $data['password'] = password_hash($this->post('password'), PASSWORD_DEFAULT);
            }
            $sets = '';
            $types = '';
            $vals = [];
            foreach ($data as $k => $v) {
                $sets .= ($sets ? ', ' : '') . "{$k} = ?";
                $types .= 's';
                $vals[] = $v;
            }
            $types .= 'i';
            $vals[] = $id;
            $stmt = $this->db->prepare("UPDATE users SET {$sets} WHERE id = ?");
            $stmt->bind_param($types, ...$vals);
            $stmt->execute();
            $this->setFlash('success', 'User updated');
            $this->redirect('admin/users');
        }
        $data = ['user' => $user];
        $this->renderAdmin('admin/users/form', $data);
    }

    public function userDelete($id) {
        $this->db->query("DELETE FROM users WHERE id = {$id}");
        $this->setFlash('success', 'User deleted');
        $this->redirect('admin/users');
    }
}
