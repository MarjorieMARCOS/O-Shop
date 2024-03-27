<?php

namespace App\Controllers;
use App\Models\Type;
use Exception;

class TypeController extends CrudController
{
	/**
	 * Réucpère puis filter les données en POST et les enregistre dans $type
	 *
	 * @param Type $type le type dans lequel on stocke les données du formulaire
	 * @throws Exception si des données sont invalides
	 */
	private function getDataFromPost(Type $type)
	{
		// Est-ce que les données en POST sont correct ?
		// Si une donnée est invalide, $dataAreValid = false;
		// À la fin de la fonction, si $dataAreValid est faux, on lance une exception
		$dataAreValid = true;

		// $name contient le nom du type filtré
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		// On essaye de stocker le nom
		// Si ça revoie false, le nom est vide, 
		// Il n'est pas enregistré
		if (false === $type->setName($name)) {
			// le nom du type est vide
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
	 * Appel la fonction show pour afficher le formulaire d'édition d'un type.
	 *
	 * @param string $title titre du formulaire
	 * @param Type $type le type à afficher dans le formulaire
	 * @return void
	 */
	private function showForm(string $title, Type $type): void
	{
		$data = [];
		$data['title'] = $title;
		$data['type'] = $type;
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('type/add-update', $data);
	}


	/**
	 * Action de lister les types sur la page /type/list
	 *
	 * @return void retour rien : pas de return
	 */
	public function list(): void
	{
		$data['typeList'] = Type::findAll();
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('type/list', $data);
	}

	/**
	 * Action pour présenter le formulaire d'ajout d'un type
	 *
	 * @return void
	 */
	public function add(): void
	{
		$this->showForm('Ajouter un type', new Type());
	}

	/**
	 * Action pour enregistrer un nouveau type
	 *
	 * @return void
	 */
	public function create(): void
	{
		// 1. nouvelle instance de type
		$type = new Type();

		try {
			// 2. récupère les données en POST
			$this->getDataFromPost($type);

			// 3. enregistre les valeurs de l'objet dans la BDD
			$type->insert();

			// Un petit message de confirmation
			$this->addFlashNotification("Nouveau type enregistrée : " . $type->getName());

			// Redirect After Post
			$this->redirect('type-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur est survenue
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec les infos saisies
			$this->showForm('Ajouter un type', $type);
		}
	}

	/**
	 * Action de présenter le formulaire pré-rempli pour éditer un type
	 *
	 * @return void
	 */
	public function edit($typeId): void
	{
		try {
			$type = Type::find($typeId);

			if (null === $type) {
				// Si la type n'existe pas
				$this->addFlashErrorMessage("Le type n'existe plus");
				// Redirige vers le formulaire d'ajout d'un type
				// Le script s'arrête ici
				$this->redirect('type-list');
			}
	
			// Le type existe, on continue le script normalement
			// On affiche le formulaire avec les infos saisies
			$this->showForm('Modifiier le type ' . $type->getName(), $type);
			
		} catch (Exception $exception) {
			// Une erreur est survenue
			$this->addFlashErrorMessage($exception->getMessage());

			$this->redirect('type-list');
		}
	
	}


	/**
	 * Action de mettre à jour 1 type
	 *
	 * @param [type] $typeId l'identifiant du type à mettre à jour
	 * @return void
	 */
	public function update($typeId): void
	{
		// Essaye de faire la mise à jour d'un type
		try {
			// Recherche dans la BDD les infos d'un type qui doit etre modifié
			$type = Type::find($typeId);

			if (null === $type) {
				// Le type n'existe plus
				// Pas la peine de continuer,

				// $type est null
				// je mets une nouvelle instance de type dans la vairable
				// elle sera utilisée dans le catch 
				$type = new Type();

				// Il suffit de lancer une exception
				// Elle sera capturée dans le catch juste en dessous
				throw new Exception("Le type à modifier n'existe plus");
			}

			// Essaye de récupérer les données du formulaire			
			$this->getDataFromPost($type);

			// Les données du formulaire sont valides
			// Je peux mettre à jour le type
			$type->update();

			$this->addFlashNotification('Le type ' . $type->getName() . ' a bien été modifié');

			// Redirect After Post
			$this->redirect('type-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec le type à modifier
			$this->showForm('Modifiier un type', $type);
		}
	}

	/**
	 * Action de supprimer un type
	 *
	 * @param [type] $typeId l'identifiant du type à supprimer
	 * @return void
	 */
	public function delete(int $id): void
	{
		try {
			$type = Type::find($id);

			if (null === $type) {
				// Le type n'existe plus
				// Pas la peine de continuer,

				// $type est null
				// je mets une nouvelle instance de type dans la vairable
				// elle sera utilisée dans le catch 
				;

				// Il suffit de lancer une exception
				// Elle sera capturée dans le catch juste en dessous
				throw new Exception("Le type à modifier n'existe plus");
			}
			
			$type->delete();

			$this->addFlashNotification('Type supprimé');

			// Redirect After Post
			$this->redirect('type-list');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());

			// Redirect After Post
			$this->redirect('type-list');
		}

	}


	/**
	 * Va affichier la page footer-order en liste les footer_order des types
	 *
	 * @return void
	 */
	public function footerOrder(): void
	{
		$typeList = Type::findAll();
		
		$data = [
			'typeList' => $typeList,
			'tokenCSRF' => $this->generateTokenCSRF(),
		];

		$this->show('type/footer-order', $data);
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
			$this->addFlashErrorMessage('Il faut sémectionner des types uniques');
			$this->redirect('type-footer-order');
		}

		try {
			// 1. reset de tous les footer order
			Type::resetFooterOrder();

			// 2. Parcours des POST['emplacements'] = [index du tableau => id du type]
			foreach ($_POST['emplacements'] as $i => $idtype) {
				// Cherche le type à modifier
				$type = Type::find($idtype);

				// Change le home order du type
				$type->setFooter_order($i + 1);
				// Mise à jour du type
				$type->save();
			}

			$this->addFlashNotification('Ordre des types modifié');
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}

		// Redirect After Post
		$this->redirect('type-footer-order');
	}
}
