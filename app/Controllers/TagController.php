<?php

namespace App\Controllers;

use App\Models\Tag;
use Exception;

class TagController extends CrudController
{
	private function getDataFromPost(Tag $tag)
	{
		// Est-ce que les données en POST sont correct ?
		// Si une donnée est invalide, $dataAreValid = false;
		// À la fin de la fonction, si $dataAreValid est faux, on lance une exception
		$dataAreValid = true;

		// $name contient le nom du tag filtré
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		// On essaye de stocker le nom
		// Si ça revoie false, le nom est vide, 
		// Il n'est pas enregistré
		if (false === $tag->setName($name)) {
			// le nom du tag est vide
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

	private function showForm(string $title, Tag $tag): void {
	
		$data = [];
		$data['title'] = $title;
		$data['tag'] = $tag;
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('tag/add-update', $data);
	}

	/**
	 * Action de lister les tags sur la page /tag/list
	 *
	 * @return void retour rien : pas de return
	 */
	public function list(): void
	{
		$data['tagList'] = Tag::findAll();
		$data['tokenCSRF'] = $this->generateTokenCSRF();

		$this->show('tag/list', $data);
	}

	/**
	 * Action pour présenter le formulaire d'ajout d'un tag
	 *
	 * @return void
	 */
	public function add(): void
	{
		$this->showForm('Ajouter un tag', new Tag());
	}

	/**
	 * Action pour enregistrer un nouveau tag
	 *
	 * @return void
	 */
	public function create(): void
	{
		// 1. nouvelle instance de tag
		$tag = new Tag();

		try {

			// 2. récupère les données en POST
			$this->getDataFromPost($tag);

			// 3. enregistre les valeurs de l'objet dans la BDD
			$tag->insert();

			// Un petit message de confirmation
			$this->addFlashNotification("Nouveau tag enregistré : " . $tag->getName());

			// Redirect After Post
			$this->redirect('tag-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur est survenue
			$this->addFlashErrorMessage($exception->getMessage());
			
			// On affiche le formulaire avec les infos saisies
			$this->showForm('Ajouter un tag', $tag);
		}
	}

	/**
	 * Action de présenter le formulaire pré-rempli pour éditer un tag
	 *
	 * @return void
	 */
	public function edit($tagId): void
	{
		try {
			$tag = Tag::find($tagId);

			if (null === $tag) {
				// Si le tag n'existe pas
				$this->addFlashErrorMessage("Le tag n'existe plus");
				// Redirige vers le formulaire d'ajout d'un tag
				// Le script s'arrête ici
				$this->redirect('tag-list');
			}
	
			// Le tag existe, on continue le script normalement
			// On affiche le formulaire avec les infos saisies
			$this->showForm('Modifiier le tag ' . $tag->getName(), $tag);
			
		} catch (Exception $exception) {
			// Une erreur est survenue
			$this->addFlashErrorMessage($exception->getMessage());

			$this->redirect('tag-list');
		}

	}

	/**
	 * Action de mettre à jour un tag
	 *
	 * @param [tag] $tagId l'identifiant du tag à mettre à jour
	 * @return void
	 */
	public function update($tagId): void
	{
		// Essaye de faire la mise à jour d'un tag
		try {
			// Recherche dans la BDD les infos du tag qui doit etre modifié
			$tag = Tag::find($tagId);

			if (null === $tag) {
				// Le tag n'existe plus
				// Pas la peine de continuer,

				// $tag est null
				// je mets une nouvelle instance de tag dans la vairable
				// elle sera utilisée dans le catch 
				$tag = new Tag();
				
				// Il suffit de lancer une exception
				// Elle sera capturée dans le catch juste en dessous
				throw new Exception("Le tag à modifier n'existe plus");
			}

			// Essaye de récupérer les données du formulaire			
			$this->getDataFromPost($tag);

			// Les données du formulaire sont valides
			// Je peux mettre à jour le tag

			// J'essaye de faire la maj de la tag
			$tag->update();

			$this->addFlashNotification('Le tag ' . $tag->getName() . ' a bien été modifié');

			// Redirect After Post
			$this->redirect('tag-list');

			// Catch : gestion des erreurs
		} catch (Exception $exception) {
			// Une erreur s'est déroulée durant la maj
			// J'attrape l'exception qui a été lancée
			// J'enregistre le message d'erreur dans la variable de session ErrorMessages
			$this->addFlashErrorMessage($exception->getMessage());

			// On affiche le formulaire avec la catégorie à modifier
			$this->showForm('Modifiier un tag', $tag);
		}
	}

	/**
	 * Action de supprimer un tag
	 *
	 * @param [tag] $tagId l'identifiant du tag à supprimer
	 * @return void
	 */
	public function delete(int $id): void {
		try {
			$tag = Tag::find($id);

			$tag->delete();

			$this->addFlashNotification('Tag supprimé');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}
		
		// Redirect After Post
		$this->redirect('tag-list');
	}
}
