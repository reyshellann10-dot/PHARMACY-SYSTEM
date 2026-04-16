<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;

class Reports extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');
        
        $saleModel = new SaleModel();
        $productModel = new ProductModel();
        
        $data['totalSales'] = $saleModel->selectSum('total_amount')->first()['total_amount'] ?? 0;
        $data['totalProducts'] = $productModel->countAll();
        $data['lowStock'] = $productModel->where('stock_quantity <=', 'reorder_level', false)->countAllResults();
        
        return view('reports/index',  $data);
    }
    
    public function sales()
    {
        $saleModel = new SaleModel();
        $data['sales'] = $saleModel->orderBy('sale_date', 'DESC')->findAll();
        return view('reports/sales' ,  $data);
    }
}
