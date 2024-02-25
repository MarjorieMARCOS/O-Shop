<?php

namespace App\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use App\Models\Brand;
use \Exception; 

class ProductController extends CoreController
{

	/**
	 * Action de lister les produits sur la page /product/list
	 *
	 * @return void retour rien : pas de return
	 */
    public function list(): void
    {
        $data = [];
        $data['productList'] = Product::findAll();
        $this->show('product/list', $data);
    }

	/**
	 * Action pour présenter le formulaire d'ajout d'un produit
	 *
	 * @return void
	 */
    public function add() : void
    {

         // récupérer dans le modèle la liste des catégories
		// récupérer dans le modèle la liste des marques
		// récupérer dans le modèle la liste des types
		// envoyer toutes ces données dans le formulaire
 
        $data = [];
		$data['categoryList'] = Category::findAll();
		$data['typeList'] = Type::findAll();
		$data['brandList'] = Brand::findAll();
		$data['product'] = new Product();
        $this->show('product/add', $data);
    }
    

	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $product
	 *
	 * @param Product $product la catégorie dans laquelle on stocke les donénes du formulaire
	 * @return bool true si toutes les données sont valides faux sinon
	 */
	private function getDataFromPost(Product $product): void
	{
		$dataAreValid = true;

		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_NUMBER_INT);
		$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
		$brandId = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_NUMBER_INT);
		$typeId = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
		$categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
		
		// Si j'ai des choses dans $_POST['picture']
		// Je vérifie est-ce que c'est bien une URL
		if(!empty($_POST['picture'])) {
			// $picture contient soit l'URL de l'image soit faux si le visiteur n'a pas mis d'URL
			$picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
		} else {
			// Sinon il n'y a rien dans le champ POPST 'picture'
			// Je mets une chaine vide
			$picture = '';
		}

		// On essaye de stocker le nom
		// Si ça revoie false, le nom est vide, 
		// Il n'est pas enregistré
		if (false === $product->setName($name)) {
			// le nom du produit est vide
			// le message d'erreur est en registrer
			$this->addFlashErrorMessage('Nom invalide');
			$dataAreValid = false;
		}

		// Price
		if (false === $product->setPrice($price)) {
			// le nom du produit est vide
			// le message d'erreur est en registrer
			$this->addFlashErrorMessage('Prix invalide');
			$dataAreValid = false;
		}

		// Rate
		if (false === $product->setRate($rate)) {
			// la note du produit est vide
			// le message d'erreur est en registrer
			$this->addFlashErrorMessage('Note invalide');
			$dataAreValid = false;
		}

		// Status
		if (false === $product->setStatus($status)) {
			// le status du produit est vide
			// le message d'erreur est en registrer
			$this->addFlashErrorMessage('Statut invalide');
			$dataAreValid = false;
		}

		if(false === $picture ) {
			// picture ne contient pas une URL
			// le message d'erreur est enregistrer
			$this->addFlashErrorMessage('Photo invalide');
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
     * Action pour enregister un nouveau produit
     * 
     * @return void
     */

    public function create(): void
    {
        $product = new Product();

		try {
			$this->getDataFromPost($product);
			$product->insert();
			$this->addFlashNotification("Nouveau produit enregistré : " . $product->getName());
			$this->redirect('product-list');
		} catch (Exception $exception) {

			$this->addFlashErrorMessage($exception->getMessage());

		}

        if(false === $this->getDataFromPost($product)) {
            $this->redirect('product-list');

        }

    }

	/**
	 * Action de présenter le formulaire pré-rempli pour éditer un produit
	 *
	 * @return void
	 */
    public function update($params): void
    {
        $productId = $params;
		$product = Product::find($productId);

		if (null === $product) {

			$this->addFlashErrorMessage("Le produit n'existe plus");
			$this->redirect('product-list');
		}
        $data = [];
        $data['product'] = $product;
		$data['categoryList'] = Category::findAll();
		$data['typeList'] = Type::findAll();
		$data['brandList'] = Brand::findAll();
		dump($product);
        $this->show('product/update', $data);
    }


	/**
	 * Action de mettre à jour 1 catégorie
	 *
	 * @param [type] $categoryId l'identifiant de la catégorie à mettre à jour
	 * @return void
	 */
    public function modify($params): void
    {
		$productId = $params;

		try {

			$product = product::find($productId);
			if (null === $product) {

				$product = new Product();
				throw new Exception('Le produit n\' existe plus');
				
			}

			$this->getDataFromPost($product);
			$product->update();

			$this->addFlashNotification('Le produit ' . $product->getName() . ' a bien été modifié');
			
			$this->redirect('product-list');

		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
			$this->redirect('product-list');
		}
        

    }


}