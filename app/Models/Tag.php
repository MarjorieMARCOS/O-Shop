<?php

namespace App\Models;

use App\Utils\Database;
use Exception;
use PDO;

class Tag extends CoreModel
{
	/**
	 * @var string Nom du tag
	 */
	private string $name = '';

	public static function findAll(): array
	{
		$pdo = Database::getPDO();
		$sql = 'SELECT * FROM `tag`';
		$pdoStatement = $pdo->query($sql);
		$results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

		return $results;
	}

	public static function findAllByProductId(int $productId): array
	{
		// PDO
		$pdo = Database::getPDO();
		// Requête
		$sql = "SELECT tag.* FROM tag
		  	INNER JOIN product_has_tag ON product_has_tag.tag_id = tag.id
		  	WHERE product_has_tag.product_id = :product_id";

		// On prépare, on exécute
		$pdoStatement = $pdo->prepare($sql);

		// On associe les valeurs
		$pdoStatement->bindValue(':product_id', $productId, PDO::PARAM_INT);
		$pdoStatement->execute();

		// On fetch
		return $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
	}

	public static function find(int $id): Tag
	{
		// récupérer un objet PDO = connexion à la BDD
		$pdo = Database::getPDO();

		// on écrit la requête SQL pour récupérer le produit
		$sql = 'SELECT * FROM tag
            	WHERE id = ' . $id;

		// query ? exec ?
		// On fait de la LECTURE = une récupration => query()
		// si on avait fait une modification, suppression, ou un ajout => exec
		$pdoStatement = $pdo->query($sql);

		// fetchObject() pour récupérer un seul résultat
		// si j'en avais eu plusieurs => fetchAll
		$tag = $pdoStatement->fetchObject(self::class);

		// L'utilisateur n'est pas trouvé
		if (false === $tag) {
			throw new Exception('Tag introuvable');
		}

		return $tag;
	}

	public function insert(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `tag` (name) VALUES (:name)";

		// Préparation de la quete INSERT
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':name', $this->name);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$insertedRow = $query->execute();

		if (0 === $insertedRow) {
			// Aucune ligne ajoutée dans la BDD
			// On lance une exception 
			throw new Exception("Impossible d'enregistrer un nouveau tag");
		}
		
		// Alors on récupère l'id auto-incrémenté généré par MySQL
		$this->id = $pdo->lastInsertId();
	}

	public function update(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE INTO
		$sql = "UPDATE `tag`
            SET name = :name, 
			updated_at = NOW()
            WHERE id = :id";

		// Préparation de la quete UPDATE
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':id', $this->id);
		$query->bindValue(':name', $this->name);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$updatedRow = $query->execute();

		if (false === $updatedRow) {
			// Aucune ligne ajoutée dans la BDD
			// On lance une exception 
			throw new Exception("Impossible de modifier un tag");
		}
	}

	/**
	 * Supprime les référence d'un tag dans la table product_has_tag
	 *
	 * @return void
	 */
	protected function deleteProductTag(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `product_has_tag`
					WHERE tag_id = :id';

		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$pdoStatement->execute();

		// On ne lance pas d'exception si aucune ligne supprimée
		// Car il est possible que le tag n'appartienne à aucun produit
	}

	public function delete(): void
	{
		// Supprime les références du tag dans la table product_has_tag
		$this->deleteProductTag();

		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `tag`
					WHERE id = :id';

		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$res = $pdoStatement->execute();

		if (1 != $pdoStatement->rowCount()) {
			throw new Exception('Echec supression tag');
		}
	}

	public function getName(): string
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
}
