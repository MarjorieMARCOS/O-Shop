<?php

namespace App\Controllers;
use App\Models\Product;

class ProductController extends CoreController
{
    public function list(): void
    {
        $data = [];
        $data['productList'] = Product::findAll();
        $this->show('product/list', $data);
    }

    public function add()
    {
 
        $data = [];
        $this->show('product/add', $data);
    }

    public function create()
    {
        $productModel = new Product();
        $productModel->insert();

        $data = [];
        $this->show('product/add', $data);
    }
}