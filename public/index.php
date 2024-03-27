<?php

// POINT D'ENTRÉE UNIQUE :
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)

use App\Controllers\AppUserController;
use App\Controllers\BrandController;
use App\Controllers\CategoryController;
use App\Controllers\MainController;
use App\Controllers\ProductController;
use App\Controllers\SessionController;
use App\Controllers\TagController;
use App\Controllers\TypeController;

require_once '../vendor/autoload.php';

// Je demarre la session
// A placer en première instruction juste après les require
session_start();

// Initialise une variable de session 'nbVisite' égale à 0 si la variable n'est pas déjà initialisée;
// Pour une raison que j'ignore encore, parfois la variable de session prend la valeur 0 (false), dans ce cas il faut réinitialiser le tableau
if( !isset($_SESSION['ErrorMessages']) || false == $_SESSION['ErrorMessages'] ) {
	$_SESSION['ErrorMessages'] = [];
}

// Pour une raison que j'ignore encore, parfois la variable de session prend la valeur 0 (false), dans ce cas il faut réinitialiser le tableau
if( !isset($_SESSION['Notifications']) || false == $_SESSION['Notifications'] ) {
	$_SESSION['Notifications'] = [];
}

if( !isset($_SESSION['User']) ) {
	$_SESSION['User'] = null;
	$_SESSION['Connected'] = false;
}

