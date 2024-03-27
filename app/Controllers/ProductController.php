<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Type;
use Exception;

class ProductController extends CrudController
{

	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $product
	 *
	 * @param Product $product la catégorie dans laquelle on stocke les donénes du formulaire
	 * 
	 * @throws Exception si les données sont invalides
	 */
	private function getDataFromPost(Product $product)
	{
		// Est-ce que les données en POST sont correct ?
		// Si une donnée est invalide, $dataAreValid = false;
		// À la fin de la fonction, si $dataAreValid est faux, on lance une exception
		$dataAreValid = true;

		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_NUMBER_INT);
		$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
		$brandId = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_NUMBER_INT);
		$typeId = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
		$categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);

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
		if (false === $product->setName($name)) {
			// le nom de la catégorie est vide
			// erreur
			$this->addFlashErrorMessage("Nom invalide");
			$dataAreValid = false;
		}

		// Price
		if (empty($price) || false === $product->setPrice($price)) {
			// le nom de la catégorie est vide
			// erreur
			$this->addFlashErrorMessage("Prix invalide");
			$dataAreValid = false;
		}

		// Rate
		if (empty($rate) || false === $product->setRate($rate)) {
			// le nom de la catégorie est vide
			// erreur
			$this->addFlashErrorMessage("Note invalide");
			$dataAreValid = false;
		}

		// Status
		if (empty($status) || false === $product->setStatus($status)) {
			// le nom de la catégorie est vide
			// erreur
			$this->addFlashErrorMessage("Status invalide");
			$dataAreValid = false;
		}

		if (false === $picture) {
			// picture ne contient pas une URL
			// erreur
			$this->addFlashErrorMessage("Image invalide");
			$dataAreValid = false;
		}

		$product->setDescription($description);
		$product->setPicture($picture);
		$product->setBrandId($brandId);
		$product->setTypeId($typeId);
		$product->setCategoryId($categoryId);


		if (!$dataAreValid) {
			// Si les données sont invalides
			// On envois une exception
			throw new Exception('Données invalides');
		}
	}

	/**
	 * Appel la fonction show pour afficher le formulaire d'édition d'un produit.
	 *
	 * @param string $title titre du formulaire
	 * @param Product $product le produit à afficher dans le formulaire
	 * @return void
	 */
	private function showForm(string $title, Product $product): void
	{
		$data = [];
		$data['title'] = $title;
		$data['product'] = $product;
		$data['categoryList'] = Category::findAll();
		$data['typeList'] = Type::findAll();
		$data['brandList'] = Brand::findAll();
		$data['tags'] = Tag::findAll();

		if (0 != $product->getId()) {
			$data['productTags'] = Tag::findAllByProductId($product->getId());
		} else {
			$data['productTags'] = [];
		}

		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('product/add-update', $data);
	}

	/**
	 * Enregistrer ou mettre à jour un produit avec les données du formulaure
	 *
	 * @param Product $product à enregistrer
	 * @return void
	 */
	private function saveProduct(Product $product): void
	{
		try {
			// 2. récupère les données en POST
			$this->getDataFromPost($product);

			// 3. enregistre les valeurs de l'objet dans la BDD
			$product->insert();

			// Supprime tous les tags du produits
			$product->resetTags();

			// Enregistre les nouveaux tags du produit
			$product->saveTagsId($_POST['tags']);

			// Un petit message de confirmation
			$this->addFlashNotification("Produit enregistré : " . $product->getName());

			// Redirect After Post
			$this->redirect('product-list');

			// Catch pour gérer les erreurs
		} catch (Exception $exception) {
			// Une exception a été lancée
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec les infos saisies
			$this->showForm('Éditer un produit', $product);
		}
	}

	public function list(): void
	{
		$data['productList'] = Product::findAll();
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('product/list', $data);
	}

	/**
	 * Action afficher le formulaire d'ajout d'un produit
	 *
	 * @return void
	 */
	public function add(): void
	{
		$this->showForm('Ajouter un produit', new Product());
	}

	/**
	 * Action pour enregistrer un nouveau produit
	 *
	 * @return void
	 */
	public function create(): void
	{
		// 1. nouvelle instance de produit
		$product = new Product();

		$this->saveProduct($product);
	}

	/**
	 * Action de présenter le formulaire pré-rempli pour éditer un produit
	 *
	 * @return void
	 */
	public function edit($productId): void
	{
		try {
			$product = Product::find($productId);

			if (null === $product) {
				// Si la catégorie n'existe pas
				$this->addFlashErrorMessage("Le produit n'existe plus");
				// Redirige vers le formulaire d'ajout d'une catégorie
				// Le script s'arrête ici
				$this->redirect('product-list');
			}
	
			// Le produit existe, on continue le script normalement
			// On affiche le formulaire avec les infos saisies
			$this->showForm('Modifier le produit ' . $product->getName(), $product);

		} catch (Exception $exception) {
			// Une exception a été lancée
			$this->addFlashErrorMessage($exception->getMessage());

			// On redirige
			$this->redirect('product-list');
		}

	}

	/**
	 * Action de mettre à jour un produit
	 *
	 * @param [type] $prouctId l'identifiant du produit à mettre à jour
	 * @return void
	 */
	public function update(int $productId): void
	{
		try {

		// Recherche dans la BDD les infos du produit qui doit etre modifié
		$product = Product::find($productId);

		// Cas d'erreur : le produit n'existe plus
		if (null === $product) {
			// Le produit n'existe plus
			// Pas la peine de continuer,

			// $product est null
			// je mets une nouvelle instance de Product dans la vairable
			// elle sera utilisée dans le catch 
			$product = new Product();

			// Il suffit de lancer une exception
			// Elle sera capturée dans le catch juste en dessous
			$this->addFlashErrorMessage("Le produit à modifier n'existe plus");

			// redirection vers la liste des produits
			$this->redirect('product-list');
		}

		$this->saveProduct($product);

		} catch (Exception $exception) {
			// Une exception a été lancée
			$this->addFlashErrorMessage($exception->getMessage());

			// On redirige
			$this->redirect('product-list');
		}

	}

	public function delete(int $id): void
	{
		try {
			$product = Product::find($id);

			// Supprimer les tags du produit
			// Si on ne le fait il va y avoir des problèmes avec les clés étrangères
			$product->resetTags();

			// Supprimer le produit
			$product->delete();

			$this->addFlashNotification('Produit supprimé');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}

		// Redirect After Post
		$this->redirect('product-list');
	}
}
