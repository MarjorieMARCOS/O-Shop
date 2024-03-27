<?php

namespace App\Models;

use App\Utils\Database;
use Exception;
use PDO;

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel
{
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $description;
	/**
	 * @var string
	 */
	private $picture;
	/**
	 * @var float
	 */
	private $price;
	/**
	 * @var int
	 */
	private $rate;
	/**
	 * @var int
	 */
	private $status;
	/**
	 * @var int
	 */
	private $brand_id;
	/**
	 * @var int
	 */
	private $category_id;
	/**
	 * @var int
	 */
	private $type_id;

	/**
	 * Méthode permettant de récupérer un enregistrement de la table Product en fonction d'un id donné
	 *
	 * @param int $productId ID du produit
	 * @return Product
	 */
	public static function find(int $productId): Product
	{
		// récupérer un objet PDO = connexion à la BDD
		$pdo = Database::getPDO();

		// on écrit la requête SQL pour récupérer le produit
		$sql = 'SELECT *
            FROM product
            WHERE id = ' . $productId;

		// query ? exec ?
		// On fait de la LECTURE = une récupration => query()
		// si on avait fait une modification, suppression, ou un ajout => exec
		$pdoStatement = $pdo->query($sql);

		// fetchObject() pour récupérer un seul résultat
		// si j'en avais eu plusieurs => fetchAll
		$product = $pdoStatement->fetchObject(self::class);

		// L'utilisateur n'est pas trouvé
		if (false === $product) {
			throw new Exception('Produit introuvable');
		}

		return $product;
	}

	/**
	 * Méthode permettant de récupérer tous les enregistrements de la table product
	 *
	 * @return Product[]
	 */
	public static function findAll(): array
	{
		$pdo = Database::getPDO();
		$sql = 'SELECT * FROM `product`';
		$pdoStatement = $pdo->query($sql);
		$results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

		return $results;
	}

	/**
	 * Méthode permettant d'ajouter un enregistrement dans la table product
	 * L'objet courant doit contenir toutes les données à ajouter : 1 propriété => 1 colonne dans la table
	 *
	 * @return bool
	 * 
	 * @throws Exception si l'enregistrement du produit a échoué
	 */
	public function insert(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `product` (name, description, picture, price, rate, status, brand_id, type_id, category_id)
            VALUES (:name, :description, :picture, :price, :rate, :status, :brand_id, :type_id, :category_id)";

		// Préparation de la quete INSERT
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':name', $this->name);
		$query->bindValue(':description', $this->description);
		$query->bindValue(':picture', $this->picture);
		$query->bindValue(':price', $this->price);
		$query->bindValue(':rate', $this->rate);
		$query->bindValue(':status', $this->status);
		$query->bindValue(':brand_id', $this->brand_id);
		$query->bindValue(':category_id', $this->category_id);
		$query->bindValue(':type_id', $this->type_id);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$insertedRow = $query->execute();

		if (0 === $insertedRow) {
			// Aucune ligne ajoutée dans la BDD
			// On lance une exception 
			throw new Exception("Impossible d'enregistrer un nouveau produit");
		}
		
		// Alors on récupère l'id auto-incrémenté généré par MySQL
		$this->id = $pdo->lastInsertId();
	}

	/**
	 * Méthode permettant de modifier un enregistrement dans la table product
	 * L'objet courant doit contenir toutes les données à modifier : 1 propriété => 1 colonne dans la table
	 *
	 * @return bool
	 * 
	 * @throws Exception si l'enregistrement du produit a échoué
	 */
	public function update(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "UPDATE `product` 
		SET name = :name, 
			description = :description, 
			picture = :picture, 
			price = :price, 
			rate = :rate, 
			status = :status, 
			brand_id = :brand_id, 
			type_id = :type_id, 
			category_id = :category_id
		WHERE id = :id";

		// Préparation de la quete INSERT
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':name', $this->name);
		$query->bindValue(':description', $this->description);
		$query->bindValue(':picture', $this->picture);
		$query->bindValue(':price', $this->price);
		$query->bindValue(':rate', $this->rate);
		$query->bindValue(':status', $this->status);
		$query->bindValue(':brand_id', $this->brand_id);
		$query->bindValue(':category_id', $this->category_id);
		$query->bindValue(':type_id', $this->type_id);
		$query->bindValue(':id', $this->id);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$updatedRow = $query->execute();

		if (false === $updatedRow) {
			// Aucune ligne ajoutée dans la BDD
			// On lance une exception 
			throw new Exception("Impossible de modifier un produit");
		}
	}

	/**
	 * Méthode permettant de supprimer un produit de la liste
	 * L'objet courant doit contenir toutes les données à modifier : 1 propriété => 1 colonne dans la table
	 *
	 * @return bool
	 * 
	 * @throws Exception si l'enregistrement du produit a échoué
	 */
	public function delete(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `product`
					WHERE id = :id';

		// On aurait pu faire un query / exec car $id est déjà filtré par alto router 
		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$pdoStatement->execute();

		// On retourne VRAI, une ligne a été supprimée
		if (1 != $pdoStatement->rowCount()) {
			throw new Exception('Echec supression produit');
		}
	}

	/**
	 * Supprime tous les tags du produit
	 */
	public function resetTags(): void
	{
		$pdo = Database::getPDO();

		$sql = 'DELETE FROM `product_has_tag` WHERE product_id = :id';

		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		if( !$pdoStatement->execute() ) {
			throw new Exception('Impossible de supprimer les tags du produit');
		}
	}

