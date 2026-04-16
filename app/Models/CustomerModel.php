<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $allowedFields = ['first_name', 'last_name', 'contact_number', 'email', 'address', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name'  => 'required|min_length[2]|max_length[50]',
        'email'      => 'permit_empty|valid_email|is_unique[customers.email]',
        'contact_number' => 'permit_empty|min_length[10]|max_length[15]',
    ];
}