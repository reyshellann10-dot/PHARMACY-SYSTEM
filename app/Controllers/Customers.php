<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    private $customerModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            exit('Access denied');
        }
        $this->customerModel = new CustomerModel();
    }
    
    // List all customers
    public function index()
    {
        $data['title'] = 'Customer Management';
        $data['customers'] = $this->customerModel->orderBy('first_name', 'ASC')->findAll();
        return view('customers/index', $data);
    }
    
    // Show add form
    public function create()
    {
        $data['title'] = 'Add Customer';
        return view('customers/create', $data);
    }
    
    // Save new customer (AJAX + regular)
    public function store()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name'  => 'required|min_length[2]|max_length[50]',
            'email'      => 'permit_empty|valid_email|is_unique[customers.email]',
            'contact_number' => 'permit_empty|min_length[10]|max_length[15]',
        ];
        
        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $id = $this->customerModel->insert([
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'email'         => $this->request->getPost('email'),
            'contact_number'=> $this->request->getPost('contact_number'),
            'address'       => $this->request->getPost('address'),
            'created_at'    => date('Y-m-d H:i:s')
        ]);
        
        if ($this->request->isAJAX()) {
            $customer = $this->customerModel->find($id);
            return $this->response->setJSON(['success' => true, 'customer' => $customer]);
        }
        
        return redirect()->to('/customers')->with('success', 'Customer added successfully');
    }
    
    // Edit customer
    public function edit($id)
    {
        $data['title'] = 'Edit Customer';
        $data['customer'] = $this->customerModel->find($id);
        if (!$data['customer']) {
            return redirect()->to('/customers')->with('error', 'Customer not found');
        }
        return view('customers/edit', $data);
    }
    
    // Update customer
    public function update($id)
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name'  => 'required|min_length[2]|max_length[50]',
            'email'      => "permit_empty|valid_email|is_unique[customers.email,customer_id,$id]",
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->customerModel->update($id, [
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'email'         => $this->request->getPost('email'),
            'contact_number'=> $this->request->getPost('contact_number'),
            'address'       => $this->request->getPost('address')
        ]);
        
        return redirect()->to('/customers')->with('success', 'Customer updated');
    }
    
    // Delete customer
    public function delete($id)
    {
        // Check if customer has sales
        $saleModel = new \App\Models\SaleModel();
        $hasSales = $saleModel->where('customer_id', $id)->countAllResults();
        if ($hasSales > 0) {
            return redirect()->to('/customers')->with('error', 'Cannot delete customer with purchase history');
        }
        
        $this->customerModel->delete($id);
        return redirect()->to('/customers')->with('success', 'Customer deleted');
    }
    
    // View customer details
    public function view($id)
    {
        $data['customer'] = $this->customerModel->find($id);
        if (!$data['customer']) {
            return redirect()->to('/customers')->with('error', 'Customer not found');
        }
        $saleModel = new \App\Models\SaleModel();
        $data['purchases'] = $saleModel->where('customer_id', $id)->orderBy('sale_date', 'DESC')->findAll();
        $data['title'] = 'Customer Details';
        return view('customers/view', $data);
    }
}