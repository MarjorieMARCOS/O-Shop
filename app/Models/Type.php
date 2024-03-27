<?php

namespace App\Models;

use App\Utils\Database;
use Exception;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 *
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Type extends CoreModel
{
	
	/**
	 * @var string
	 */
	private $name;
    private $footer_order;

	/**
	 * Méthode permettant de récupérer un enregistrement de la table Type en fonction d'un id donné
	 *
	 * @param int $typeId ID du type
	 * @return Type
	 */
	public static function find(int $typeId): Type
	{
		// se connecter à la BDD
		$pdo = Database::getPDO();

		// écrire notre requête
		$sql = 'SELECT * FROM `type` WHERE `id` =' . $typeId;

		// exécuter notre requête
		$pdoStatement = $pdo->query($sql);

		// un seul résultat => fetchObject
		$type = $pdoStatement->fetchObject(self::class);

		// Le type n'est pas trouvé
		if (false === $type) {
			throw new Exception('Type introuvable');
		}

		return $type;
	}

	/**
	 * Méthode permettant de récupérer tous les enregistrements de la table type
	 *
	 * @return Type[]
	 */
	public static function findAll(): array
	{
		$pdo = Database::getPDO();
		$sql = 'SELECT * FROM `type`';
		$pdoStatement = $pdo->query($sql);
		$results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

		return $results;
	}

	/**
	 * Enregistre un nouveau type dans la BDD
	 *
	 * @return void
	 * @throws Exception si l'enregistrement a échoué
	 */
	public function insert(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `type` (name)
			VALUES (:name)";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);

		// Execution de la requête d'insertion (exec, pas query)
		$insertedRow = $query->execute();

		if (0 === $insertedRow) {
			// Aucune ligne ajoutée dans la BDD
			// On lance une exception 
			throw new Exception("Impossible d'enregistrer un nouveau type");
		}
		
		// Alors on récupère l'id auto-incrémenté généré par MySQL
		$this->id = $pdo->lastInsertId();
	}

	/**
	 * Met à jour un type dans la BDD
	 *
	 * @throws Exception lance une exception si la requete SQL s'est mal passée
	 * @return void
	 */
	public function update(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "UPDATE `type`
		SET name = :name,
			footer_order = :footer_order,
			updated_at = NOW()
		WHERE id = :id";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':footer_order', $this->footer_order);
		$query->bindValue(':id', $this->id);

		// Execution de la requête d'insertion (exec, pas query)
		$updatedRows = $query->execute();

		// Si aucune ligne n'est modifiée
		if (false === $updatedRows) {
			// Je lance une exception pour indiquer une erreur
			// Lancer une exception stoppe le reste de la fonction
			throw new Exception('Impossible de mettre à jour le type');
		}
	}

	/**
	 * Supprimer un type dans la BDD
	 *
	 * @throws Exception lance une exception si la requete SQL s'est mal passée
	 * @return void
	 */
	public function delete(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `type`
					WHERE id = :id';

		// On aurait pu faire un query / exec car $id est déjà filtré par alto router 
		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$pdoStatement->execute();

		// On retourne VRAI, une ligne a été supprimée
		if (1 != $pdoStatement->rowCount()) {
			throw new Exception('Echec supression du type');
		}
	}

	public static function resetFooterOrder(): bool 
	{
		$pdo = Database::getPDO();
		
		$sql = 'UPDATE `type` SET footer_order = 0';
		
		$pdoStatement = $pdo->query($sql);
		
		return $pdoStatement->execute();
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName(string $name)
	{
		if (empty($name)) {
			return false;
		}

		$this->name = $name;
		return true;
	}

    public function getFooter_order()
    {
        return $this->footer_order;
    }

    public function setFooter_order($footer_order)
    {
        $this->footer_order = $footer_order;

        return $this;
    }
}
