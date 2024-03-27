<?php

namespace App\Controllers;

use App\Models\AppUser;
use Exception;

/**
 * Controller pour gérer les authentifications : login / logout
 */
class SessionController extends CoreController
{

	/**
	 * Action présenter la page de connexion
	 *
	 * @return void
	 */
	public function login()
	{
		$this->show('session/login');
	}

	/**
	 * Action de login d'un utilisateur
	 *
	 * @return void
	 */
	public function authenticate()
	{
		// $email contient l'email du formulaire ou false si email n'est pas au format email
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');

		// Est-ce que $email est faux
		// $email n'est pas au format email
		if (false === $email) {
			$this->addFlashErrorMessage('Email invalide');
			$this->redirect('session-login');
		}

		// Est-ce que le mot de passe est vide ?
		if (empty($password)) {
			$this->addFlashErrorMessage('Password invalide');
			$this->redirect('session-login');
		}

		// Recherche l'utilisateur dans la BDD selon son email
		try {

			$user = AppUser::findByEmail($email);

			// Compare le mot de passe du formulaire
			// Avec celui de la BDD
			// getPassword retour le hash du mot de passe stocké en BDD
			// password_verify compare le mot de passe du formulaire de login
			// avec le hash qui est en bdd
			if (password_verify($password, $user->getPassword())) {
				// Les mots de passe correspondent
				// l'utilisateur est authentifié
				// J'enregistre l'utilisateur en sessions
				$_SESSION['User'] = $user;
				$_SESSION['Connected'] = true;
				$this->addFlashNotification('Connexion réussie');
				// Redirect After Post
				$this->redirect('main-home');
			} else {
				// Le mot de passe n'est pas bon
				throw new Exception('Echec');
			}

			// Exception l'utilisateur n'existe pas dans la BDD
		} catch (Exception $exception) {
			$this->addFlashErrorMessage('Echec de connexion');
			$this->redirect('session-login');
		}
	}

	public function logout()
	{
		// Je remet à null la variable de session
		$_SESSION['User'] = null;
		$_SESSION['Connected'] = false;

		$this->addFlashNotification('Déconnexion réussie');
		$this->redirect('session-login');
	}
}
