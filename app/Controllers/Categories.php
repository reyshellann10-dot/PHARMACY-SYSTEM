<?php
namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class Categories extends BaseController
{
    protected $categoryModel;
    protected $productModel;
    protected $session;
    
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
        $this->session = \Config\Services::session();
        
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Only admin and pharmacist can manage categories
        $role = $this->session->get('role');
        if (!in_array($role, ['admin', 'pharmacist'])) {
            return redirect()->to('/dashboard')
                ->with('error', 'You don\'t have permission to access this page.');
        }
    }
    
    // Display categories list
    public function index()
    {
        $data['title'] = 'Category Management';
        $data['categories'] = $this->categoryModel
            ->orderBy('category_name', 'ASC')
            ->findAll();
        
        // Get product count for each category
        foreach ($data['categories'] as &$category) {
            $category['product_count'] = $this->productModel
                ->where('category_id', $category['category_id'])
                ->countAllResults();
        }
        
        return view('categories/index', $data);
    }
    
    // Show create category form
    public function create()
    {
        $data['title'] = 'Add New Category';
        
        return view('categories/create', $data);
    }
    
    // Store new category
    public function store()
    {
        $rules = [
            'category_name' => 'required|min_length[2]|max_length[50]|is_unique[categories.category_name]',
            'description' => 'permit_empty|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'category_name' => ucwords($this->request->getPost('category_name')),
            'description' => $this->request->getPost('description'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/categories')
                ->with('success', 'Category added successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add category.');
        }
    }
    
    // Show edit category form
    public function edit($id = null)
    {
        $data['title'] = 'Edit Category';
        $data['category'] = $this->categoryModel->find($id);
        
        if (!$data['category']) {
            return redirect()->to('/categories')
                ->with('error', 'Category not found.');
        }
        
        return view('categories/edit', $data);
    }
    
    // Update category
    public function update($id = null)
    {
        $rules = [
            'category_name' => 'required|min_length[2]|max_length[50]|is_unique[categories.category_name,category_id,' . $id . ']',
            'description' => 'permit_empty|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'category_name' => ucwords($this->request->getPost('category_name')),
            'description' => $this->request->getPost('description')
        ];
        
        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/categories')
                ->with('success', 'Category updated successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update category.');
        }
    }
    
    // Delete category
    public function delete($id = null)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/categories')
                ->with('error', 'Category not found.');
        }
        
        // Check if category has products
        $productCount = $this->productModel
            ->where('category_id', $id)
            ->countAllResults();
        
        if ($productCount > 0) {
            return redirect()->to('/categories')
                ->with('error', 'Cannot delete category with ' . $productCount . ' product(s). Move or delete products first.');
        }
        
        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/categories')
                ->with('success', 'Category deleted successfully!');
        } else {
            return redirect()->to('/categories')
                ->with('error', 'Failed to delete category.');
        }
    }
    
    // View products by category
    public function products($id = null)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/categories')
                ->with('error', 'Category not found.');
        }
        
        $data['title'] = 'Products in ' . $category['category_name'];
        $data['category'] = $category;
        $data['products'] = $this->productModel
            ->where('category_id', $id)
            ->orderBy('generic_name', 'ASC')
            ->findAll();
        
        return view('categories/products', $data);
    }
    
    // Get categories for dropdown (AJAX)
    public function getCategories()
    {
        $categories = $this->categoryModel
            ->orderBy('category_name', 'ASC')
            ->findAll();
        
        return $this->response->setJSON($categories);
    }
}