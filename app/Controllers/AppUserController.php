<?php

namespace App\Controllers;

use App\Models\AppUser;
use Exception;

class AppUserController extends CrudController
{

	/**
	 * Récupère et vérifie les données saisie en POST
	 * Si les données sont valides, elles sont enregistrées dans $this->category
	 *
	 * @return boolean vrai si les données sont valides, faux sinon
	 */
	private function getDataFromPost(AppUser $myUser): void
	{
		// Récupérer et netoyer les donnes en POST
		$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
		$role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

		// /* === Vérifier les données saisies === */
		$dataAreValid = true;
		// First name
		$myUser->setFirstname($firstname);
		// Last name
		$myUser->setLastname($lastname);

		// Email
		if (false == $email) {
			$dataAreValid = false;
			$this->addFlashErrorMessage('Email invalide');
		} else {
			$myUser->setEmail($email);
		}

		// Password
		if (!$myUser->setPassword($password)) {
			$dataAreValid = false;
			$this->addFlashErrorMessage('Mot de passe invalide, au moins 8 caractère et 1 chiffre et 1 lettre');
		}

		// Role
		if (!$myUser->setRole($role)) {
			$dataAreValid = false;
			$this->addFlashErrorMessage('Role invalide');
		}

		// Status
		if (false == $status || !$myUser->setStatus($status)) {
			$dataAreValid = false;
			$this->addFlashErrorMessage('Status invalide');
		}

		if (!$dataAreValid) {
			throw new Exception('Données invalides');
		}
	}

	/**
	 * Affiche le formulaire d'ajout / modification d'un utilisateur
	 *
	 * @param string $title titre du formulaire
	 * @param AppUser $user instance de AppUser à afficher dans le formulaire
	 * @return void
	 */
	private function showForm(string $title, AppUser $user)
	{

		$data = [
			'title' => $title,
			'user' => $user,
			'tokenCSRF' => $this->generateTokenCSRF(),
		];

		$this->show('app-user/add-update', $data);
	}

	public function list(): void
	{
		$data = [
			'userList' => AppUser::findAll(),
			'tokenCSRF' => $this->generateTokenCSRF(),
		];

		$this->show('app-user/list', $data);
	}

	public function add(): void
	{
		$this->showForm('Ajouter un utilisateur', new AppUser());
	}

	public function create(): void
	{
		$user = new AppUser();

		try {
			$this->getDataFromPost($user);

			$user->save();

			$this->addFlashNotification('Utilisateur enregistré');

			// redirect After Post
			$this->redirect('user-list');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
			$this->showForm('Erreur - ajouter un utilisateur', $user);
		}
	}

	public function edit(int $id): void
	{
		try {
			$user = AppUser::find($id);

			$this->showForm("Modification de " . $user->getFirstname(), $user);

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
			$this->redirect('user-list');
		}
	}

	public function update(int $id): void
	{
		$user = new AppUser();

		try {
			$user = AppUser::find($id);

			$this->getDataFromPost($user);

			$user->save();

			$this->addFlashNotification('Utilisateur enregistré');

			// Redirect After Post
			$this->redirect('user-list');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
			$this->showForm('Erreur - modifier un utilisateur', $user);
		}
	}

	public function delete(int $id): void
	{
		try {
			$user = AppUser::find($id);

			$user->delete();

			$this->addFlashNotification('Utilisateur supprimé');

			// Catch traite de l'exception
		} catch (Exception $exception) {
			$this->addFlashErrorMessage($exception->getMessage());
		}
		
		// Redirect After Post
		$this->redirect('user-list');
	}
}
