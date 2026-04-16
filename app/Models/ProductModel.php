<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $allowedFields = [
        'product_code', 'generic_name', 'brand_name', 'category_id',
        'unit_price', 'stock_quantity', 'reorder_level', 'expiry_date',
        'manufacturer', 'is_prescription_required', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'product_code' => 'required|is_unique[products.product_code]',
        'generic_name' => 'required',
        'unit_price' => 'required|numeric|greater_than[0]',
        'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        'expiry_date' => 'required|valid_date'
    ];

    public function updateStock($productId, $quantity, $type = 'subtract')
{
    $product = $this->find($productId);
    if (!$product) {
        return false;
    }

    if ($type === 'subtract') {
        $newQuantity = $product['stock_quantity'] - $quantity;
        if ($newQuantity < 0) {
            return false;
        }
    } else {
        $newQuantity = $product['stock_quantity'] + $quantity;
    }

    return $this->update($productId, ['stock_quantity' => $newQuantity]);
}
}

