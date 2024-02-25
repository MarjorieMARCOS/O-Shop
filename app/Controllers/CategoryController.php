<?php

namespace App\Controllers;
use App\Models\Category;
use Exception;


class CategoryController extends CoreController
{
	/**
	 * Action de lister les catégories sur la page /category/list
	 *
	 * @return void retour rien : pas de return
	 */
    public function list(): void
    {
        $data = [];
        $data['categoryList'] = Category::findAll();
        $this->show('category/list', $data);
    }

	/**
	 * Action pour présenter le formulaire d'ajout d'une catégorie
	 *
	 * @return void
	 */
    public function add(): void
    {
        $this->show('category/add');
    }


	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $category
	 *
	 * @param Category $category la catégorie dans laquelle on stocke les donénes du formulaire
	 * 
	 * @throws Exception si des données sont invalides
	 */
	private function getDataFromPost(Category $categorie): void
	{
		// Est-ce que les données en POST sont correct ?
		// Si une donnée est invalide, $dataAreValid = false;
		// À la fin de la fonction, si $dataAreValid est faux, on lance une exception
		$dataAreValid = true;

		// $name contient le nom de la catégorie filtré
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		// $subtitle contient le nom du sous titre filtré
		$subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
			// le message d'erreur est en registrer
			$this->addFlashErrorMessage('Nom invalide');
			$dataAreValid = false;

		}

		if(false === $picture) {
			// picture ne contient pas une URL
			// le message d'erreur est enregistrer
			$this->addFlashErrorMessage('Image invalide');
			$dataAreValid = false;
		}

		$categorie->setSubtitle($subtitle);
		$categorie->setPicture($picture);

		if (!$dataAreValid) {
			// Si les données sont invalides
			// On envois une exception
			throw new Exception('Données invalides');
		}
	}

	/**
	 * Action pour enregistrer une nouvelle catégorie
	 *
	 * @return void
	 */
	public function create(): void
	{
		// 1. nouvelle instance de catégorie
		$category = new Category();

		try {
		// 2. récupère les données en POST
		$this->getDataFromPost($category);

		// 3. enregistrer les valeurs de l'objet dans la BBD
		$category->insert();

		// 4. message d'information
		$this->addFlashNotification("Nouvelle catégorie enregistrée : " . $category->getName());

		// 5. rediriger after post
		$this->redirect('category-list');
			
		} catch (Exception $exception) {
			// afficher l'erreur
			$this->addFlashErrorMessage($exception->getMessage());

			// rediriger after post
			$this->redirect('category-list');
		}

	}


	/**
	 * Action de présenter le formulaire pré-rempli pour éditer une catégorie
	 *
	 * @return void
	 */
    public function update($params): void
    {
        $categoryId = $params;
		$category = Category::find($categoryId);

		if (null === $category) {
			// si la page n'existe pas on affiche une erreur
			$this->addFlashErrorMessage("La catégrie n'existe plus");

			// redirection
			$this->redirect('category-list');
		}
        $data = [];
        $data['category'] = $category;
        $this->show('category/update', $data);
    }


	/**
	 * Action de mettre à jour 1 catégorie
	 *
	 * @param [type] $categoryId l'identifiant de la catégorie à mettre à jour
	 * @return void
	 */
    public function modify($categoryId): void
    {

		try {
			// Recherche dans la BDD les infos de la catégorie qui doit etre modifiée
			$category = Category::find($categoryId);

			if (null === $category) {
				// si $category null je mets une nouvelle instance qui sera utilisée dans le catch
				$category = new Category();

				// il suffit de lancer une exception
				// Elle sera capturée dans le catch en dessous
				throw new Exception('La catégorie n\' existe plus');
			}

			// Essaye de récupérer les données du formulaire			
			$this->getDataFromPost($category);

			// Les données du formulaire sont valides
			// Je peux mettre à jour la catégorie

			// J'essaye de faire la maj de la catégorie
			$category->update();

			$this->addFlashNotification('La catégorie ' . $category->getName() . ' a bien été modifiée');

			// Redirect After Post
			$this->redirect('category-list');

		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			// Redirect After Post
			$this->redirect('category-list');
		}
		

    }



}
