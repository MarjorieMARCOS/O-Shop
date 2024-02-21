<?php

namespace App\Controllers;

// Si j'ai besoin du Model Category
use App\Models\Category;
use App\Models\Product;

class MainController extends CoreController
{
    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {
        $categoryModel = new Category();
        $limitListCategory =  $categoryModel->findOnly3();

        $productModel = new Product();
        $limitListProduct = $productModel->findOnly3();
        
        $data = [];
        $data['limitListCategory'] = $limitListCategory;
        $data['limitListProduct'] = $limitListProduct;
        $this->show('main/home', $data);
    }

    public function category()
    {
        $categoryModel = new Category();
        $categoryList = $categoryModel->findAll();

        $data = [];
        $data['categoryList'] = $categoryList;
        $this->show('category/listCategory', $data);
    }

    public function product()
    {
        $productModel = new Product();
        $productList = $productModel->findAll();

        $data = [];
        $data['productList'] = $productList;
        $this->show('product/listProduct', $data);
    }

    public function categoryAdd()
    {
        $name = $_POST['name'];
        dump($name);
        $subtitle = $_POST['subtitle'];
        dump($subtitle);
        $picture = $_POST['picture'];
        dump($picture);
        $categoryModel = new Category();

        $data = [];
        $this->show('category/addCategory', $data);
    }

    public function productAdd()
    {
        $productModel = new Product();

        $data = [];
        $this->show('product/addProduct', $data);
    }

}
