<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    private $userModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            exit('Access denied');
        }
        $this->userModel = new UserModel();
    }
    
    // Display users list
    public function index()
    {
        $data['title'] = 'User Management';
        $data['users'] = $this->userModel->orderBy('user_id', 'ASC')->findAll();
        return view('users/index', $data);
    }
    
    // Save new user
    public function save()
    {
        $rules = [
            'username'  => 'required|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'full_name' => 'required',
            'password'  => 'required|min_length[4]',
            'role'      => 'required|in_list[admin,pharmacist,cashier]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->userModel->save([
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'full_name'  => $this->request->getPost('full_name'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'       => $this->request->getPost('role'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to('/users')->with('success', 'User added successfully');
    }
    
    // Update user
    public function update()
    {
        $id = $this->request->getPost('user_id');
        
        $rules = [
            'username'  => "required|is_unique[users.username,user_id,$id]",
            'email'     => "required|valid_email|is_unique[users.email,user_id,$id]",
            'full_name' => 'required',
            'role'      => 'required|in_list[admin,pharmacist,cashier]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'role'      => $this->request->getPost('role')
        ];
        
        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $this->userModel->update($id, $data);
        
        return redirect()->to('/users')->with('success', 'User updated successfully');
    }
    
    // Delete user
    public function delete($id)
    {
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account');
        }
        
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted successfully');
    }
    
    // Edit form (AJAX friendly)
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }
        return $this->response->setJSON($user);
    }
}