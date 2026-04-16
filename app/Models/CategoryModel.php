<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'category_name',
        'description',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false;
    protected $deletedField = false;

    // Validation rules
    protected $validationRules = [
        'category_name' => 'required|min_length[2]|max_length[50]|is_unique[categories.category_name,category_id,{category_id}]',
        'description' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'category_name' => [
            'required' => 'Category name is required.',
            'min_length' => 'Category name must be at least 2 characters.',
            'is_unique' => 'This category name already exists.'
        ],
        'description' => [
            'max_length' => 'Description cannot exceed 500 characters.'
        ]
    ];

    protected $skipValidation = false;

    // Custom methods
    
    /**
     * Get all categories with product count
     */
    public function getCategoriesWithProductCount()
    {
        $categories = $this->orderBy('category_name', 'ASC')->findAll();
        
        $productModel = new ProductModel();
        
        foreach ($categories as &$category) {
            $category['product_count'] = $productModel->where('category_id', $category['category_id'])->countAllResults();
        }
        
        return $categories;
    }

    /**
     * Get category by name
     */
    public function getCategoryByName($name)
    {
        return $this->where('category_name', $name)->first();
    }

    /**
     * Check if category has products
     */
    public function hasProducts($categoryId)
    {
        $productModel = new ProductModel();
        $count = $productModel->where('category_id', $categoryId)->countAllResults();
        
        return $count > 0;
    }

    /**
     * Get popular categories (with most products)
     */
    public function getPopularCategories($limit = 5)
    {
        $db = \Config\Database::connect();
        
        return $db->table('categories')
                  ->select('categories.category_id, categories.category_name, COUNT(products.product_id) as product_count')
                  ->join('products', 'products.category_id = categories.category_id', 'left')
                  ->groupBy('categories.category_id')
                  ->orderBy('product_count', 'DESC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Search categories
     */
    public function searchCategories($keyword)
    {
        return $this->like('category_name', $keyword)
                    ->orLike('description', $keyword)
                    ->orderBy('category_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get dropdown options for forms
     */
    public function getDropdownOptions()
    {
        $categories = $this->orderBy('category_name', 'ASC')->findAll();
        $options = [];
        
        foreach ($categories as $category) {
            $options[$category['category_id']] = $category['category_name'];
        }
        
        return $options;
    }

    /**
     * Delete category only if it has no products
     */
    public function deleteIfEmpty($categoryId)
    {
        if (!$this->hasProducts($categoryId)) {
            return $this->delete($categoryId);
        }
        
        return false;
    }
}