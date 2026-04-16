<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['username', 'password', 'email', 'full_name', 'role', 'created_at'];
    protected $useTimestamps = false;
    
    protected $validationRules = [
        'username' => 'required|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'full_name'=> 'required',
        'password' => 'required|min_length[4]',
        'role'     => 'required|in_list[admin,pharmacist,cashier]'
    ];
}