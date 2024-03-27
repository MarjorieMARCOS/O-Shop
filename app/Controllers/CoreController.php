<?php

namespace App\Controllers;

use AltoRouter;

class CoreController
{

	/**
	 * Tableau des ACL de oShop
	 * 
	 * Le tableau est une constante, une fois défini, je ne peux plus le modifier.
	 * L'idéal serait d'écrire ce tableau d'ACL dans un fichier de configuration.
	 * 
	 * Les clés du tableau sont les noms de toutes les routes possibles dans oShop
	 * Les valeur du tableau sont les roles qui ont le droit d'accéder à ces routes
	 */
	private const ACL = [
		// Category
		'category-list' => ['admin', 'catalog-manager'],
		'category-add' => ['admin', 'catalog-manager'],
		'category-create' => ['admin', 'catalog-manager'],
		'category-edit' => ['admin', 'catalog-manager'],
		'category-update' => ['admin', 'catalog-manager'],
		'category-delete' => ['admin', 'catalog-manager'],
		'category-home-order' => ['admin', 'catalog-manager'],
		'category-edit-home-order' => ['admin', 'catalog-manager'],
		// User
		'user-list' => ['admin'],
		'user-add' => ['admin'],
		'user-create' => ['admin'],
		'user-edit' => ['admin'],
		'user-update' => ['admin'],
		'user-delete' => ['admin'],
		// Product
		'product-list' => ['admin', 'catalog-manager'],
		'product-add' => ['admin', 'catalog-manager'],
		'product-create' => ['admin', 'catalog-manager'],
		'product-edit' => ['admin', 'catalog-manager'],
		'product-update' => ['admin', 'catalog-manager'],
		'product-delete' => ['admin', 'catalog-manager'],
		// Type
		'type-list' => ['admin', 'catalog-manager'],
		'type-add' => ['admin', 'catalog-manager'],
		'type-create' => ['admin', 'catalog-manager'],
		'type-edit' => ['admin', 'catalog-manager'],
		'type-update' => ['admin', 'catalog-manager'],
		'type-delete' => ['admin', 'catalog-manager'],
		'type-footer-order' => ['admin', 'catalog-manager'],
		'type-edit-footer-order' => ['admin', 'catalog-manager'],
		// Brand
		'brand-list' => ['admin', 'catalog-manager'],
		'brand-add' => ['admin', 'catalog-manager'],
		'brand-create' => ['admin', 'catalog-manager'],
		'brand-edit' => ['admin', 'catalog-manager'],
		'brand-update' => ['admin', 'catalog-manager'],
		'brand-delete' => ['admin', 'catalog-manager'],
	];

	/**
	 * Liste des routes qui doivent etre protégées par un token en POST
	 */
	private const TOKEN_CSRF_POST = [
		// category
		'category-create',
		'category-update',
		'category-edit-home-order',
		// product
		'product-create',
		'product-update',
		// user 
		'user-create',
		'user-update',
		// brand
		'brand-create',
		'brand-update',
		// type
		'type-create',
		'type-update',
		'type-edit-footer-order',
	];

	/**
	 * Liste des routes qui doivent etre protégées par un token en GET
	 */
	private const TOKEN_CSRF_GET = [
		'category-delete',
		'user-delete',
		'product-delete',
		'brand-delete',
		'type-delete',
	];

	/**
	 * Instance d'AltRouter
	 *
	 * @var AltoRouter
	 */
	private AltoRouter $router;

	/**
	 * Constructeur du CoreController.
	 * Cette fonction est appellée pour toute les pages visitée.
	 * Elle vérifie : 
	 * 1. est que l'utilisateur est bien connecté ET a les bons droits pour accéder à la page
	 * 2. si la page doit etre protégée par un token, la fonction vérifie la valeur du token
	 *
	 * @param [type] $routeName route demandée par le visiteur
	 * @param [type] $altoRouter instance d'AltoRouter
	 */
	public function __construct($routeName, $altoRouter)
	{
		$this->router = $altoRouter;

		// Appel checkAuthorisation pour vérifier si l'utilisateur
		// a le droit d'accéder à la page grace aux ACL
		$this->checkAuthorisation($routeName);

		// vérifier le token CSRF
		if (in_array($routeName, self::TOKEN_CSRF_POST)) {
			$this->checkTokenCSRF($_POST['tokenCSRF'] ?? '');
		} else {
			if (in_array($routeName, self::TOKEN_CSRF_GET)) {
				$this->checkTokenCSRF($_GET['tokenCSRF'] ?? '');
			}
		}
	}