/**
	 * Enregistre une liste de tag associé au produit.
	 * Cette fonction execute la sauvegarde des tags en une seule requête SQL.
	 *
	 * @param [type] $tagsId liste des tags à enregistrer
	 * @return boolean
	 */
	public function saveTagsId(array $tagsId): bool
	{
		$pdo = Database::getPDO();

		// First part of the request
		$sql = 'INSERT INTO product_has_tag (tag_id, product_id) VALUES ';

		// Add values (:idX, :productId)
		// Example : (:id1, :productId), (:id2, :productId), (:id3, :productId), etc.
		$i = 1;
		foreach ($tagsId as $tagId) {
			$sql .= '(:id' . $i . ', :productId)';

			// while not the end of tag list, add ',' at the end of (:id, :productId)
			if ($i < sizeof($tagsId)) {
				$sql .= ', ';
			}
			$i++;
		}

		$pdoStatement = $pdo->prepare($sql);

		// Bind all :idX in $sql with tagId
		$i = 1;
		foreach ($tagsId as $tagId) {
			$pdoStatement->bindValue(':id' . $i, $tagId);
			$i++;
		}
		// Bind :productId
		$pdoStatement->bindValue(':productId', $this->id);

		// Le requete SQL finale ressemble à ça :
		// INSERT INTO product_has_tag (tag_id, product_id) VALUES (1, 42), (3, 42), (9, 42), (11, 42)

		// Exec sql request
		return $pdoStatement->execute();
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName(string $name): bool
	{
		if (empty($name)) {
			return false;
		}

		$this->name = $name;
		return true;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription(string $description)
	{
		$this->description = $description;
	}

	public function getPicture()
	{
		return $this->picture;
	}

	public function setPicture(string $picture)
	{
		$this->picture = $picture;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice(float $price): bool
	{
		if ($price < 0) {
			return false;
		}

		$this->price = $price;
		return true;
	}

	public function getRate()
	{
		return $this->rate;
	}

	public function setRate(int $rate): bool
	{
		if ($rate < 0 || $rate > 5) {
			return false;
		}

		$this->rate = $rate;
		return true;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus(int $status): bool
	{
		if ($status < 0 || $status > 2) {
			return false;
		}

		$this->status = $status;
		return true;
	}

	public function getBrandId()
	{
		return $this->brand_id;
	}

	public function setBrandId(int $brand_id)
	{
		$this->brand_id = $brand_id;
	}

	public function getCategoryId()
	{
		return $this->category_id;
	}

	public function setCategoryId(int $category_id)
	{
		$this->category_id = $category_id;
	}

	public function getTypeId()
	{
		return $this->type_id;
	}

	public function setTypeId(int $type_id)
	{
		$this->type_id = $type_id;
	}
}
