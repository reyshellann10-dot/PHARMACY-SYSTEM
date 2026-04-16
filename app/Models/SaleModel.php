<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $allowedFields = [
        'invoice_number', 'user_id', 'customer_id', 'total_amount',
        'amount_paid', 'change_due', 'sale_date', 'payment_method', 'status'
    ];
    protected $useTimestamps = false;
}