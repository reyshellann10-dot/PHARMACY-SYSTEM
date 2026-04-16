<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login');
    }
    
    public function auth()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Hardcoded credentials (for testing - remove later)
        if ($username === 'admin' && $password === 'password123') {
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => 1,
                'username'   => 'admin',
                'full_name'  => 'Administrator',
                'role'       => 'admin'
            ]);
            return redirect()->to('/dashboard')->with('success', 'Login successful');
        }
        
        // Invalid login
        return redirect()->back()->with('error', 'Invalid username or password');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}