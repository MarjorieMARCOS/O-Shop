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
    

    /**
     * Récupère puis filtre les données en POST et les enregistre dans $product
     * 
     * @param Product $product là où on va stocker les données du formulaire
     * @return bool true si toutes les données sont valides faux sinon
     */


    private function getDataFromPost(Product $product): bool
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $rate = filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_INT);
        $brand_id = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT);
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

        if (!empty($_POST['picture'])) {
            $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
        } else {
            $picture = '';
        }

        if ($product->setName($name) == false) {
            return false;
        }
        if ($picture == false) {
            return false;
        }

        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setRate($rate);
        $product->setBrandId($brand_id);
        $product->setCategoryId($category_id);
        $product->setTypeId($type_id);

        return true;
    }

    /**
     * Action pour enregister un nouveau produit
     * 
     * @return void
     */

    public function create(): void
    {
        $product = new Product();

        if(false === $this->getDataFromPost($product)) {
            $this->redirect('product-list');
            
        }
        $result = $product->insert();

        $this->redirect('product-list');
    }

    public function update($params): void
    {
        $productId = $params;
        $data = [];
        $data['product'] = Product::find($productId);
        $this->show('product/update', $data);
    }


    public function modify(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $product = new Product();
        if(false === $this->getDataFromPost($product)) {
            $this->redirect('product-list');
    }
    $result = $product->update($id);
    $this->redirect('product-list');
    }


}