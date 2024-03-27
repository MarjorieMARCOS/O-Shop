<?php

namespace App\Controllers;

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
		// @deprecated 
		// $this->checkAuthorisation(['admin', 'catalog-manager']);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        
		// Plus besoin de passer par un objet pour faire des select
		// On utlise static et la classe de la fonction
		$data['categoryList'] = Category::findAll();
		$data['productList'] = Product::findAll();

		$this->show('main/home', $data);
    }
}
