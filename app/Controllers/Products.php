<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    private $productModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            exit('Access denied');
        }
        $this->productModel = new ProductModel();
    }
    
    
    // List all products
    public function index()
    {
        $data['title'] = 'Products';
        $data['products'] = $this->productModel->orderBy('product_id', 'DESC')->findAll();
        return view('products/index', $data);
    }
    
    // Show create form
    public function create()
    {
        $data['title'] = 'Add Product';
        return view('products/create', $data);
    }
    
    // Save new product
    public function store()
    {
        $rules = [
            'product_code' => 'required|is_unique[products.product_code]',
            'generic_name' => 'required',
            'unit_price' => 'required|numeric|greater_than[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'expiry_date' => 'required|valid_date'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->productModel->save([
            'product_code' => $this->request->getPost('product_code'),
            'generic_name' => $this->request->getPost('generic_name'),
            'brand_name' => $this->request->getPost('brand_name'),
            'unit_price' => $this->request->getPost('unit_price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'expiry_date' => $this->request->getPost('expiry_date'),
            'manufacturer' => $this->request->getPost('manufacturer')
        ]);
        
        return redirect()->to('/products')->with('success', 'Product added successfully');
    }
    
    // Show edit form
    public function edit($id = null)
    {
        // Find the product by ID
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }
        
        $data['title'] = 'Edit Product';
        $data['product'] = $product;
        
        return view('products/edit', $data);
    }
    // Update product
    public function update($id = null)
    {
        $rules = [
            'product_code'   => "required|is_unique[products.product_code,product_id,$id]",
            'generic_name'   => 'required',
            'unit_price'     => 'required|numeric|greater_than[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'expiry_date'    => 'required|valid_date'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->productModel->update($id, [
            'product_code'   => $this->request->getPost('product_code'),
            'generic_name'   => $this->request->getPost('generic_name'),
            'brand_name'     => $this->request->getPost('brand_name'),
            'unit_price'     => $this->request->getPost('unit_price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'expiry_date'    => $this->request->getPost('expiry_date'),
            'manufacturer'   => $this->request->getPost('manufacturer')
        ]);
        
        return redirect()->to('/products')->with('success', 'Product updated successfully');
    }
    
    // Delete product
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }
    
        $db = \Config\Database::connect();
        $db->transStart();
    
        // Delete related sale items first
        $saleItemModel = new \App\Models\SaleItemModel();
        $saleItemModel->where('product_id', $id)->delete();
    
        // Now delete product
        $this->productModel->delete($id);
    
        $db->transComplete();
    
        if ($db->transStatus() === false) {
            return redirect()->to('/products')->with('error', 'Failed to delete product');
        }
    
        return redirect()->to('/products')->with('success', 'Product and its sales records deleted');
    }
    
}
