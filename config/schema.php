<?php
class Schema {
    private $db;
    private $tables = [];

    public function __construct() {
        $this->db = Database::getInstance();
        $this->getExistingTables();
    }

    private function getExistingTables() {
        $result = $this->db->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $this->tables[] = $row[0];
        }
    }

    private function tableExists($name) {
        return in_array($name, $this->tables);
    }

    public function install() {
        $this->createUsersTable();
        $this->createCategoriesTable();
        $this->createBrandsTable();
        $this->createTaxClassesTable();
        $this->createProductsTable();
        $this->createProductImagesTable();
        $this->createProductVariantsTable();
        $this->createSuppliersTable();
        $this->createCustomersTable();
        $this->createCustomerAddressesTable();
        $this->createCouponsTable();
        $this->createOrdersTable();
        $this->createOrderItemsTable();
        $this->createOrderStatusHistoryTable();
        $this->createInventoryTable();
        $this->createInventoryMovementsTable();
        $this->createReviewsTable();
        $this->createWishlistTable();
        $this->createCompareTable();
        $this->createShippingZonesTable();
        $this->createShippingMethodsTable();
        $this->createShippingRatesTable();
        $this->createPaymentMethodsTable();
        $this->createPaymentsTable();
        $this->createMpesaTransactionsTable();
        $this->createMpesaCallbacksTable();
        $this->createPaymentLogsTable();
        $this->createPaymentReconciliationsTable();
        $this->createPaymentFailuresTable();
        $this->createCallbackLogsTable();
        $this->createSettingsTable();
        $this->createActivityLogsTable();
        $this->createEmailTemplatesTable();
        $this->createPagesTable();
        $this->createCartTable();
        $this->createCartItemsTable();
        $this->seedData();
        return true;
    }

    private function createUsersTable() {
        if ($this->tableExists('users')) return;
        $this->db->query("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('super_admin','admin','staff') NOT NULL DEFAULT 'staff',
            image VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            remember_token VARCHAR(255) DEFAULT NULL,
            remember_token_expiry DATETIME DEFAULT NULL,
            reset_token VARCHAR(255) DEFAULT NULL,
            reset_token_expiry DATETIME DEFAULT NULL,
            last_login DATETIME DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_users_email (email),
            INDEX idx_users_role (role),
            INDEX idx_users_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCategoriesTable() {
        if ($this->tableExists('categories')) return;
        $this->db->query("CREATE TABLE categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            parent_id INT DEFAULT NULL,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            description TEXT DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            icon VARCHAR(50) DEFAULT NULL,
            meta_title VARCHAR(255) DEFAULT NULL,
            meta_description TEXT DEFAULT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_categories_parent (parent_id),
            INDEX idx_categories_slug (slug),
            INDEX idx_categories_status (status),
            INDEX idx_categories_sort (sort_order),
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createBrandsTable() {
        if ($this->tableExists('brands')) return;
        $this->db->query("CREATE TABLE brands (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            description TEXT DEFAULT NULL,
            logo VARCHAR(255) DEFAULT NULL,
            website VARCHAR(255) DEFAULT NULL,
            meta_title VARCHAR(255) DEFAULT NULL,
            meta_description TEXT DEFAULT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_brands_slug (slug),
            INDEX idx_brands_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createTaxClassesTable() {
        if ($this->tableExists('tax_classes')) return;
        $this->db->query("CREATE TABLE tax_classes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            rate DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            type ENUM('percentage','fixed') NOT NULL DEFAULT 'percentage',
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createProductsTable() {
        if ($this->tableExists('products')) return;
        $this->db->query("CREATE TABLE products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT DEFAULT NULL,
            brand_id INT DEFAULT NULL,
            tax_class_id INT DEFAULT NULL,
            sku VARCHAR(50) NOT NULL UNIQUE,
            barcode VARCHAR(50) DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description LONGTEXT DEFAULT NULL,
            short_description TEXT DEFAULT NULL,
            cost_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            selling_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            discount_price DECIMAL(12,2) DEFAULT NULL,
            discount_start DATETIME DEFAULT NULL,
            discount_end DATETIME DEFAULT NULL,
            quantity INT NOT NULL DEFAULT 0,
            reorder_level INT NOT NULL DEFAULT 5,
            weight DECIMAL(10,2) DEFAULT NULL,
            length DECIMAL(10,2) DEFAULT NULL,
            width DECIMAL(10,2) DEFAULT NULL,
            height DECIMAL(10,2) DEFAULT NULL,
            featured_image VARCHAR(255) DEFAULT NULL,
            type ENUM('physical','digital') NOT NULL DEFAULT 'physical',
            status ENUM('active','inactive','draft') NOT NULL DEFAULT 'draft',
            is_featured TINYINT(1) NOT NULL DEFAULT 0,
            is_new_arrival TINYINT(1) NOT NULL DEFAULT 0,
            is_best_seller TINYINT(1) NOT NULL DEFAULT 0,
            meta_title VARCHAR(255) DEFAULT NULL,
            meta_description TEXT DEFAULT NULL,
            views INT NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_products_category (category_id),
            INDEX idx_products_brand (brand_id),
            INDEX idx_products_sku (sku),
            INDEX idx_products_slug (slug),
            INDEX idx_products_status (status),
            INDEX idx_products_featured (is_featured),
            INDEX idx_products_price (selling_price),
            INDEX idx_products_type (type),
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
            FOREIGN KEY (tax_class_id) REFERENCES tax_classes(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createProductImagesTable() {
        if ($this->tableExists('product_images')) return;
        $this->db->query("CREATE TABLE product_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            image VARCHAR(255) NOT NULL,
            thumbnail VARCHAR(255) DEFAULT NULL,
            medium VARCHAR(255) DEFAULT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            is_main TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_prodimg_product (product_id),
            INDEX idx_prodimg_main (is_main),
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createProductVariantsTable() {
        if ($this->tableExists('product_variants')) return;
        $this->db->query("CREATE TABLE product_variants (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            sku VARCHAR(50) NOT NULL,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(12,2) DEFAULT NULL,
            quantity INT NOT NULL DEFAULT 0,
            image VARCHAR(255) DEFAULT NULL,
            attributes JSON DEFAULT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_var_product (product_id),
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createSuppliersTable() {
        if ($this->tableExists('suppliers')) return;
        $this->db->query("CREATE TABLE suppliers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            contact_person VARCHAR(100) DEFAULT NULL,
            email VARCHAR(100) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            address TEXT DEFAULT NULL,
            city VARCHAR(100) DEFAULT NULL,
            state VARCHAR(100) DEFAULT NULL,
            postal_code VARCHAR(20) DEFAULT NULL,
            country VARCHAR(100) DEFAULT NULL,
            tax_id VARCHAR(50) DEFAULT NULL,
            payment_terms VARCHAR(100) DEFAULT NULL,
            notes TEXT DEFAULT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_suppliers_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCustomersTable() {
        if ($this->tableExists('customers')) return;
        $this->db->query("CREATE TABLE customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            phone VARCHAR(20) DEFAULT NULL,
            password VARCHAR(255) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            notes TEXT DEFAULT NULL,
            status ENUM('active','inactive','banned') NOT NULL DEFAULT 'active',
            email_verified_at DATETIME DEFAULT NULL,
            remember_token VARCHAR(255) DEFAULT NULL,
            remember_token_expiry DATETIME DEFAULT NULL,
            reset_token VARCHAR(255) DEFAULT NULL,
            reset_token_expiry DATETIME DEFAULT NULL,
            last_login DATETIME DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_customers_email (email),
            INDEX idx_customers_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCustomerAddressesTable() {
        if ($this->tableExists('customer_addresses')) return;
        $this->db->query("CREATE TABLE customer_addresses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT NOT NULL,
            type ENUM('shipping','billing','both') NOT NULL DEFAULT 'both',
            address_line1 VARCHAR(255) NOT NULL,
            address_line2 VARCHAR(255) DEFAULT NULL,
            city VARCHAR(100) NOT NULL,
            state VARCHAR(100) DEFAULT NULL,
            postal_code VARCHAR(20) DEFAULT NULL,
            country VARCHAR(100) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            is_default TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_custaddr_customer (customer_id),
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createOrdersTable() {
        if ($this->tableExists('orders')) return;
        $this->db->query("CREATE TABLE orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_number VARCHAR(50) NOT NULL UNIQUE,
            customer_id INT DEFAULT NULL,
            shipping_address_id INT DEFAULT NULL,
            billing_address_id INT DEFAULT NULL,
            coupon_id INT DEFAULT NULL,
            subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            discount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            tax DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            outstanding_balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            payment_status ENUM('pending','paid','partially_paid','refunded','failed') NOT NULL DEFAULT 'pending',
            order_status ENUM('pending','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
            shipping_method VARCHAR(100) DEFAULT NULL,
            payment_method VARCHAR(100) DEFAULT NULL,
            notes TEXT DEFAULT NULL,
            staff_notes TEXT DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            user_agent TEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_orders_customer (customer_id),
            INDEX idx_orders_number (order_number),
            INDEX idx_orders_status (order_status),
            INDEX idx_orders_payment (payment_status),
            INDEX idx_orders_date (created_at),
            INDEX idx_orders_coupon (coupon_id),
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
            FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createOrderItemsTable() {
        if ($this->tableExists('order_items')) return;
        $this->db->query("CREATE TABLE order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT DEFAULT NULL,
            variant_id INT DEFAULT NULL,
            product_name VARCHAR(255) NOT NULL,
            product_sku VARCHAR(50) DEFAULT NULL,
            quantity INT NOT NULL DEFAULT 1,
            unit_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            total_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            tax_rate DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            tax_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_orditem_order (order_id),
            INDEX idx_orditem_product (product_id),
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createOrderStatusHistoryTable() {
        if ($this->tableExists('order_status_history')) return;
        $this->db->query("CREATE TABLE order_status_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            status VARCHAR(50) NOT NULL,
            comment TEXT DEFAULT NULL,
            changed_by VARCHAR(100) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_ordhist_order (order_id),
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createInventoryTable() {
        if ($this->tableExists('inventory')) return;
        $this->db->query("CREATE TABLE inventory (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            warehouse VARCHAR(100) DEFAULT 'Main',
            quantity INT NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_inv_product (product_id),
            UNIQUE KEY uk_inv_product_warehouse (product_id, warehouse),
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createInventoryMovementsTable() {
        if ($this->tableExists('inventory_movements')) return;
        $this->db->query("CREATE TABLE inventory_movements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            type ENUM('purchase','sale','return','adjustment','transfer_in','transfer_out') NOT NULL,
            quantity INT NOT NULL,
            reference VARCHAR(100) DEFAULT NULL,
            notes TEXT DEFAULT NULL,
            created_by INT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_invmov_product (product_id),
            INDEX idx_invmov_type (type),
            INDEX idx_invmov_created (created_at),
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCouponsTable() {
        if ($this->tableExists('coupons')) return;
        $this->db->query("CREATE TABLE coupons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL UNIQUE,
            type ENUM('percentage','fixed','free_shipping') NOT NULL DEFAULT 'percentage',
            value DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            min_order_amount DECIMAL(12,2) DEFAULT NULL,
            max_discount DECIMAL(12,2) DEFAULT NULL,
            usage_limit INT DEFAULT NULL,
            usage_per_customer INT DEFAULT NULL,
            used_count INT NOT NULL DEFAULT 0,
            starts_at DATETIME DEFAULT NULL,
            expires_at DATETIME DEFAULT NULL,
            status ENUM('active','inactive','expired') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_coupons_code (code),
            INDEX idx_coupons_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createReviewsTable() {
        if ($this->tableExists('reviews')) return;
        $this->db->query("CREATE TABLE reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            customer_id INT DEFAULT NULL,
            name VARCHAR(100) DEFAULT NULL,
            email VARCHAR(100) DEFAULT NULL,
            rating INT NOT NULL DEFAULT 5,
            title VARCHAR(255) DEFAULT NULL,
            review TEXT DEFAULT NULL,
            status ENUM('pending','approved','disapproved') NOT NULL DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_reviews_product (product_id),
            INDEX idx_reviews_customer (customer_id),
            INDEX idx_reviews_status (status),
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createWishlistTable() {
        if ($this->tableExists('wishlist')) return;
        $this->db->query("CREATE TABLE wishlist (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT NOT NULL,
            product_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_wish_customer (customer_id),
            INDEX idx_wish_product (product_id),
            UNIQUE KEY uk_wishlist (customer_id, product_id),
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCompareTable() {
        if ($this->tableExists('compare_list')) return;
        $this->db->query("CREATE TABLE compare_list (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT NOT NULL,
            product_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_comp_customer (customer_id),
            INDEX idx_comp_product (product_id),
            UNIQUE KEY uk_compare (customer_id, product_id),
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createShippingZonesTable() {
        if ($this->tableExists('shipping_zones')) return;
        $this->db->query("CREATE TABLE shipping_zones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT DEFAULT NULL,
            countries TEXT DEFAULT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createShippingMethodsTable() {
        if ($this->tableExists('shipping_methods')) return;
        $this->db->query("CREATE TABLE shipping_methods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            description TEXT DEFAULT NULL,
            is_default TINYINT(1) NOT NULL DEFAULT 0,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            sort_order INT NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createShippingRatesTable() {
        if ($this->tableExists('shipping_rates')) return;
        $this->db->query("CREATE TABLE shipping_rates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            zone_id INT NOT NULL,
            method_id INT NOT NULL,
            min_weight DECIMAL(10,2) DEFAULT NULL,
            max_weight DECIMAL(10,2) DEFAULT NULL,
            min_total DECIMAL(12,2) DEFAULT NULL,
            max_total DECIMAL(12,2) DEFAULT NULL,
            cost DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            additional_cost DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            estimated_days VARCHAR(50) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_shiprates_zone (zone_id),
            INDEX idx_shiprates_method (method_id),
            FOREIGN KEY (zone_id) REFERENCES shipping_zones(id) ON DELETE CASCADE,
            FOREIGN KEY (method_id) REFERENCES shipping_methods(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createPaymentMethodsTable() {
        if ($this->tableExists('payment_methods')) return;
        $this->db->query("CREATE TABLE payment_methods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            description TEXT DEFAULT NULL,
            type VARCHAR(50) NOT NULL DEFAULT 'manual',
            config JSON DEFAULT NULL,
            is_default TINYINT(1) NOT NULL DEFAULT 0,
            sort_order INT NOT NULL DEFAULT 0,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createPaymentsTable() {
        if ($this->tableExists('payments')) return;
        $this->db->query("CREATE TABLE payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT DEFAULT NULL,
            customer_id INT DEFAULT NULL,
            payment_method_id INT DEFAULT NULL,
            transaction_id VARCHAR(100) DEFAULT NULL,
            reference VARCHAR(100) DEFAULT NULL,
            amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            net_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            currency VARCHAR(10) DEFAULT 'KES',
            status ENUM('pending','completed','failed','refunded','reversed') NOT NULL DEFAULT 'pending',
            payer_name VARCHAR(150) DEFAULT NULL,
            payer_email VARCHAR(100) DEFAULT NULL,
            payer_phone VARCHAR(20) DEFAULT NULL,
            notes TEXT DEFAULT NULL,
            paid_at DATETIME DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_pay_order (order_id),
            INDEX idx_pay_customer (customer_id),
            INDEX idx_pay_method (payment_method_id),
            INDEX idx_pay_status (status),
            INDEX idx_pay_transaction (transaction_id),
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
            FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createMpesaTransactionsTable() {
        if ($this->tableExists('mpesa_transactions')) return;
        $this->db->query("CREATE TABLE mpesa_transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT DEFAULT NULL,
            customer_id INT DEFAULT NULL,
            transaction_type VARCHAR(50) NOT NULL,
            merchant_request_id VARCHAR(100) DEFAULT NULL,
            checkout_request_id VARCHAR(100) DEFAULT NULL,
            mpesa_receipt_number VARCHAR(50) DEFAULT NULL,
            transaction_date DATETIME DEFAULT NULL,
            phone_number VARCHAR(20) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            balance DECIMAL(12,2) DEFAULT NULL,
            result_code INT DEFAULT NULL,
            result_desc TEXT DEFAULT NULL,
            status ENUM('pending','completed','failed','cancelled','reversed') NOT NULL DEFAULT 'pending',
            raw_callback JSON DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_mpesa_order (order_id),
            INDEX idx_mpesa_customer (customer_id),
            INDEX idx_mpesa_receipt (mpesa_receipt_number),
            INDEX idx_mpesa_request (merchant_request_id),
            INDEX idx_mpesa_checkout (checkout_request_id),
            INDEX idx_mpesa_status (status),
            INDEX idx_mpesa_phone (phone_number),
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createMpesaCallbacksTable() {
        if ($this->tableExists('mpesa_callbacks')) return;
        $this->db->query("CREATE TABLE mpesa_callbacks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaction_type VARCHAR(50) NOT NULL,
            merchant_request_id VARCHAR(100) DEFAULT NULL,
            checkout_request_id VARCHAR(100) DEFAULT NULL,
            result_code INT DEFAULT NULL,
            result_desc TEXT DEFAULT NULL,
            payload LONGTEXT NOT NULL,
            processed TINYINT(1) NOT NULL DEFAULT 0,
            ip_address VARCHAR(45) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_mpeback_type (transaction_type),
            INDEX idx_mpeback_request (merchant_request_id),
            INDEX idx_mpeback_processed (processed)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createPaymentLogsTable() {
        if ($this->tableExists('payment_logs')) return;
        $this->db->query("CREATE TABLE payment_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            payment_id INT DEFAULT NULL,
            action VARCHAR(100) NOT NULL,
            status VARCHAR(50) DEFAULT NULL,
            request_data LONGTEXT DEFAULT NULL,
            response_data LONGTEXT DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            created_by INT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_paylog_payment (payment_id),
            INDEX idx_paylog_action (action)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createPaymentReconciliationsTable() {
        if ($this->tableExists('payment_reconciliations')) return;
        $this->db->query("CREATE TABLE payment_reconciliations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            payment_id INT NOT NULL,
            expected_amount DECIMAL(12,2) NOT NULL,
            actual_amount DECIMAL(12,2) NOT NULL,
            difference DECIMAL(12,2) NOT NULL,
            status ENUM('matched','unmatched','partial') NOT NULL DEFAULT 'unmatched',
            notes TEXT DEFAULT NULL,
            reconciled_at DATETIME DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_payrec_payment (payment_id),
            FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createPaymentFailuresTable() {
        if ($this->tableExists('payment_failures')) return;
        $this->db->query("CREATE TABLE payment_failures (
            id INT AUTO_INCREMENT PRIMARY KEY,
            payment_method_id INT DEFAULT NULL,
            order_id INT DEFAULT NULL,
            transaction_id VARCHAR(100) DEFAULT NULL,
            amount DECIMAL(12,2) NOT NULL,
            error_code VARCHAR(50) DEFAULT NULL,
            error_message TEXT DEFAULT NULL,
            retry_count INT NOT NULL DEFAULT 0,
            last_retry_at DATETIME DEFAULT NULL,
            resolved TINYINT(1) NOT NULL DEFAULT 0,
            payload LONGTEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_payfail_method (payment_method_id),
            INDEX idx_payfail_order (order_id),
            INDEX idx_payfail_resolved (resolved)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCallbackLogsTable() {
        if ($this->tableExists('callback_logs')) return;
        $this->db->query("CREATE TABLE callback_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            source VARCHAR(50) NOT NULL,
            type VARCHAR(50) NOT NULL,
            method VARCHAR(10) NOT NULL,
            headers TEXT DEFAULT NULL,
            payload LONGTEXT DEFAULT NULL,
            response_code INT DEFAULT NULL,
            response_body TEXT DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            processed TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cblog_source (source),
            INDEX idx_cblog_type (type),
            INDEX idx_cblog_processed (processed)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createSettingsTable() {
        if ($this->tableExists('settings')) return;
        $this->db->query("CREATE TABLE settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(100) NOT NULL UNIQUE,
            `value` LONGTEXT DEFAULT NULL,
            group_name VARCHAR(50) NOT NULL DEFAULT 'general',
            type ENUM('text','textarea','image','email','number','select','json') NOT NULL DEFAULT 'text',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_settings_key (`key`),
            INDEX idx_settings_group (group_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createActivityLogsTable() {
        if ($this->tableExists('activity_logs')) return;
        $this->db->query("CREATE TABLE activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT NULL,
            action VARCHAR(100) NOT NULL,
            details TEXT DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            user_agent TEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_actlog_user (user_id),
            INDEX idx_actlog_action (action),
            INDEX idx_actlog_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createEmailTemplatesTable() {
        if ($this->tableExists('email_templates')) return;
        $this->db->query("CREATE TABLE email_templates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            subject VARCHAR(255) NOT NULL,
            body LONGTEXT NOT NULL,
            variables TEXT DEFAULT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createPagesTable() {
        if ($this->tableExists('pages')) return;
        $this->db->query("CREATE TABLE pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content LONGTEXT DEFAULT NULL,
            meta_title VARCHAR(255) DEFAULT NULL,
            meta_description TEXT DEFAULT NULL,
            status ENUM('published','draft') NOT NULL DEFAULT 'draft',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCartTable() {
        if ($this->tableExists('cart')) return;
        $this->db->query("CREATE TABLE cart (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT DEFAULT NULL,
            session_id VARCHAR(100) DEFAULT NULL,
            coupon_id INT DEFAULT NULL,
            coupon_code VARCHAR(50) DEFAULT NULL,
            subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            discount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            tax DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            shipping_address_id INT DEFAULT NULL,
            billing_address_id INT DEFAULT NULL,
            shipping_method VARCHAR(100) DEFAULT NULL,
            notes TEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_cart_customer (customer_id),
            INDEX idx_cart_session (session_id),
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function createCartItemsTable() {
        if ($this->tableExists('cart_items')) return;
        $this->db->query("CREATE TABLE cart_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cart_id INT NOT NULL,
            product_id INT NOT NULL,
            variant_id INT DEFAULT NULL,
            quantity INT NOT NULL DEFAULT 1,
            unit_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            total_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cartitem_cart (cart_id),
            INDEX idx_cartitem_product (product_id),
            FOREIGN KEY (cart_id) REFERENCES cart(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function seedData() {
        $this->seedSettings();
        $this->seedAdminUser();
        $this->seedSampleData();
    }

    private function seedSettings() {
        $defaults = [
            ['shop_name', 'My Shop', 'general', 'text'],
            ['shop_email', 'admin@myshop.com', 'general', 'email'],
            ['shop_phone', '+254700000000', 'general', 'text'],
            ['shop_address', 'Nairobi, Kenya', 'general', 'text'],
            ['shop_currency', 'KES', 'general', 'text'],
            ['shop_currency_symbol', 'KSh', 'general', 'text'],
            ['shop_timezone', 'Africa/Nairobi', 'general', 'text'],
            ['shop_logo', '', 'general', 'image'],
            ['shop_favicon', '', 'general', 'image'],
            ['tax_enabled', '1', 'tax', 'text'],
            ['tax_rate', '16.00', 'tax', 'text'],
            ['tax_label', 'VAT (16%)', 'tax', 'text'],
            ['shipping_enabled', '1', 'shipping', 'text'],
            ['order_prefix', 'ORD-', 'orders', 'text'],
            ['items_per_page', '12', 'catalog', 'text'],
            ['enable_reviews', '1', 'catalog', 'text'],
            ['enable_wishlist', '1', 'catalog', 'text'],
            ['enable_compare', '1', 'catalog', 'text'],
            ['meta_title', 'My Shop - Best Online Store', 'seo', 'text'],
            ['meta_description', 'Welcome to our online store', 'seo', 'text'],
            ['og_title', 'My Shop', 'seo', 'text'],
            ['og_description', 'Welcome to our online store', 'seo', 'text'],
            ['og_image', '', 'seo', 'image'],
            ['facebook_url', '', 'social', 'text'],
            ['twitter_url', '', 'social', 'text'],
            ['instagram_url', '', 'social', 'text'],
            ['youtube_url', '', 'social', 'text'],
            ['tiktok_url', '', 'social', 'text'],
            ['linkedin_url', '', 'social', 'text'],
            ['pinterest_url', '', 'social', 'text'],
            ['whatsapp_number', '', 'social', 'text'],
            ['whatsapp_message', 'Hi! I want to order from your shop', 'social', 'text'],
            ['primary_color', '#2563eb', 'appearance', 'text'],
            ['secondary_color', '#7c3aed', 'appearance', 'text'],
            ['accent_color', '#f59e0b', 'appearance', 'text'],
            ['header_bg', '#0f172a', 'appearance', 'text'],
            ['footer_bg', '#0f172a', 'appearance', 'text'],
            ['font_family', "'Inter', sans-serif", 'appearance', 'text'],
            ['border_radius', '0.5rem', 'appearance', 'text'],
            ['mpesa_environment', 'sandbox', 'mpesa', 'text'],
            ['mpesa_consumer_key', '', 'mpesa', 'text'],
            ['mpesa_consumer_secret', '', 'mpesa', 'text'],
            ['mpesa_passkey', '', 'mpesa', 'text'],
            ['mpesa_shortcode', '174379', 'mpesa', 'text'],
            ['mpesa_till_number', '', 'mpesa', 'text'],
            ['mpesa_initiator_name', '', 'mpesa', 'text'],
            ['mpesa_initiator_password', '', 'mpesa', 'text'],
            ['mpesa_security_certificate', '', 'mpesa', 'text'],
            ['mpesa_callback_url', 'http://localhost/shop/api/mpesa/callback', 'mpesa', 'text'],
            ['mpesa_validation_url', 'http://localhost/shop/api/mpesa/validate', 'mpesa', 'text'],
            ['mpesa_confirmation_url', 'http://localhost/shop/api/mpesa/confirm', 'mpesa', 'text'],
            ['mpesa_queue_timeout_url', 'http://localhost/shop/api/mpesa/timeout', 'mpesa', 'text'],
            ['mpesa_result_url', 'http://localhost/shop/api/mpesa/result', 'mpesa', 'text'],
        ];

        foreach ($defaults as $setting) {
            $stmt = $this->db->prepare("INSERT IGNORE INTO settings (`key`, `value`, group_name, type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $setting[0], $setting[1], $setting[2], $setting[3]);
            $stmt->execute();
        }
    }

    private function seedAdminUser() {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM users");
        $row = $result->fetch_assoc();
        if ($row['cnt'] > 0) return;

        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'super_admin', 'active')");
        $name = 'Super Admin';
        $email = 'admin@shop.com';
        $stmt->bind_param("sss", $name, $email, $password);
        $stmt->execute();
    }

    private function seedSampleData() {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM categories");
        $row = $result->fetch_assoc();
        if ($row['cnt'] > 0) return;

        $this->db->query("INSERT INTO categories (name, slug, description, sort_order, status) VALUES
            ('Electronics', 'electronics', 'Electronic devices and accessories', 1, 'active'),
            ('Fashion', 'fashion', 'Clothing and accessories', 2, 'active'),
            ('Home & Living', 'home-living', 'Home and living products', 3, 'active')");

        $this->db->query("INSERT INTO categories (parent_id, name, slug, description, sort_order, status) VALUES
            (1, 'Phones', 'phones', 'Mobile phones', 1, 'active'),
            (1, 'Computers', 'computers', 'Laptops and desktops', 2, 'active'),
            (2, 'Men', 'men', 'Men fashion', 1, 'active'),
            (2, 'Women', 'women', 'Women fashion', 2, 'active'),
            (3, 'Furniture', 'furniture', 'Home furniture', 1, 'active')");

        $this->db->query("INSERT INTO brands (name, slug, status) VALUES
            ('Apple', 'apple', 'active'),
            ('Samsung', 'samsung', 'active'),
            ('Nike', 'nike', 'active'),
            ('Adidas', 'adidas', 'active')");

        $this->db->query("INSERT INTO tax_classes (name, slug, rate, type, status) VALUES
            ('Standard VAT', 'standard-vat', 16.00, 'percentage', 'active'),
            ('Zero Rated', 'zero-rated', 0.00, 'percentage', 'active')");

        $this->db->query("INSERT INTO products (category_id, brand_id, tax_class_id, sku, barcode, name, slug, description, cost_price, selling_price, quantity, reorder_level, type, status, is_featured, is_new_arrival, is_best_seller, featured_image) VALUES
            (2, 1, 1, 'PRD-001', '200000000001', 'iPhone 15 Pro', 'iphone-15-pro', 'Latest Apple iPhone', 80000.00, 120000.00, 50, 5, 'physical', 'active', 1, 1, 1, 'https://picsum.photos/seed/iphone15/600/600'),
            (2, 2, 1, 'PRD-002', '200000000002', 'Samsung Galaxy S24', 'samsung-galaxy-s24', 'Latest Samsung Galaxy', 70000.00, 100000.00, 40, 5, 'physical', 'active', 1, 1, 0, 'https://picsum.photos/seed/galaxys24/600/600'),
            (3, 3, 1, 'PRD-003', '200000000003', 'Nike Air Max', 'nike-air-max', 'Comfortable running shoes', 4000.00, 8000.00, 100, 10, 'physical', 'active', 0, 0, 1, 'https://picsum.photos/seed/nikeairmax/600/600')");

        $this->db->query("INSERT INTO customers (name, email, phone, password, status) VALUES
            ('John Doe', 'john@example.com', '254700000001', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'active'),
            ('Jane Smith', 'jane@example.com', '254700000002', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'active')");

        $this->db->query("INSERT INTO shipping_methods (name, slug, description, is_default, status, sort_order) VALUES
            ('Standard Shipping', 'standard', 'Standard delivery (5-7 business days)', 1, 'active', 1),
            ('Express Shipping', 'express', 'Express delivery (1-2 business days)', 0, 'active', 2)");

        $this->db->query("INSERT INTO payment_methods (name, slug, description, type, status, sort_order) VALUES
            ('Cash on Delivery', 'cod', 'Pay when you receive', 'cod', 'active', 1),
            ('M-Pesa', 'mpesa', 'Pay via M-Pesa', 'mpesa', 'active', 2),
            ('Bank Transfer', 'bank', 'Pay via bank transfer', 'bank', 'active', 3)");
    }
}
