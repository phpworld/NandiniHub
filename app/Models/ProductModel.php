<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'weight',
        'dimensions',
        'image',
        'gallery',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'category_id' => 'required|integer',
        'name'        => 'required|min_length[2]|max_length[255]',
        'slug'        => 'required|alpha_dash|is_unique[products.slug,id,{id}]',
        'price'       => 'required|decimal',
        'sku'         => 'required|is_unique[products.sku,id,{id}]',
    ];

    protected $validationMessages = [
        'slug' => [
            'is_unique' => 'This product slug already exists.'
        ],
        'sku' => [
            'is_unique' => 'This SKU already exists.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $beforeUpdate   = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }
        return $data;
    }

    public function getActiveProducts($limit = null)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1)
                       ->orderBy('products.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getFeaturedProducts($limit = 8)
    {
        return $this->select('products.*, categories.name as category_name')
                   ->join('categories', 'categories.id = products.category_id')
                   ->where('products.is_featured', 1)
                   ->where('products.is_active', 1)
                   ->where('categories.is_active', 1)
                   ->limit($limit)
                   ->findAll();
    }

    public function getProductBySlug($slug)
    {
        return $this->select('products.*, categories.name as category_name, categories.slug as category_slug')
                   ->join('categories', 'categories.id = products.category_id')
                   ->where('products.slug', $slug)
                   ->where('products.is_active', 1)
                   ->where('categories.is_active', 1)
                   ->first();
    }

    public function getProductsByCategory($categoryId, $limit = null)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->where('products.category_id', $categoryId)
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1)
                       ->orderBy('products.name', 'ASC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function searchProducts($keyword, $limit = null)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->groupStart()
                           ->like('products.name', $keyword)
                           ->orLike('products.description', $keyword)
                           ->orLike('products.short_description', $keyword)
                           ->orLike('categories.name', $keyword)
                       ->groupEnd()
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1)
                       ->orderBy('products.name', 'ASC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getDiscountedPrice($product)
    {
        return $product['sale_price'] ? $product['sale_price'] : $product['price'];
    }

    public function hasDiscount($product)
    {
        return !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
    }

    public function getDiscountPercentage($product)
    {
        if (!$this->hasDiscount($product)) {
            return 0;
        }

        return round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
    }

    public function getProductsWithFilters($filters = [])
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1);

        // Category filter
        if (!empty($filters['category_id'])) {
            $builder->where('products.category_id', $filters['category_id']);
        }

        // Price range filter
        if (!empty($filters['min_price'])) {
            $builder->where('(CASE WHEN products.sale_price IS NOT NULL THEN products.sale_price ELSE products.price END) >=', $filters['min_price'], false);
        }

        if (!empty($filters['max_price'])) {
            $builder->where('(CASE WHEN products.sale_price IS NOT NULL THEN products.sale_price ELSE products.price END) <=', $filters['max_price'], false);
        }

        // Search keyword
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('products.name', $filters['search'])
                   ->orLike('products.description', $filters['search'])
                   ->orLike('products.short_description', $filters['search'])
                   ->orLike('categories.name', $filters['search'])
                   ->groupEnd();
        }

        // Sort options
        $sortBy = $filters['sort'] ?? 'newest';
        switch ($sortBy) {
            case 'price_low':
                // Use raw SQL for complex expressions
                $builder->orderBy('CASE WHEN products.sale_price IS NOT NULL THEN products.sale_price ELSE products.price END', 'ASC', false);
                break;
            case 'price_high':
                // Use raw SQL for complex expressions
                $builder->orderBy('CASE WHEN products.sale_price IS NOT NULL THEN products.sale_price ELSE products.price END', 'DESC', false);
                break;
            case 'name':
                $builder->orderBy('products.name', 'ASC');
                break;
            case 'featured':
                $builder->orderBy('products.is_featured', 'DESC')
                       ->orderBy('products.created_at', 'DESC');
                break;
            default: // newest
                $builder->orderBy('products.created_at', 'DESC');
                break;
        }

        // Limit
        if (!empty($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        return $builder->findAll();
    }

    public function getPriceRange()
    {
        $result = $this->select('
            MIN(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END) as min_price,
            MAX(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END) as max_price
        ', false)
        ->where('is_active', 1)
        ->first();

        return [
            'min' => floor($result['min_price'] ?? 0),
            'max' => ceil($result['max_price'] ?? 1000)
        ];
    }
}
