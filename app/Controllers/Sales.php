<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\CustomerModel;

class Sales extends BaseController
{
    protected $productModel;
    protected $saleModel;
    protected $saleItemModel;
    protected $customerModel;

    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            exit('Access denied');
        }
        $this->productModel = new ProductModel();
        $this->saleModel = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->customerModel = new CustomerModel();
    }

    // -------------------------------------------------------------
    //  Sales List
    // -------------------------------------------------------------
    public function index()
    {
        $data['title'] = 'Sales Transactions';
        $data['sales'] = $this->saleModel->orderBy('sale_date', 'DESC')->findAll();
        return view('sales/index', $data);
    }

    // -------------------------------------------------------------
    //  View Single Sale
    // -------------------------------------------------------------
    public function view($id)
    {
        $sale = $this->saleModel->find($id);
        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale not found');
        }
        $data['title'] = 'Sale Details';
        $data['sale'] = $sale;
        return view('sales/view', $data);
    }

    // -------------------------------------------------------------
    //  Get Items for a Sale (AJAX)
    // -------------------------------------------------------------
    public function getItems($saleId)
    {
        $items = $this->saleItemModel->select('sale_items.*, products.generic_name, products.brand_name')
                                     ->join('products', 'products.product_id = sale_items.product_id')
                                     ->where('sale_id', $saleId)
                                     ->findAll();
        return $this->response->setJSON($items);
    }

    // -------------------------------------------------------------
    //  Point of Sale (POS) Page
    // -------------------------------------------------------------
    public function pos()
    {
        $cart = session()->get('cart') ?? [];
        $products = $this->productModel->where('stock_quantity >', 0)
                                       ->where('expiry_date >', date('Y-m-d'))
                                       ->orderBy('generic_name', 'ASC')
                                       ->findAll();
        $customers = $this->customerModel->orderBy('first_name', 'ASC')->findAll();

        $data = [
            'title'     => 'Point of Sale',
            'cart'      => $cart,
            'products'  => $products,
            'customers' => $customers,
            'cartTotal' => array_sum(array_column($cart, 'subtotal'))
        ];
        return view('sales/pos', $data);
    }

    // -------------------------------------------------------------
    //  Add to Cart (AJAX)
    // -------------------------------------------------------------
    public function addToCart()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;

        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
        }

        if ($product['stock_quantity'] < $quantity) {
            return $this->response->setJSON(['success' => false, 'message' => 'Insufficient stock']);
        }

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;
            if ($product['stock_quantity'] < $newQty) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cannot add more than available stock']);
            }
            $cart[$productId]['quantity'] = $newQty;
            $cart[$productId]['subtotal'] = $newQty * $product['unit_price'];
        } else {
            $cart[$productId] = [
                'product_id'   => $productId,
                'product_name' => $product['generic_name'] . ($product['brand_name'] ? ' (' . $product['brand_name'] . ')' : ''),
                'unit_price'   => $product['unit_price'],
                'quantity'     => $quantity,
                'subtotal'     => $quantity * $product['unit_price']
            ];
        }

        session()->set('cart', $cart);
        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------------
    //  Remove from Cart (AJAX)
    // -------------------------------------------------------------
    public function removeFromCart()
    {
        $productId = $this->request->getPost('product_id');
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------------
    //  Update Cart Quantity (AJAX)
    // -------------------------------------------------------------
    public function updateCart()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');
        if ($quantity <= 0) {
            return $this->removeFromCart();
        }

        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
        }

        if ($product['stock_quantity'] < $quantity) {
            return $this->response->setJSON(['success' => false, 'message' => 'Insufficient stock']);
        }

        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['subtotal'] = $quantity * $product['unit_price'];
            session()->set('cart', $cart);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------------
    //  Clear Cart (AJAX)
    // -------------------------------------------------------------
    public function clearCart()
    {
        session()->remove('cart');
        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------------
    //  Checkout (Complete Sale) – FIXED VERSION
    // -------------------------------------------------------------
    public function checkout()
    {
        // Enable error reporting for debugging (remove in production)
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $cart = session()->get('cart');
        if (empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cart is empty']);
        }

        $total = array_sum(array_column($cart, 'subtotal'));
        $amountPaid = floatval($this->request->getPost('amount_paid'));
        
        // Validate amount paid
        if ($amountPaid <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter a valid amount paid']);
        }
        
        if ($amountPaid < $total) {
            return $this->response->setJSON(['success' => false, 'message' => 'Amount paid is less than total']);
        }

        $change = $amountPaid - $total;
        $customerId = $this->request->getPost('customer_id') ?: null;

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $db = \Config\Database::connect();
        $db->transStart();

        // Insert sale
        $saleData = [
            'invoice_number' => $invoiceNumber,
            'user_id'        => session()->get('user_id'),
            'customer_id'    => $customerId,
            'total_amount'   => $total,
            'amount_paid'    => $amountPaid,
            'change_due'     => $change,
            'sale_date'      => date('Y-m-d H:i:s'),
            'payment_method' => 'cash',
            'status'         => 'completed'
        ];

        $saleId = $this->saleModel->insert($saleData);
        
        if (!$saleId) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save sale record']);
        }

        // Insert sale items and update stock
        $itemModel = new \App\Models\SaleItemModel();
        $productModel = new \App\Models\ProductModel();
        
        foreach ($cart as $item) {
            // Insert sale item
            $itemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $item['subtotal']
            ]);
            
            // Reduce stock using ProductModel method
            $productModel->updateStock($item['product_id'], $item['quantity'], 'subtract');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Transaction failed']);
        }

        // Clear cart
        session()->remove('cart');

        return $this->response->setJSON([
            'success'        => true,
            'invoice_number' => $invoiceNumber,
            'total_amount'   => $total,
            'change_due'     => $change
        ]);
    }

    // -------------------------------------------------------------
    //  Search Products (AJAX for POS)
    // -------------------------------------------------------------
    public function searchProducts()
    {
        $keyword = $this->request->getGet('keyword');
        if (!$keyword || strlen($keyword) < 2) {
            return $this->response->setJSON([]);
        }

        $products = $this->productModel->like('generic_name', $keyword)
                                       ->orLike('brand_name', $keyword)
                                       ->orLike('product_code', $keyword)
                                       ->where('stock_quantity >', 0)
                                       ->where('expiry_date >', date('Y-m-d'))
                                       ->orderBy('generic_name', 'ASC')
                                       ->limit(20)
                                       ->findAll();

        return $this->response->setJSON($products);
    }

    // -------------------------------------------------------------
    //  Print Receipt
    // -------------------------------------------------------------
    public function receipt($saleId)
    {
        $sale = $this->saleModel->find($saleId);
        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale not found');
        }

        $items = $this->saleItemModel->select('sale_items.*, products.generic_name, products.brand_name')
                                     ->join('products', 'products.product_id = sale_items.product_id')
                                     ->where('sale_id', $saleId)
                                     ->findAll();

        $data = [
            'title' => 'Receipt',
            'sale'  => $sale,
            'items' => $items
        ];
        return view('sales/receipt', $data);
    }

    // -------------------------------------------------------------
//  Delete/Void a Sale (Admin only or cashier with permission)
// -------------------------------------------------------------
public function delete($id)
{
    // Optional: restrict to admin
    // if (session()->get('role') !== 'admin') {
    //     return redirect()->to('/sales')->with('error', 'Permission denied');
    // }

    // Find the sale
    $sale = $this->saleModel->find($id);
    if (!$sale) {
        return redirect()->to('/sales')->with('error', 'Sale not found');
    }

    // Get sale items
    $items = $this->saleItemModel->where('sale_id', $id)->findAll();

    $db = \Config\Database::connect();
    $db->transStart();

    // Restore stock for each item
    $productModel = new \App\Models\ProductModel();
    foreach ($items as $item) {
        $product = $productModel->find($item['product_id']);
        if ($product) {
            $productModel->update($item['product_id'], [
                'stock_quantity' => $product['stock_quantity'] + $item['quantity']
            ]);
        }
    }

    // Delete sale items
    $this->saleItemModel->where('sale_id', $id)->delete();

    // Delete sale
    $this->saleModel->delete($id);

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->to('/sales')->with('error', 'Transaction failed');
    }

    return redirect()->to('/sales')->with('success', 'Sale deleted and stock restored');
}
}