/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
	// Alors on définit le basePath d'AltoRouter
	$router->setBasePath($_SERVER['BASE_URI']);
	// ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
} else { // sinon
	// On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
	$_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter,
// afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"
$router->map(
	'GET',
	'/',
	[
		'method' => 'home',
		'controller' => MainController::class // On indique le FQCN de la classe
	],
	'main-home'
);

/* ---------------
--- CATEGORIES ---
------------------*/

$router->map(
	'GET',
	'/category/list',
	[
		'method' => 'list',
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-list'
);

$router->map(
	'GET',
	'/category/add',
	[
		'method' => 'add',
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-add'
);

$router->map(
	'POST', // Pour atteindre cette route il faut passer par le formulaire et le bouton valider
	'/category/add',
	[
		'method' => 'create',
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-create'
);

$router->map(
	'GET',
	'/category/update/[i:id]',
	[
		'method' => 'edit', // Action présenter le formulaire pré-rempli
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-edit'
);

$router->map(
	'POST', // Pour atteindre cette route il faut passer par le formulaire et le bouton valider
	'/category/update/[i:id]',
	[
		'method' => 'update', // Action enregistrer la maj de la catégorie
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-update'
);

$router->map(
	'GET',
	'/category/delete/[i:id]',
	[
		'controller' => CategoryController::class, // On indique le FQCN de la classe
		'method' => 'delete'
	],
	'category-delete'
);

$router->map(
	'GET',
	'/category/home-order',
	[
		'method' => 'homeOrder',
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-home-order'
);

$router->map(
	'POST',
	'/category/home-order',
	[
		'method' => 'editHomeOrder',
		'controller' => CategoryController::class // On indique le FQCN de la classe
	],
	'category-edit-home-order'
);

/* ---------------
---- PRODUCTS ----
------------------*/

$router->map(
	'GET',
	'/product/list',
	[
		'method' => 'list',
		'controller' => ProductController::class // On indique le FQCN de la classe
	],
	'product-list'
);

$router->map(
	'GET',
	'/product/add',
	[
		'method' => 'add',
		'controller' => ProductController::class // On indique le FQCN de la classe
	],
	'product-add'
);

$router->map(
	'POST',
	'/product/add',
	[
		'method' => 'create',
		'controller' => ProductController::class // On indique le FQCN de la classe
	],
	'product-create'
);

$router->map(
	'GET',
	'/product/update/[i:id]',
	[
		'method' => 'edit', // Action présenter le formulaire pré-rempli
		'controller' => ProductController::class // On indique le FQCN de la classe
	],
	'product-edit'
);

$router->map(
	'POST', // Pour atteindre cette route il faut passer par le formulaire et le bouton valider
	'/product/update/[i:id]',
	[
		'method' => 'update', // Action enregistrer la maj de la catégorie
		'controller' => ProductController::class // On indique le FQCN de la classe
	],
	'product-update'
);

$router->map(
	'GET',
	'/product/delete/[i:id]',
	[
		'controller' => ProductController::class, // On indique le FQCN de la classe
		'method' => 'delete'
	],
	'product-delete'
);

/* ---------------
------ TYPE ------
------------------*/

$router->map(
	'GET',
	'/type/list',
	[
		'method' => 'list',
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-list'
);

$router->map(
	'GET',
	'/type/add',
	[
		'method' => 'add',
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-add'
);

$router->map(
	'POST',
	'/type/add',
	[
		'method' => 'create',
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-create'
);

$router->map(
	'GET',
	'/type/update/[i:id]',
	[
		'method' => 'edit', // Action présenter le formulaire pré-rempli
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-edit'
);

$router->map(
	'POST', // Pour atteindre cette route il faut passer par le formulaire et le bouton valider
	'/type/update/[i:id]',
	[
		'method' => 'update', // Action enregistrer la maj de la catégorie
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-update'
);

$router->map(
	'GET',
	'/type/delete/[i:id]',
	[
		'controller' => TypeController::class, // On indique le FQCN de la classe
		'method' => 'delete'
	],
	'type-delete'
);

$router->map(
	'GET',
	'/type/footer-order',
	[
		'method' => 'footerOrder',
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-footer-order'
);

$router->map(
	'POST',
	'/type/footer-order',
	[
		'method' => 'editFooterOrder',
		'controller' => TypeController::class // On indique le FQCN de la classe
	],
	'type-edit-footer-order'
);


/* ---------------
------ USERS -----
------------------*/

$router->map(
	'GET', 
	'/user/list',
	[
		'method' => 'list', // Action enregistrer la maj de la catégorie
		'controller' => AppUserController::class // On indique le FQCN de la classe
	],
	'user-list'
);

$router->map(
	'GET',
	'/user/add',
	[
		'method' => 'add',
		'controller' => AppUserController::class // On indique le FQCN de la classe
	],
	'user-add'
);

$router->map(
	'POST',
	'/user/add',
	[
		'method' => 'create',
		'controller' => AppUserController::class // On indique le FQCN de la classe
	],
	'user-create'
);

$router->map(
	'GET',
	'/user/update/[i:id]',
	[
		'method' => 'edit', // Action présenter le formulaire pré-rempli
		'controller' => AppUserController::class // On indique le FQCN de la classe
	],
	'user-edit'
);

$router->map(
	'POST', // Pour atteindre cette route il faut passer par le formulaire et le bouton valider
	'/user/update/[i:id]',
	[
		'method' => 'update', // Action enregistrer la maj de la catégorie
		'controller' => AppUserController::class // On indique le FQCN de la classe
	],
	'user-update'
);

$router->map(
	'GET',
	'/user/delete/[i:id]',
	[
		'method' => 'delete',
		'controller' => AppUserController::class, // On indique le FQCN de la classe
	],
	'user-delete'
);

/* ---------------
---- SESSIONS ----
------------------*/

$router->map(
	'GET', 
	'/session/login',
	[
		'method' => 'login', // Action enregistrer la maj de la catégorie
		'controller' => SessionController::class // On indique le FQCN de la classe
	],
	'session-login'
);

$router->map(
	'POST', 
	'/session/login',
	[
		'method' => 'authenticate', // Action enregistrer la maj de la catégorie
		'controller' => SessionController::class // On indique le FQCN de la classe
	],
	'session-authenticate'
);

$router->map(
	'GET', 
	'/session/logout',
	[
		'method' => 'logout', // Action enregistrer la maj de la catégorie
		'controller' => SessionController::class // On indique le FQCN de la classe
	],
	'session-logout'
);


/* ---------------
------ TAG -------
----------------*/

$router->map(
	'GET',
	'/tag/list',
	[
		'method' => 'list',
		'controller' => TagController::class // On indique le FQCN de la classe
	],
	'tag-list'
);

$router->map(
	'GET',
	'/tag/add',
	[
		'method' => 'add',
		'controller' => TagController::class // On indique le FQCN de la classe
	],
	'tag-add'
);

$router->map(
	'POST',
	'/tag/add',
	[
		'method' => 'create',
		'controller' => TagController::class // On indique le FQCN de la classe
	],
	'tag-create'
);

$router->map(
	'GET',
	'/tag/update/[i:id]',
	[
		'method' => 'edit',
		'controller' => TagController::class
	],
	'tag-edit'
);

$router->map(
	'POST',
	'/tag/update/[i:id]',
	[
		'method' => 'update',
		'controller' => TagController::class
	],
	'tag-update'
);

$router->map(
	'GET',
	'/tag/delete/[i:id]',
	[
		'controller' => TagController::class, // On indique le FQCN de la classe
		'method' => 'delete'
	],
	'tag-delete'
);

/* ---------------
----- BRANDS -----
----------------*/

$router->map(
	'GET',
	'/brand/list',
	[
		'method' => 'list',
		'controller' => BrandController::class // On indique le FQCN de la classe
	],
	'brand-list'
);

$router->map(
	'GET',
	'/brand/add',
	[
		'method' => 'add',
		'controller' => BrandController::class // On indique le FQCN de la classe
	],
	'brand-add'
);

$router->map(
	'POST',
	'/brand/add',
	[
		'method' => 'create',
		'controller' => BrandController::class // On indique le FQCN de la classe
	],
	'brand-create'
);

$router->map(
	'GET',
	'/brand/update/[i:id]',
	[
		'method' => 'edit',
		'controller' => BrandController::class
	],
	'brand-edit'
);

$router->map(
	'POST',
	'/brand/update/[i:id]',
	[
		'method' => 'update',
		'controller' => BrandController::class
	],
	'brand-update'
);

$router->map(
	'GET',
	'/brand/delete/[i:id]',
	[
		'controller' => BrandController::class, // On indique le FQCN de la classe
		'method' => 'delete'
	],
	'brand-delete'
);

$router->map(
	'GET',
	'/brand/footer-order',
	[
		'method' => 'footerOrder',
		'controller' => BrandController::class // On indique le FQCN de la classe
	],
	'brand-footer-order'
);

$router->map(
	'POST',
	'/brand/footer-order',
	[
		'method' => 'editFooterOrder',
		'controller' => BrandController::class // On indique le FQCN de la classe
	],
	'brand-edit-footer-order'
);

/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

// On injecte dans le constructeur du CoreController
// Le nom de la route demandée
// ET notre instance d'AltoRouter
if(false === $match) {
	$dispatcher->setControllersArguments('', $router);
} else {
	$dispatcher->setControllersArguments($match['name'], $router);
}

// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();
