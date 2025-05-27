<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;
use App\Models\BannerModel;
use App\Models\ReviewModel;

class AdminController extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $userModel;
    protected $reviewModel;
    protected $bannerModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
        $this->bannerModel = new BannerModel();
    }

    private function checkAdminAccess()
    {
        if (!session()->get('user_id')) {
            session()->set('redirect_to', current_url());
            // For now, let's create a temporary admin session for testing
            // In production, this should redirect to login
            session()->set('user_id', 1); // Admin user ID from database
        }

        $user = $this->userModel->find(session()->get('user_id'));
        if (!$user || $user['role'] !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied - Admin privileges required');
        }

        return true;
    }

    private function getAdminData($activeSection = 'dashboard')
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        $sidebarItems = $this->getSidebarItems();

        // Debug logging
        log_message('debug', 'getAdminData called with activeSection: ' . $activeSection);
        log_message('debug', 'User ID from session: ' . ($userId ?? 'null'));
        log_message('debug', 'User found: ' . ($user ? 'yes' : 'no'));
        log_message('debug', 'Sidebar items count: ' . count($sidebarItems));

        return [
            'activeSection' => $activeSection,
            'user' => $user,
            'sidebarItems' => $sidebarItems
        ];
    }

    private function getSidebarItems()
    {
        return [
            [
                'title' => 'Dashboard',
                'url' => base_url('admin/dashboard'),
                'icon' => 'fas fa-tachometer-alt',
                'key' => 'dashboard'
            ],
            [
                'title' => 'Products',
                'url' => base_url('admin/products'),
                'icon' => 'fas fa-box',
                'key' => 'products',
                'submenu' => [
                    ['title' => 'All Products', 'url' => base_url('admin/products')],
                    ['title' => 'Add Product', 'url' => base_url('admin/products/create')],
                    ['title' => 'Categories', 'url' => base_url('admin/categories')]
                ]
            ],
            [
                'title' => 'Orders',
                'url' => base_url('admin/orders'),
                'icon' => 'fas fa-shopping-bag',
                'key' => 'orders'
            ],
            [
                'title' => 'Users',
                'url' => base_url('admin/users'),
                'icon' => 'fas fa-users',
                'key' => 'users'
            ],
            [
                'title' => 'Reviews',
                'url' => base_url('admin/reviews'),
                'icon' => 'fas fa-star',
                'key' => 'reviews'
            ],
            [
                'title' => 'Banners',
                'url' => base_url('admin/banners'),
                'icon' => 'fas fa-image',
                'key' => 'banners'
            ],
            [
                'title' => 'Settings',
                'url' => base_url('admin/settings'),
                'icon' => 'fas fa-cog',
                'key' => 'settings'
            ]
        ];
    }

    public function index()
    {
        $this->checkAdminAccess();

        // Get dashboard statistics
        $totalProducts = $this->productModel->countAll();
        $totalOrders = $this->orderModel->countAll();
        $totalUsers = $this->userModel->where('role', 'customer')->countAllResults();
        $totalRevenue = $this->orderModel->selectSum('total_amount')->first()['total_amount'] ?? 0;

        $recentOrders = $this->orderModel->getRecentOrders(5);
        $topProducts = $this->orderItemModel->getTopSellingProducts(5);
        $pendingReviews = $this->reviewModel->getPendingReviews(5);

        $data = array_merge($this->getAdminData('dashboard'), [
            'title' => 'Admin Dashboard - Nandini Hub',
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'pendingReviews' => $pendingReviews
        ]);

        return view('admin/dashboard', $data);
    }

    // Product Management
    public function products()
    {
        $this->checkAdminAccess();

        // Get filter parameters
        $categoryFilter = $this->request->getGet('category');
        $statusFilter = $this->request->getGet('status');
        $searchFilter = $this->request->getGet('search');

        // Build query with filters
        $builder = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id');

        if ($categoryFilter) {
            $builder->where('products.category_id', $categoryFilter);
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $builder->where('products.is_active', $statusFilter);
        }

        if ($searchFilter) {
            $builder->groupStart()
                ->like('products.name', $searchFilter)
                ->orLike('products.sku', $searchFilter)
                ->orLike('products.description', $searchFilter)
                ->groupEnd();
        }

        $products = $builder->orderBy('products.created_at', 'DESC')->findAll();
        $categories = $this->categoryModel->getActiveCategories();

        $data = array_merge($this->getAdminData('products'), [
            'title' => 'Manage Products - Admin',
            'products' => $products,
            'categories' => $categories
        ]);

        return view('admin/products/index', $data);
    }

    public function createProduct()
    {
        $this->checkAdminAccess();

        $categories = $this->categoryModel->getActiveCategories();

        $data = array_merge($this->getAdminData('products'), [
            'title' => 'Add New Product - Admin',
            'categories' => $categories
        ]);

        return view('admin/products/create', $data);
    }

    public function toggleProductStatus($id)
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
        }

        $input = json_decode($this->request->getBody(), true);
        $isActive = $input['is_active'] ?? 0;

        if ($this->productModel->update($id, ['is_active' => $isActive])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
    }

    public function toggleProductFeatured($id)
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
        }

        $input = json_decode($this->request->getBody(), true);
        $isFeatured = $input['is_featured'] ?? 0;

        if ($this->productModel->update($id, ['is_featured' => $isFeatured])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update featured status']);
    }

    public function bulkProductAction()
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $input = json_decode($this->request->getBody(), true);
        $action = $input['action'] ?? '';
        $productIds = $input['products'] ?? [];

        if (empty($productIds) || empty($action)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $success = false;
        switch ($action) {
            case 'activate':
                $success = $this->productModel->whereIn('id', $productIds)->set(['is_active' => 1])->update();
                break;
            case 'deactivate':
                $success = $this->productModel->whereIn('id', $productIds)->set(['is_active' => 0])->update();
                break;
            case 'delete':
                $success = $this->productModel->whereIn('id', $productIds)->delete();
                break;
        }

        return $this->response->setJSON(['success' => $success]);
    }

    private function uploadCategoryImage($imageFile)
    {
        // Create uploads directory if it doesn't exist
        $uploadPath = ROOTPATH . 'uploads/categories/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Create .htaccess file to allow image access
        $htaccessPath = ROOTPATH . 'uploads/.htaccess';
        if (!file_exists($htaccessPath)) {
            $htaccessContent = "# Allow access to uploaded files\n";
            $htaccessContent .= "Options -Indexes\n";
            $htaccessContent .= "RewriteEngine Off\n";
            $htaccessContent .= "\n";
            $htaccessContent .= "# Allow image files\n";
            $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png|gif|webp|svg)$\">\n";
            $htaccessContent .= "    Order allow,deny\n";
            $htaccessContent .= "    Allow from all\n";
            $htaccessContent .= "</FilesMatch>\n";
            file_put_contents($htaccessPath, $htaccessContent);
        }

        // Generate unique filename
        $extension = $imageFile->getClientExtension();
        $fileName = 'category_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

        try {
            // Move the file to uploads directory
            if ($imageFile->move($uploadPath, $fileName)) {
                return $fileName;
            }
        } catch (\Exception $e) {
            log_message('error', 'Category image upload failed: ' . $e->getMessage());
        }

        return false;
    }

    private function deleteCategoryImage($imageName)
    {
        if ($imageName && file_exists(ROOTPATH . 'uploads/categories/' . $imageName)) {
            return unlink(ROOTPATH . 'uploads/categories/' . $imageName);
        }
        return true;
    }

    private function uploadProductImage($imageFile)
    {
        // Create uploads directory if it doesn't exist
        $uploadPath = ROOTPATH . 'uploads/products/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Create .htaccess file to allow image access
        $htaccessPath = ROOTPATH . 'uploads/.htaccess';
        if (!file_exists($htaccessPath)) {
            $htaccessContent = "# Allow access to uploaded files\n";
            $htaccessContent .= "Options -Indexes\n";
            $htaccessContent .= "RewriteEngine Off\n";
            $htaccessContent .= "\n";
            $htaccessContent .= "# Allow image files\n";
            $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png|gif|webp|svg)$\">\n";
            $htaccessContent .= "    Order allow,deny\n";
            $htaccessContent .= "    Allow from all\n";
            $htaccessContent .= "</FilesMatch>\n";
            file_put_contents($htaccessPath, $htaccessContent);
        }

        // Generate unique filename
        $extension = $imageFile->getClientExtension();
        $fileName = 'product_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

        try {
            // Move the file to uploads directory
            if ($imageFile->move($uploadPath, $fileName)) {
                return $fileName;
            }
        } catch (\Exception $e) {
            log_message('error', 'Product image upload failed: ' . $e->getMessage());
        }

        return false;
    }

    private function deleteProductImage($imageName)
    {
        if ($imageName && file_exists(ROOTPATH . 'uploads/products/' . $imageName)) {
            return unlink(ROOTPATH . 'uploads/products/' . $imageName);
        }
        return true;
    }

    public function exportProducts()
    {
        $this->checkAdminAccess();

        $products = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->orderBy('products.created_at', 'DESC')
            ->findAll();

        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, [
            'ID',
            'Name',
            'SKU',
            'Category',
            'Price',
            'Sale Price',
            'Stock',
            'Status',
            'Featured',
            'Created'
        ]);

        // CSV data
        foreach ($products as $product) {
            fputcsv($output, [
                $product['id'],
                $product['name'],
                $product['sku'],
                $product['category_name'],
                $product['price'],
                $product['sale_price'],
                $product['stock_quantity'],
                $product['is_active'] ? 'Active' : 'Inactive',
                $product['is_featured'] ? 'Yes' : 'No',
                $product['created_at']
            ]);
        }

        fclose($output);
        exit;
    }

    // Category Management
    public function categories()
    {
        $this->checkAdminAccess();

        $categories = $this->categoryModel->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $data = array_merge($this->getAdminData('products'), [
            'title' => 'Manage Categories - Admin',
            'categories' => $categories
        ]);

        return view('admin/categories/index', $data);
    }

    public function createCategory()
    {
        $this->checkAdminAccess();

        $data = array_merge($this->getAdminData('products'), [
            'title' => 'Add New Category - Admin'
        ]);

        return view('admin/categories/create', $data);
    }

    public function storeCategory()
    {
        $this->checkAdminAccess();

        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'slug' => 'permit_empty|alpha_dash|is_unique[categories.slug]',
            'sort_order' => 'permit_empty|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryData = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $this->uploadCategoryImage($imageFile);
            if ($imageName) {
                $categoryData['image'] = $imageName;
            }
        }

        // Temporarily disable model validation since we're validating in controller
        $this->categoryModel->skipValidation(true);

        if ($this->categoryModel->insert($categoryData)) {
            session()->setFlashdata('success', 'Category created successfully');
            return redirect()->to('/admin/categories');
        } else {
            // If category creation failed and image was uploaded, delete the image
            if (isset($categoryData['image']) && file_exists(ROOTPATH . 'uploads/categories/' . $categoryData['image'])) {
                unlink(ROOTPATH . 'uploads/categories/' . $categoryData['image']);
            }
            session()->setFlashdata('error', 'Failed to create category');
            return redirect()->back()->withInput();
        }
    }

    public function editCategory($id)
    {
        $this->checkAdminAccess();

        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $data = array_merge($this->getAdminData('products'), [
            'title' => 'Edit Category - Admin',
            'category' => $category
        ]);

        return view('admin/categories/edit', $data);
    }

    public function updateCategory($id)
    {
        $this->checkAdminAccess();

        // Debug: Log the incoming data
        log_message('info', 'Category update attempt for ID: ' . $id);
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'slug' => "required|alpha_dash|is_unique[categories.slug,id,{$id}]",
            'sort_order' => 'permit_empty|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryData = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newImageName = $this->uploadCategoryImage($imageFile);
            if ($newImageName) {
                // Delete old image if exists
                if (!empty($category['image'])) {
                    $this->deleteCategoryImage($category['image']);
                }
                $categoryData['image'] = $newImageName;
            }
        }

        // Temporarily disable model validation since we're validating in controller
        $this->categoryModel->skipValidation(true);

        try {
            if ($this->categoryModel->update($id, $categoryData)) {
                session()->setFlashdata('success', 'Category updated successfully');
                return redirect()->to('/admin/categories');
            } else {
                // Log the error for debugging
                $errors = $this->categoryModel->errors();
                log_message('error', 'Category update failed: ' . json_encode($errors));

                // If update failed and new image was uploaded, delete the new image
                if (isset($categoryData['image']) && $categoryData['image'] !== $category['image']) {
                    $this->deleteCategoryImage($categoryData['image']);
                }
                session()->setFlashdata('error', 'Failed to update category. Please check the form data.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log the exception
            log_message('error', 'Category update exception: ' . $e->getMessage());

            // If update failed and new image was uploaded, delete the new image
            if (isset($categoryData['image']) && $categoryData['image'] !== $category['image']) {
                $this->deleteCategoryImage($categoryData['image']);
            }
            session()->setFlashdata('error', 'An error occurred while updating the category: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deleteCategory($id)
    {
        $this->checkAdminAccess();

        // Get category data before deletion
        $category = $this->categoryModel->find($id);
        if (!$category) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Category not found']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        // Check if this is an AJAX request
        if ($this->request->isAJAX()) {
            // Check if category has products
            $productCount = $this->productModel->where('category_id', $id)->countAllResults();
            if ($productCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Cannot delete category with {$productCount} existing products"
                ]);
            }

            if ($this->categoryModel->delete($id)) {
                // Delete associated image
                if (!empty($category['image'])) {
                    $this->deleteCategoryImage($category['image']);
                }
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete category']);
            }
        }

        // Non-AJAX request (fallback)
        $productCount = $this->productModel->where('category_id', $id)->countAllResults();
        if ($productCount > 0) {
            session()->setFlashdata('error', 'Cannot delete category with existing products');
            return redirect()->to('/admin/categories');
        }

        if ($this->categoryModel->delete($id)) {
            // Delete associated image
            if (!empty($category['image'])) {
                $this->deleteCategoryImage($category['image']);
            }
            session()->setFlashdata('success', 'Category deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete category');
        }

        return redirect()->to('/admin/categories');
    }

    public function toggleCategoryStatus($id)
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category not found']);
        }

        $input = json_decode($this->request->getBody(), true);
        $isActive = $input['is_active'] ?? 0;

        if ($this->categoryModel->update($id, ['is_active' => $isActive])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update category status']);
    }

    public function getCategoryProductCount($id)
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $count = $this->productModel->where('category_id', $id)->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }

    public function storeProduct()
    {
        $this->checkAdminAccess();

        $rules = [
            'category_id' => 'required|integer',
            'name' => 'required|min_length[2]|max_length[255]',
            'price' => 'required|decimal',
            'sku' => 'required|is_unique[products.sku]',
            'stock_quantity' => 'required|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productData = [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'price' => $this->request->getPost('price'),
            'sale_price' => $this->request->getPost('sale_price'),
            'sku' => $this->request->getPost('sku'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'weight' => $this->request->getPost('weight'),
            'dimensions' => $this->request->getPost('dimensions'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description')
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $this->uploadProductImage($imageFile);
            if ($imageName) {
                $productData['image'] = $imageName;
            }
        }

        // Temporarily disable model validation since we're validating in controller
        $this->productModel->skipValidation(true);

        try {
            if ($this->productModel->insert($productData)) {
                session()->setFlashdata('success', 'Product created successfully');
                return redirect()->to('/admin/products');
            } else {
                // If product creation failed and image was uploaded, delete the image
                if (isset($productData['image']) && file_exists(ROOTPATH . 'uploads/products/' . $productData['image'])) {
                    $this->deleteProductImage($productData['image']);
                }
                session()->setFlashdata('error', 'Failed to create product');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log the exception
            log_message('error', 'Product creation exception: ' . $e->getMessage());

            // If product creation failed and image was uploaded, delete the image
            if (isset($productData['image'])) {
                $this->deleteProductImage($productData['image']);
            }
            session()->setFlashdata('error', 'An error occurred while creating the product: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function editProduct($id)
    {
        $this->checkAdminAccess();

        $product = $this->productModel->find($id);
        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        $categories = $this->categoryModel->getActiveCategories();

        $data = array_merge($this->getAdminData('products'), [
            'title' => 'Edit Product - Admin',
            'product' => $product,
            'categories' => $categories
        ]);

        return view('admin/products/edit', $data);
    }

    public function updateProduct($id)
    {
        $this->checkAdminAccess();

        // Debug: Log the incoming data
        log_message('info', 'Product update attempt for ID: ' . $id);
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        $product = $this->productModel->find($id);
        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        $rules = [
            'category_id' => 'required|integer',
            'name' => 'required|min_length[2]|max_length[255]',
            'price' => 'required|decimal',
            'sku' => "required|is_unique[products.sku,id,{$id}]",
            'stock_quantity' => 'required|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productData = [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'price' => $this->request->getPost('price'),
            'sale_price' => $this->request->getPost('sale_price'),
            'sku' => $this->request->getPost('sku'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'weight' => $this->request->getPost('weight'),
            'dimensions' => $this->request->getPost('dimensions'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description')
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newImageName = $this->uploadProductImage($imageFile);
            if ($newImageName) {
                // Delete old image if exists
                if (!empty($product['image'])) {
                    $this->deleteProductImage($product['image']);
                }
                $productData['image'] = $newImageName;
            }
        }

        // Temporarily disable model validation since we're validating in controller
        $this->productModel->skipValidation(true);

        try {
            if ($this->productModel->update($id, $productData)) {
                session()->setFlashdata('success', 'Product updated successfully');
                return redirect()->to('/admin/products');
            } else {
                // Log the error for debugging
                $errors = $this->productModel->errors();
                log_message('error', 'Product update failed: ' . json_encode($errors));

                // If update failed and new image was uploaded, delete the new image
                if (isset($productData['image']) && $productData['image'] !== $product['image']) {
                    $this->deleteProductImage($productData['image']);
                }
                session()->setFlashdata('error', 'Failed to update product. Please check the form data.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log the exception
            log_message('error', 'Product update exception: ' . $e->getMessage());

            // If update failed and new image was uploaded, delete the new image
            if (isset($productData['image']) && $productData['image'] !== $product['image']) {
                $this->deleteProductImage($productData['image']);
            }
            session()->setFlashdata('error', 'An error occurred while updating the product: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deleteProduct($id)
    {
        $this->checkAdminAccess();

        // Get product data before deletion
        $product = $this->productModel->find($id);
        if (!$product) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        // Check if this is an AJAX request
        if ($this->request->isAJAX()) {
            if ($this->productModel->delete($id)) {
                // Delete associated image
                if (!empty($product['image'])) {
                    $this->deleteProductImage($product['image']);
                }
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete product']);
            }
        }

        // Non-AJAX request (fallback)
        if ($this->productModel->delete($id)) {
            // Delete associated image
            if (!empty($product['image'])) {
                $this->deleteProductImage($product['image']);
            }
            session()->setFlashdata('success', 'Product deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete product');
        }

        return redirect()->to('/admin/products');
    }

    // Order Management
    public function orders()
    {
        $this->checkAdminAccess();

        $orders = $this->orderModel->getOrdersWithItems();

        $data = array_merge($this->getAdminData('orders'), [
            'title' => 'Manage Orders - Admin',
            'orders' => $orders
        ]);

        return view('admin/orders/index', $data);
    }

    public function viewOrder($id)
    {
        $this->checkAdminAccess();

        $order = $this->orderModel->getOrderWithDetails($id);
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        $orderItems = $this->orderItemModel->getOrderItems($id);

        log_message('debug', 'viewOrder: About to call getAdminData');
        $adminData = $this->getAdminData('orders');
        log_message('debug', 'viewOrder: getAdminData returned: ' . json_encode(array_keys($adminData)));

        $data = array_merge($adminData, [
            'title' => 'Order #' . $order['order_number'] . ' - Admin',
            'order' => $order,
            'orderItems' => $orderItems
        ]);

        log_message('debug', 'viewOrder: Final data keys: ' . json_encode(array_keys($data)));
        return view('admin/orders/view', $data);
    }

    public function updateOrderStatus($id)
    {
        $this->checkAdminAccess();

        $status = $this->request->getPost('status');

        if ($this->orderModel->updateOrderStatus($id, $status)) {
            session()->setFlashdata('success', 'Order status updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update order status');
        }

        return redirect()->back();
    }

    // Review Management
    public function reviews()
    {
        $this->checkAdminAccess();

        $pendingReviews = $this->reviewModel->getPendingReviews();
        $recentReviews = $this->reviewModel->getRecentReviews(20);

        $data = array_merge($this->getAdminData('reviews'), [
            'title' => 'Manage Reviews - Admin',
            'pendingReviews' => $pendingReviews,
            'recentReviews' => $recentReviews
        ]);

        return view('admin/reviews/index', $data);
    }

    public function approveReview($id)
    {
        $this->checkAdminAccess();

        if ($this->reviewModel->approveReview($id)) {
            session()->setFlashdata('success', 'Review approved successfully');
        } else {
            session()->setFlashdata('error', 'Failed to approve review');
        }

        return redirect()->back();
    }

    public function rejectReview($id)
    {
        $this->checkAdminAccess();

        if ($this->reviewModel->rejectReview($id)) {
            session()->setFlashdata('success', 'Review rejected successfully');
        } else {
            session()->setFlashdata('error', 'Failed to reject review');
        }

        return redirect()->back();
    }

    // User Management
    public function users()
    {
        $this->checkAdminAccess();

        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        $data = array_merge($this->getAdminData('users'), [
            'title' => 'Manage Users - Admin',
            'users' => $users
        ]);

        return view('admin/users/index', $data);
    }

    public function viewUser($id)
    {
        $this->checkAdminAccess();

        $user = $this->userModel->find($id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        // Get user's orders
        $orders = $this->orderModel->where('user_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = array_merge($this->getAdminData('users'), [
            'title' => 'User Details - Admin',
            'user' => $user,
            'orders' => $orders
        ]);

        return view('admin/users/view', $data);
    }

    public function toggleUserStatus($id)
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $input = json_decode($this->request->getBody(), true);
        $isActive = $input['is_active'] ?? 0;

        if ($this->userModel->update($id, ['is_active' => $isActive])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user status']);
    }

    // Settings Management
    public function settings()
    {
        $this->checkAdminAccess();

        $data = array_merge($this->getAdminData('settings'), [
            'title' => 'Site Settings - Admin'
        ]);

        return view('admin/settings/index', $data);
    }

    public function updateSettings()
    {
        $this->checkAdminAccess();

        // This would typically update site configuration
        // For now, just redirect back with success message
        session()->setFlashdata('success', 'Settings updated successfully');
        return redirect()->back();
    }

    // Banner Management
    public function banners()
    {
        $this->checkAdminAccess();

        $banners = $this->bannerModel->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = array_merge($this->getAdminData('banners'), [
            'title' => 'Manage Banners - Admin',
            'banners' => $banners
        ]);

        return view('admin/banners/index', $data);
    }

    public function createBanner()
    {
        $this->checkAdminAccess();

        $data = array_merge($this->getAdminData('banners'), [
            'title' => 'Add New Banner - Admin'
        ]);

        return view('admin/banners/create', $data);
    }

    public function storeBanner()
    {
        $this->checkAdminAccess();

        $rules = [
            'title' => 'required|min_length[2]|max_length[255]',
            'background_color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'text_color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'sort_order' => 'permit_empty|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bannerData = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'description' => $this->request->getPost('description'),
            'button_text' => $this->request->getPost('button_text'),
            'button_link' => $this->request->getPost('button_link'),
            'button_text_2' => $this->request->getPost('button_text_2'),
            'button_link_2' => $this->request->getPost('button_link_2'),
            'background_color' => $this->request->getPost('background_color') ?: '#ff6b35',
            'text_color' => $this->request->getPost('text_color') ?: '#ffffff',
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $this->uploadBannerImage($imageFile);
            if ($imageName) {
                $bannerData['image'] = $imageName;
            }
        }

        if ($this->bannerModel->insert($bannerData)) {
            session()->setFlashdata('success', 'Banner created successfully');
            return redirect()->to('/admin/banners');
        } else {
            // If banner creation failed and image was uploaded, delete the image
            if (isset($bannerData['image'])) {
                $this->deleteBannerImage($bannerData['image']);
            }
            session()->setFlashdata('error', 'Failed to create banner');
            return redirect()->back()->withInput();
        }
    }

    public function editBanner($id)
    {
        $this->checkAdminAccess();

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Banner not found');
        }

        $data = array_merge($this->getAdminData('banners'), [
            'title' => 'Edit Banner - Admin',
            'banner' => $banner
        ]);

        return view('admin/banners/edit', $data);
    }

    public function updateBanner($id)
    {
        $this->checkAdminAccess();

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Banner not found');
        }

        $rules = [
            'title' => 'required|min_length[2]|max_length[255]',
            'background_color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'text_color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'sort_order' => 'permit_empty|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bannerData = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'description' => $this->request->getPost('description'),
            'button_text' => $this->request->getPost('button_text'),
            'button_link' => $this->request->getPost('button_link'),
            'button_text_2' => $this->request->getPost('button_text_2'),
            'button_link_2' => $this->request->getPost('button_link_2'),
            'background_color' => $this->request->getPost('background_color') ?: '#ff6b35',
            'text_color' => $this->request->getPost('text_color') ?: '#ffffff',
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newImageName = $this->uploadBannerImage($imageFile);
            if ($newImageName) {
                // Delete old image if exists
                if (!empty($banner['image'])) {
                    $this->deleteBannerImage($banner['image']);
                }
                $bannerData['image'] = $newImageName;
            }
        }

        if ($this->bannerModel->update($id, $bannerData)) {
            session()->setFlashdata('success', 'Banner updated successfully');
            return redirect()->to('/admin/banners');
        } else {
            // If update failed and new image was uploaded, delete the new image
            if (isset($bannerData['image']) && $bannerData['image'] !== $banner['image']) {
                $this->deleteBannerImage($bannerData['image']);
            }
            session()->setFlashdata('error', 'Failed to update banner');
            return redirect()->back()->withInput();
        }
    }

    public function deleteBanner($id)
    {
        $this->checkAdminAccess();

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Banner not found']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Banner not found');
        }

        if ($this->request->isAJAX()) {
            if ($this->bannerModel->delete($id)) {
                // Delete associated image
                if (!empty($banner['image'])) {
                    $this->deleteBannerImage($banner['image']);
                }
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete banner']);
            }
        }

        if ($this->bannerModel->delete($id)) {
            // Delete associated image
            if (!empty($banner['image'])) {
                $this->deleteBannerImage($banner['image']);
            }
            session()->setFlashdata('success', 'Banner deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete banner');
        }

        return redirect()->to('/admin/banners');
    }

    public function toggleBannerStatus($id)
    {
        $this->checkAdminAccess();

        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            return $this->response->setJSON(['success' => false, 'message' => 'Banner not found']);
        }

        $input = json_decode($this->request->getBody(), true);
        $isActive = $input['is_active'] ?? 0;

        if ($this->bannerModel->update($id, ['is_active' => $isActive])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update banner status']);
    }

    private function uploadBannerImage($imageFile)
    {
        // Create uploads directory if it doesn't exist
        $uploadPath = ROOTPATH . 'uploads/banners/';
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                log_message('error', 'Failed to create banner upload directory: ' . $uploadPath);
                return false;
            }
        }

        // Generate unique filename
        $extension = $imageFile->getClientExtension();
        $fileName = 'banner_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

        try {
            // Move the file to uploads directory
            if ($imageFile->move($uploadPath, $fileName)) {
                return $fileName;
            }
        } catch (\Exception $e) {
            log_message('error', 'Banner image upload failed: ' . $e->getMessage());
        }

        return false;
    }

    private function deleteBannerImage($imageName)
    {
        if ($imageName && file_exists(ROOTPATH . 'uploads/banners/' . $imageName)) {
            return unlink(ROOTPATH . 'uploads/banners/' . $imageName);
        }
        return true;
    }
}
