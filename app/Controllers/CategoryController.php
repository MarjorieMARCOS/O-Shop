<?php

namespace App\Controllers;
use App\Models\Category;

class CategoryController extends CoreController
{
    public function list(): void
    {
        $data = [];
        $data['categoryList'] = Category::findAll();
        $this->show('category/list', $data);
    }

    public function add()
    {
        $data = [];
        $this->show('category/add', $data);
    }

    public function create()
    {
        $categoryModel = new Category();
        $categoryModel->insert();

        $data = [];
        $this->show('category/add', $data);
    }
}