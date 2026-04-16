<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'sale_item_id';
    protected $allowedFields = ['sale_id', 'product_id', 'quantity', 'unit_price', 'subtotal'];
    protected $useTimestamps = false;
}