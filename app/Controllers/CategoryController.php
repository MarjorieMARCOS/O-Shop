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

    public function add(): void
    {
        $this->show('category/add');
    }


	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $category
	 *
	 * @param Category $category la catégorie dans laquelle on stocke les donénes du formulaire
	 * @return bool true si toutes les données sont valides faux sinon
	 */
	private function getDataFromPost(Category $categorie): bool
	{
		// $name contient le nom de la catégorie filtré
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //dump($name);
		// $subtitle contient le nom du sous titre filtré
		$subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       // dump($subtitle);
		// Si j'ai des choses dans $_POST['picture']
		// Je vérifie est-ce que c'est bien une URL
		if(!empty($_POST['picture'])) {
			// $picture contient soit l'URL de l'image soit faux si le visiteur n'a pas mis d'URL
			$picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
            //dump($picture);
		} else {
			// Sinon il n'y a rien dans le champ POPST 'picture'
			// Je mets une chaine vide
			$picture = '';
		}

		// On essaye de stocker le nom
		// Si ça revoie false, le nom est vide, 
		// Il n'est pas enregistré
		if (false === $categorie->setName($name)) {
			// le nom de la catégorie est vide
			// erreur
			return false;

		}

		if(false === $picture ) {
			// picture ne contient pas une URL
			// erreur
			return false;
		}

		$categorie->setSubtitle($subtitle);
		$categorie->setPicture($picture);

		// On a bien enregistré dans $categorie toutes les données du formulaire
		return true;
	}

	/**
	 * Action pour enregistrer une nouvelle catégorie
	 *
	 * @return void
	 */
	public function create(): void
	{
		// 1. nouvelle instance de catégorie
		$categorie = new Category();
		// 2. récupère les données en POST
		if(false === $this->getDataFromPost($categorie)) {
			// Il y a des erreur dans les données saisie par le visiteur
			// traiter le cas d'erreur
			// Une redirection pour quitter la fonction sans faire l'enregistrement
			// Bonus : afficher un message d'erreur au visiteur
			$this->redirect('category-list');
		}

		// 3. enregistre les valeurs de l'objet dans la BDD
		$result = $categorie->insert();
		// 4.1 Bonus traitre le cas où $result est faux
		// l'insertion s'est mal passée

		// 5. redirection vers la liste des catégories
		// Redirect After Post
		$this->redirect('category-list');
	}



    public function update($params): void
    {
        $categoryId = $params;
        $data = [];
        $data['category'] = Category::find($categoryId);
        $this->show('category/update', $data);
    }


    public function modify(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $categorie = new Category();
        //dump($categorie);
        if(false === $this->getDataFromPost($categorie)) {
            $this->redirect('category-list');
    }
    $result = $categorie->update($id);
    //dump($result);
    $this->redirect('category-list');
    }



}
