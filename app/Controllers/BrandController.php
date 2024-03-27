<?php

namespace App\Controllers;
use App\Models\Brand;
use Exception;

class BrandController extends CrudController {


	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $brand
	 *
	 * @param Brand $brand la marque dans laquelle on stocke les données du formulaire
	 * 
	 * @throws Exception si des données sont invalides
	 */
	private function getDataFromPost(Brand $brand)
	{
		// Est-ce que les données en POST sont correct ?
		// Si une donnée est invalide, $dataAreValid = false;
		// À la fin de la fonction, si $dataAreValid est faux, on lance une exception
		$dataAreValid = true;

		// $name contient le nom de la catégorie filtré
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		// On essaye de stocker le nom
		// Si ça revoie false, le nom est vide, 
		// Il n'est pas enregistré
		if (false === $brand->setName($name)) {
			// le nom de la catégorie est vide
			// J'enregistre un mesage d'erreur
			$this->addFlashErrorMessage('Nom invalide');
			$dataAreValid = false;
		}

		if (!$dataAreValid) {
			// Si les données sont invalides
			// On envois une exception
			throw new Exception('Données invalides');
		}
	}

	/**
	 * Appel la fonction show pour afficher le formulaire d'édition d'une marque.
	 *
	 * @param string $title titre du formulaire
	 * @param Brand $brand la marque à afficher dans le formulaire
	 * @return void
	 */
	private function showForm(string $title, Brand $brand): void
	{

		$data = [];
		$data['title'] = $title;
		$data['brand'] = $brand;
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('brand/add-update', $data);
	}

	/**
	 * Action de lister les marque sur la page /brand/list
	 *
	 * @return void retour rien : pas de return
	 */
	public function list(): void
	{
		$data['brandList'] = Brand::findAll();
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('brand/list', $data);
	}

	/**
	 * Action pour présenter le formulaire d'ajout d'une marque
	 *
	 * @return void
	 */
	public function add(): void
	{
		$this->showForm('Ajouter une marque', new Brand());
	}

	/**
	 * Action pour enregistrer une nouvelle marque
	 *
	 * @return void
	 */
	public function create(): void
	{
		// 1. nouvelle instance de brand
		$brand = new Brand();

		try {

			// 2. récupère les données en POST
			$this->getDataFromPost($brand);

			// 3. enregistre les valeurs de l'objet dans la BDD
			$brand->insert();

			// Un petit message de confirmation
			$this->addFlashNotification("Nouvelle marque enregistrée : " . $brand->getName());

			// Redirect After Post
			$this->redirect('brand-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur est survenue
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec les infos saisies
			$this->showForm('Ajouter une nouvelle', $brand);
		}
	}

	/**
	 * Action de présenter le formulaire pré-rempli pour éditer une marque
	 *
	 * @return void
	 */
	public function edit($brandId): void
	{
		try {
			$brand = Brand::find($brandId);

			if (null === $brand) {
				// Si la brand n'existe pas
				$this->addFlashErrorMessage("La marque n'existe plus");
				// Redirige vers le formulaire d'ajout d'une marque
				// Le script s'arrête ici
				$this->redirect('brand-list');
			}
	
			// La marque existe, on continue le script normalement
			// On affiche le formulaire avec les infos saisies
			$this->showForm('Modifiier la marque ' . $brand->getName(), $brand);

		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			//On redirige
			$this->redirect('brand-list');
		}

	}

	/**
	 * Action de mettre à jour une marque
	 *
	 * @param Brand $brand la marque à mettre à jour
	 * @return void
	 */
	public function update($brandId): void
	{
		// Essaye de faire la mise à jour d'une marque
		try {
			// Recherche dans la BDD les infos d'une marque qui doit etre modifiée
			$brand = Brand::find($brandId);

			if (null === $brand) {
				// La marque n'existe plus
				// Pas la peine de continuer,

				// $brand est null
				// je mets une nouvelle instance de brand dans la vairable
				// elle sera utilisée dans le catch 
				$brand = new Brand();

				// Il suffit de lancer une exception
				// Elle sera capturée dans le catch juste en dessous
				throw new Exception("La marque à modifier n'existe plus");
			}

			// Essaye de récupérer les données du formulaire			
			$this->getDataFromPost($brand);

			// Les données du formulaire sont valides
			// Je peux mettre à jour la marque

			// J'essaye de faire la maj de la marque
			$brand->update();

			$this->addFlashNotification('La marque ' . $brand->getName() . ' a bien été modifiée');

			// Redirect After Post
			$this->redirect('brand-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec la marque à modifier
			$this->showForm('Modifiier une marque', $brand);
		}
	}

	/**
	 * Action de supprimer une marque
	 *
	 * @param Brand $brand la marque à supprimer
	 * @return void
	 */
	public function delete(int $id): void
	{
		try {
			$brand = Brand::find($id);

			$brand->delete();

			$this->addFlashNotification('Marque supprimée');

		// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}

		// Redirect After Post
		$this->redirect('brand-list');
	}

	/**
	 * Va affichier la page footer-order en liste les footer_order des marques
	 *
	 * @return void
	 */
	public function footerOrder(): void
	{
		$brandList = Brand::findAll();
		
		$data = [
			'brandList' => $brandList,
			'tokenCSRF' => $this->generateTokenCSRF(),
		];

		$this->show('brand/footer-order', $data);
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



	/**
	 * Vérifier si les valeurs saisies sont unique et va les enregistrer
	 *
	 * @return void
	 */
	public function editFooterOrder(): void
	{
		// On vérifie si les type choisies sont uniques
		if( !$this->valuesAreUnique($_POST['emplacements'])) {
			$this->addFlashErrorMessage('Il faut sémectionner des marques uniques');
			$this->redirect('brand-footer-order');
		}

		try {
			// 1. reset de tous les footer order
			Brand::resetFooterOrder();

			// 2. Parcours des POST['emplacements'] = [index du tableau => id du type]
			foreach ($_POST['emplacements'] as $i => $idBrand) {
				// Cherche la marque à modifier
				$brand = Brand::find($idBrand);
				// Change le home order de la marque
				$brand->setFooter_order($i + 1);
				// Mise à jour de la marque
				$brand->save();
			}

			$this->addFlashNotification('Ordre des marques modifiées');
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}

		// Redirect After Post
		$this->redirect('brand-footer-order');
	}
}