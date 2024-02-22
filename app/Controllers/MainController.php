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
        
        $data = [];
        $data['limitListCategory'] = Category::findOnly3();
        $data['limitListProduct'] = Product::findOnly3();
        $this->show('main/home', $data);
    }

}
