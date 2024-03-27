<?php

namespace App\Controllers;

use App\Models\Category;
use Exception;

class CategoryController extends CrudController
{
	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $category
	 *
	 * @param Category $category la catégorie dans laquelle on stocke les données du formulaire
	 * 
	 * @throws Exception si des données sont invalides
	 */
	private function getDataFromPost(Category $category)
	{
		// Est-ce que les données en POST sont correct ?
		// Si une donnée est invalide, $dataAreValid = false;
		// À la fin de la fonction, si $dataAreValid est faux, on lance une exception
		$dataAreValid = true;

		// $name contient le nom de la catégorie filtré
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		// $subtitle contient le nom du sous titre filtré
		$subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$pattern = '/\.(jpg|jpeg|gif|svg|png)$/i';
		
		// Si j'ai des choses dans $_POST['picture']
		// Je vérifie est-ce que c'est bien une URL et que l'URL se termine bien par jpg, gif, svg ou png
		if (isset($_POST['picture'])) {
			$picture = $_POST['picture'];
			if (filter_var($picture, FILTER_VALIDATE_URL) && preg_match($pattern, $picture)) {
				// $picture contient soit l'URL de l'image soit faux si le visiteur n'a pas mis d'URL
				$picture = filter_var($picture, FILTER_SANITIZE_URL);
			} else {
				$this->addFlashErrorMessage("L'URL de l'image est invalide ou l'extension n'est pas prise en charge.");
				$dataAreValid = false;
			}
		} 

		// On essaye de stocker le nom
		// Si ça revoie false, le nom est vide, 
		// Il n'est pas enregistré
		if (false === $category->setName($name)) {
			// le nom de la catégorie est vide
			// J'enregistre un mesage d'erreur
			$this->addFlashErrorMessage('Nom invalide');
			$dataAreValid = false;
		}

		if (false === $picture) {
			// picture ne contient pas une URL
			// J'enregistre un message d'erreur
			$this->addFlashErrorMessage('Image invalide');
			$dataAreValid = false;
		}

		$category->setSubtitle($subtitle);
		$category->setPicture($picture);

		if (!$dataAreValid) {
			// Si les données sont invalides
			// On envois une exception
			throw new Exception('Données invalides');
		}
	}

	/**
	 * Appel la fonction show pour afficher le formulaire d'édition d'une catégorie.
	 *
	 * @param string $title titre du formulaire
	 * @param Category $category la catégorie à afficher dans le formulaire
	 * @return void
	 */
	private function showForm(string $title, Category $category): void
	{

		$data = [];
		$data['title'] = $title;
		$data['category'] = $category;
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('category/add-update', $data);
	}

	/**
	 * Action de lister les catégories sur la page /category/list
	 *
	 * @return void retour rien : pas de return
	 */
	public function list(): void
	{

		$data['categoryList'] = Category::findAll();
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('category/list', $data);
	}

	/**
	 * Action pour présenter le formulaire d'ajout d'une catégorie
	 *
	 * @return void
	 */
	public function add(): void
	{
		// @deprecated 
		// $this->checkAuthorisation(['admin']);

		$this->showForm('Ajouter une catégorie', new Category());
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

			// 3. enregistre les valeurs de l'objet dans la BDD
			$category->insert();

			// Un petit message de confirmation
			$this->addFlashNotification("Nouvelle catégorie enregistrée : " . $category->getName());

			// Redirect After Post
			$this->redirect('category-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur est survenue
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec les infos saisies
			$this->showForm('Ajouter une catégorie', $category);
		}
	}

	/**
	 * Action de présenter le formulaire pré-rempli pour éditer une catégorie
	 *
	 * @return void
	 */
	public function edit($categoryId): void
	{
		try {

			$category = Category::find($categoryId);

			if (null === $category) {
				// Si la catégorie n'existe pas
				$this->addFlashErrorMessage("La catégrie n'existe plus");
				// Redirige vers le formulaire d'ajout d'une catégorie
				// Le script s'arrête ici
				$this->redirect('category-list');
			}
	
			// La catégorie existe, on continue le script normalement
			// On affiche le formulaire avec les infos saisies
			$this->showForm('Modifiier la catégorie ' . $category->getName(), $category);
			
		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec la catégorie à modifier
			$this->redirect('category-list');
		}

	}

	/**
	 * Action de mettre à jour 1 catégorie
	 *
	 * @param [type] $categoryId l'identifiant de la catégorie à mettre à jour
	 * @return void
	 */
	public function update($categoryId): void
	{
		// Essaye de faire la mise à jour d'une catégorie
		try {
			// Recherche dans la BDD les infos de la catégorie qui doit etre modifiée
			$category = Category::find($categoryId);

			if (null === $category) {
				// La catégorie n'existe plus
				// Pas la peine de continuer,

				// $catégorie est null
				// je mets une nouvelle instance de catégorie dans la vairable
				// elle sera utilisée dans le catch 
				$category = new Category();

				// Il suffit de lancer une exception
				// Elle sera capturée dans le catch juste en dessous
				throw new Exception("La catégorie à modifier n'existe plus");
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

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec la catégorie à modifier
			$this->showForm('Modifiier une catégorie', $category);
		}
	}

	public function delete(int $id): void
	{
		try {
			$category = Category::find($id);

			$category->delete();

			$this->addFlashNotification('Catégorie supprimée');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}

		// Redirect After Post
		$this->redirect('category-list');
	}

	public function homeOrder(): void
	{
		// @see ACL 
		// $this->checkAuthorization(['admin', 'catalog-manager']);

		$categoryList = Category::findAll();

		$data = [
			'categoryList' => $categoryList,
			'tokenCSRF' => $this->generateTokenCSRF(),
		];

		$this->show('category/home-order', $data);
	}

	/**
	 * Vérifier si les valeurs d'un tableau associatif sont uniques
	 *
	 * @param [array] $array tableau associatif à vérifier
	 * @return bool vrai si les valeurs du tableau sont uniques
	 */
	private function valuesAreUnique(array $array): bool
	{
		// Toutes les valeurs du tableau
		$values = array_values($array);
		// Les valeurs uniques du tableau
		$uniqueValues = array_unique($values);

		// Est-ce que le nombre de valeur dans le tabeau est égal 
		// au nombre de valeur unique dans le tableau ? 
		return count($values) === count($uniqueValues);
	}

	public function editHomeOrder(): void
	{
		// On vérifie si les catégories choisies sont uniques
		if( !$this->valuesAreUnique($_POST['emplacements'])) {
			$this->addFlashErrorMessage('Il faut sémectionner des catégories uniques');
			$this->redirect('category-home-order');
		}

		try {
			// 1. reset de tous les home order
			Category::resetHomeOrder();

			// 2. Parcours des POST['emplacements'] = [index du tableau => id de la catégorie]
			foreach ($_POST['emplacements'] as $i => $idCategory) {
				// Cherche la catégorie à modifier
				$category = Category::find($idCategory);

				// Change le home order de la catégorie
				$category->setHomeOrder($i + 1);
				// Mise à jour de la catégorie
				$category->save();
			}

			$this->addFlashNotification('Ordre des catégories modifié');
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}

		// Redirect After Post
		$this->redirect('category-home-order');
	}
}
