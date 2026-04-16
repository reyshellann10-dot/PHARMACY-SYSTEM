<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Dashboard',
            'totalProducts' => 245,
            'lowStockItems' => 12,
            'expiringCount' => 8,
            'todaySales' => 5420
        ];
        
        return view('dashboard/index', $data);
    }
}