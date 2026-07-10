<?php
class HomeController extends Controller {
    public function index() {
        $product = new Product();
        $category = new Category();

        $data = [
            'featuredProducts' => $product->getFeatured(8),
            'newArrivals' => $product->getNewArrivals(8),
            'bestSellers' => $product->getBestSellers(8),
            'categories' => $category->getTree()
        ];
        $this->render('front/home', $data);
    }

    public function shop() {
        $product = new Product();
        $category = new Category();
        $brand = new Brand();

        $page = $this->get('page', 1);
        $filters = [
            'category_id' => $this->get('category'),
            'brand_id' => $this->get('brand'),
            'min_price' => $this->get('min_price'),
            'max_price' => $this->get('max_price'),
            'sort' => $this->get('sort', 'newest'),
            'rating' => $this->get('rating'),
            'q' => $this->get('q')
        ];

        $results = $product->search($filters['q'], $filters, $page, 12);

        $data = [
            'products' => $results['data'],
            'total' => $results['total'],
            'page' => $results['page'],
            'totalPages' => $results['totalPages'],
            'categories' => $category->getTree(),
            'brands' => $brand->getWithProductCount(),
            'filters' => $filters
        ];
        $this->render('front/shop', $data);
    }

    public function product($slug) {
        $product = new Product();
        $prod = $product->findBy('slug', $slug, 1);
        if (!$prod) {
            $this->redirect('shop');
        }

        $product->update($prod['id'], ['views' => $prod['views'] + 1]);

        $inWishlist = false;
        if (is_customer()) {
            $w = $this->db->query("SELECT id FROM wishlist WHERE customer_id = {$_SESSION['customer_id']} AND product_id = {$prod['id']}");
            $inWishlist = (bool)$w->fetch_assoc();
        }

        $data = [
            'product' => $product->getWithRelations($prod['id']),
            'images' => $product->getImages($prod['id']),
            'variants' => $product->getVariants($prod['id']),
            'reviews' => $product->getReviews($prod['id']),
            'rating' => $product->getAverageRating($prod['id']),
            'related' => $product->getRelated($prod['id'], $prod['category_id']),
            'inWishlist' => $inWishlist
        ];
        $this->render('front/product', $data);
    }

    public function category($slug) {
        $category = new Category();
        $cat = $category->findBy('slug', $slug, 1);
        if (!$cat) {
            $this->redirect('shop');
        }

        $product = new Product();
        $page = $this->get('page', 1);
        $catIds = $category->getCategoryIdsRecursive($cat['id']);
        $filters = ['category_id' => $cat['id'], 'sort' => $this->get('sort', 'newest')];
        $results = $product->search('', $filters, $page, 12);

        $data = [
            'category' => $cat,
            'products' => $results['data'],
            'page' => $results['page'],
            'totalPages' => $results['totalPages'],
            'total' => $results['total'],
            'breadcrumbs' => $category->getBreadcrumbs($cat['id']),
            'subcategories' => $category->getFlatList($cat['id'])
        ];
        $this->render('front/category', $data);
    }

    public function login() {
        if (is_customer()) $this->redirect('account');
        $this->render('front/auth/login');
    }

    public function register() {
        if (is_customer()) $this->redirect('account');
        $this->render('front/auth/register');
    }

    public function logout() {
        $auth = new Auth();
        $auth->customerLogout();
        $this->redirect('');
    }

    public function account() {
        customer_guard();
        $customer = new Customer();
        $data = ['customer' => $customer->getWithOrders($_SESSION['customer_id'])];
        $this->render('front/account', $data);
    }

    public function wishlist() {
        customer_guard();
        $this->render('front/wishlist');
    }

    public function compare() {
        $this->render('front/compare');
    }

    public function page($slug) {
        $data = ['slug' => $slug];
        $this->render('front/page', $data);
    }

    public function contact() {
        $this->render('front/contact');
    }
}
