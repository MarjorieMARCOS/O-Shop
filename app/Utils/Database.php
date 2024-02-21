<?php

namespace App\Utils;
use \PDO;

/**
 * Classe technique de connection à la BDD.
 * Cette classe récupère les informations de connection dans le fichier /app/config.ini.
 * 
 * Configuration attendue (penser à retirer les "<" ">" !): 
 * DB_HOST=<nom de la machine qui héberge la BDD, souvent c'est "localhost"> 
 * DB_USERNAME=<nom de l'utilisateur pour se connecter à la BDD>
 * DB_PASSWORD=<mot de passe pour se connecter à la BDD>
 * DB_NAME=<nom de la BDD à utiliser>
 * 
 * Cette classe met en œuvre le design pattern Singleton : https://fr.wikipedia.org/wiki/Singleton_(patron_de_conception)
 * Le code est un peu compliqué, on n'a pas encore vu comment utiliser cette classe.
 * Pour se connecter à la BDD, voir la class DBConnection ci-dessus et sa fonction getConnection()
 */
class Database
{
	/** @var PDO */
	private $dbh;

	/** @var Database 
	 * L'unique instance de la classe Database. Valeur null par défaut. 
	 * Cette variable est static. On n'a pas encore vu à quoi sert ce mot clé...
	 * Ça viendra en saison 06 ;)
	 */
	private static $_instance = null;

	private function __construct()
	{
		// Récupération des données du fichier de config.ini
		// la fonction parse_ini_file analyse le fichier et retourne un array associatif
		// parse_ini_file() : https://www.php.net/manual/en/function.parse-ini-file.php
		// avec les informations de connexions inscrites dans le fichier
		$configData = parse_ini_file(__DIR__ . '/../config.ini');

		// try : un mot clé que vous verrez lors des prochaines saisons, 
		// promis on vous expliquera tout ;)
		try {
			// Tentative de connexion à la BDD
			$this->dbh = new PDO(
				"mysql:host={$configData['DB_HOST']};dbname={$configData['DB_NAME']};charset=utf8",
				$configData['DB_USERNAME'],
				$configData['DB_PASSWORD'],

				// Configuration pour afficher les erreurs SQL à l'écran
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
			); 
		} catch (\Exception $exception) {
			// La connexion à la BDD a échoué :/
			// On affiche les messages d'erreur
			echo 'Erreur de connexion...<br>';
			echo $exception->getMessage() . '<br>';
			echo '<pre>';
			echo $exception->getTraceAsString();
			echo '</pre>';
			exit;
		}
	}

	/**
	 * Fonction qui renvoie l'unique connexion à la BDD
	 * @see Design Pattern Singleton : https://fr.wikipedia.org/wiki/Singleton_(patron_de_conception)
	 *
	 * @return PDO la connexion à la BDD
	 */
	public static function getPDO()
	{
		// Si l'instance unique de Database n'existe pas (est null),
		// créer une nouvelle instance
		if (null === self::$_instance) {
			self::$_instance = new Database();
		}

		// Retour l'attribut $dbh de Database
		return self::$_instance->dbh;
	}
}