	/**
	 * Génère un token CSRF, l'enregistre dans une variable de session puis retourne sa valeur
	 *
	 * @return string le token CSRF mis en session
	 */
	protected function generateTokenCSRF(): string
	{
		$tokenCSRF = bin2hex(random_bytes(32)); // génère un nombre aléatoire de 32 caractere
		$_SESSION['tokenCSRF'] = $tokenCSRF;
		return $tokenCSRF;
	}

	/**
	 * Compare le token passé en argument avec le token en sessoin
	 *
	 * @param string $tokenCSRF token à comparer avec celui en session
	 * @return boolean true si les token correspondent
	 */
	private function checkTokenCSRF(string $tokenCSRF): bool
	{
		// Est-ce que le token qui est en session correspond à $tokenCSRF
		if (isset($_SESSION['tokenCSRF']) && $tokenCSRF === $_SESSION['tokenCSRF']) {
			// Oui les token correspondent
			// 1. supprime le token en session
			unset($_SESSION['tokenCSRF']);
			// 2. return true
			return true;
		}

		// supprime le token en session
		unset($_SESSION['tokenCSRF']);

		$this->addFlashErrorMessage('Action interdite');
		// Le mieux serait de rediriger vers une page d'erreur
		$this->redirect('main-home');
	}

	/**
	 * Vérifier : est-ce que l'utilisateur est connecté ? ET
	 * Est-ce que l'utilisateur a le bon role pour l'action demaindée grace au tableau d'ACL ?
	 *
	 * @return void
	 */
	private function checkAuthorisation(string $requestedRoute)
	{


		// 1. Est-ce que la route demandée par le visiteur est dans le tableau des ACL ?
		// Est-ce que la route est protégée par les ACL ?
		// pour accéder aux constantes en PHP il faut utiliser self::
		if (array_key_exists($requestedRoute, self::ACL)) {

			// Est-ce que l'utilisateur est connecté ?
			if (true === $_SESSION['Connected']) {

				// L'utisateur est bien connecté
				// On récupère le role de l'utilisateur
				$user = $_SESSION['User'];
				$userRole = $user->getRole();

				// La route demandée est dans le tableau des ACL
				// Est-ce que le role de l'utilisateur est dans le tableau des ACL ?			
				if (!in_array($userRole, self::ACL[$requestedRoute])) {
					// Si le role de l'utilisateur N'EST PAS dans la liste des roles possibles
					// On redirige vers la page d'accueil
					$this->addFlashErrorMessage('Acces interdit');
					$this->redirect('main-home');
				}
			} else {
				$this->addFlashErrorMessage('Acces interdit');
				$this->redirect('session-login');
			}
		}
	}

	/**
	 * Fonction de redirection vers une nouvelle page
	 * Le script s'arrete après la redirection 
	 *
	 * @param string $routeName nom de la route (AltoRouter) où rediriger le visiteur
	 * @return void
	 */
	protected function redirect(string $routeName): void
	{
		// Opère la redirection
		header('Location: ' . $this->router->generate($routeName));
		// Termine le script PHP
		exit();
	}

	/**
	 * Enregistre en session flash message
	 *
	 * @param string $message à afficher
	 * @return void
	 */
	protected function addFlashNotification(string $message)
	{
		array_push($_SESSION['Notifications'], $message);
	}

	/**
	 * Enregistre en session flash message d'erreur
	 *
	 * @param string $message à afficher
	 * @return void
	 */
	protected function addFlashErrorMessage(string $message)
	{
		array_push($_SESSION['ErrorMessages'], $message);
	}

	/**
	 * Méthode permettant d'afficher du code HTML en se basant sur les views
	 *
	 * @param string $viewName Nom du fichier de vue
	 * @param array $viewData Tableau des données à transmettre aux vues
	 * @return void
	 */
	protected function show(string $viewName, $viewData = [])
	{
		// On globalise $router car on ne sait pas faire mieux pour l'instant
		$router = $this->router;

		// Comme $viewData est déclarée comme paramètre de la méthode show()
		// les vues y ont accès
		// ici une valeur dont on a besoin sur TOUTES les vues
		// donc on la définit dans show()
		$viewData['currentPage'] = $viewName;

		// définir l'url absolue pour nos assets
		$viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
		// définir l'url absolue pour la racine du site
		// /!\ != racine projet, ici on parle du répertoire public/
		$viewData['baseUri'] = $_SERVER['BASE_URI'];

		// On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
		// La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
		extract($viewData);
		// => la variable $currentPage existe désormais, et sa valeur est $viewName
		// => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
		// => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
		// => il en va de même pour chaque élément du tableau

		// $viewData est disponible dans chaque fichier de vue
		require_once __DIR__ . '/../views/layout/header.tpl.php';
		require_once __DIR__ . '/../views/' . $viewName . '.tpl.php';
		require_once __DIR__ . '/../views/layout/footer.tpl.php';
	}
}
