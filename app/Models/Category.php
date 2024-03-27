<?php

namespace App\Models;

use App\Utils\Database;
use Exception;
use PDO;

class Category extends CoreModel
{
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $subtitle;
	/**
	 * @var string
	 */
	private $picture;
	/**
	 * @var int
	 */
	private $home_order;

	/**
	 * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
	 *
	 * @param int $categoryId ID de la catégorie
	 * @return ?Category ? pour dire que le type de retour est soit une instance de Categorie soit null
	 */
	public static function find(int $categoryId): Category
	{
		// se connecter à la BDD
		$pdo = Database::getPDO();

		// écrire notre requête
		$sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

		// exécuter notre requête
		$pdoStatement = $pdo->query($sql);

		// un seul résultat => fetchObject
		$category = $pdoStatement->fetchObject(self::class);

		// L'utilisateur n'est pas trouvé
		if (false === $category) {
			throw new Exception('Catégorie introuvable');
		}

		return $category;
	}

	/**
	 * Méthode permettant de récupérer tous les enregistrements de la table category
	 *
	 * @return Category[]
	 */
	public static function findAll(): array
	{
		$pdo = Database::getPDO();
		$sql = 'SELECT * FROM `category`';
		$pdoStatement = $pdo->query($sql);
		$results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

		return $results;
	}

	/**
	 * Récupérer les 5 catégories mises en avant sur la home
	 * Fonction pour le fron office !
	 *
	 * @return Category[]
	 */
	public function findAllHomepage()
	{
		$pdo = Database::getPDO();
		
		$sql = 'SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
		LIMIT 5';

		$pdoStatement = $pdo->query($sql);
		$categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

		return $categories;
	}

	/**
	 * Enregistre une nouvelle catégorie dans la BDD
	 *
	 * @return boolean true si l'enregistrement s'est bien passé
	 * @throws Exception si l'enregistrement a échoué
	 */
	public function insert(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `category` (name, subtitle, picture)
			VALUES (:name, :subtitle, :picture)";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':subtitle', $this->subtitle);
		$query->bindValue(':picture', $this->picture);

		// Execution de la requête d'insertion (exec, pas query)
		$insertedRow = $query->execute();

		if (0 === $insertedRow) {
			// Aucune ligne ajoutée dans la BDD
			// On lance une exception 
			throw new Exception("Impossible d'enregistrer une nouvelle catégorie");
		}
		
		// Alors on récupère l'id auto-incrémenté généré par MySQL
		$this->id = $pdo->lastInsertId();
	}


	/**
	 * Met à jour catégorie dans la BDD
	 *
	 * @throws Exception lance une exception si la requete SQL s'est mal passée
	 */
	public function update(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "UPDATE `category`
		SET name = :name,
			subtitle = :subtitle,
			picture = :picture,
			home_order = :homeOrder,
			updated_at = NOW()
		WHERE id = :id";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':subtitle', $this->subtitle);
		$query->bindValue(':picture', $this->picture);
		$query->bindValue(':homeOrder', $this->home_order);
		$query->bindValue(':id', $this->id);

		// Execution de la requête d'insertion (exec, pas query)
		$updatedRows = $query->execute();

		// Si aucune ligne n'est modifiée
		if (false === $updatedRows) {
			// Je lance une exception pour indiquer une erreur
			// Lancer une exception stoppe le reste de la fonction
			throw new Exception('Impossible de mettre à jour la catégorie');
		}
	}

	/**
	 * Méthode qui supprime une catégorie de la base de données
	 *
	 * @throws Exception lance une exception si la requete SQL s'est mal passée
	 */
	public function delete(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `category`
					WHERE id = :id';

		// On aurait pu faire un query / exec car $id est déjà filtré par alto router 
		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$pdoStatement->execute();

		// On retourne VRAI, une ligne a été supprimée
		if (1 != $pdoStatement->rowCount()) {
			throw new Exception('Echec supression catégorie');
		}
	}

	public static function resetHomeOrder(): bool {

		$pdo = Database::getPDO();
		
		$sql = 'UPDATE `category` SET home_order = 0';
		
		$pdoStatement = $pdo->query($sql);
		
		return $pdoStatement->execute();
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName(string $name): bool
	{
		if (empty($name)) {
			// Si $name est vide, je refuse cette valeur
			// et je l'indique en revoyant faux
			return false;
		}

		$this->name = $name;
		// $name n'est pas vide, j'accepte cette valeur
		// et je renvoie vrai 
		return true;
	}

	public function getSubtitle()
	{
		return $this->subtitle;
	}

	public function setSubtitle($subtitle)
	{
		$this->subtitle = $subtitle;
	}

	public function getPicture()
	{
		return $this->picture;
	}

	public function setPicture($picture)
	{
		$this->picture = $picture;
	}

	public function getHomeOrder()
	{
		return $this->home_order;
	}

	public function setHomeOrder($home_order)
	{
		$this->home_order = $home_order;
	}
}
