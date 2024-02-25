<?php

namespace App\Models;
use App\Models\CoreModel;
use App\Utils\Database;
use \PDO;
use \Exception;

class Category extends CoreModel
{

    private $name;
    private $subtitle;
    private $picture;
    private $home_order;

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        if(empty($name)) {
			return false;
		}
		$this->name = $name;
		return true;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {        
            if(empty($subtitle)) {
            return false;
            }
        $this->subtitle = $subtitle;
        return true;
   
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
            if(empty($picture)) {
                return false;
            }
        $this->picture = $picture;
        return true;
    }

    public function getHomeOrder()
    {
        return $this->home_order;
    }

    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return ?Category
     */
    public static function find(int $categoryId): ?Category
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject(self::class);

        if(false === $category) {
			// La catégorie est introuvable, elle n'existe pas
			return null;
		}

		// retourner le résultat une instance de Catégorie
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
	 * Récupérer les 3 catégories sur la home page
	 *
	 * @return Category[]
	 */
    public static function findOnly3(): array
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * 
                FROM `category`
                LIMIT 3';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     *
     * @return Category[]
     */
    public static function findAllHomepage(): array
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $categories;
    }

	/**
	 * Enregistre une nouvelle catégorie dans la BDD
	 *
	 * @return boolean true si l'enregistrement s'est bien passé
	 */
	public function insert(): bool
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
		$insertedRows = $query->execute();

		// Si au moins une ligne ajoutée
		if ($insertedRows > 0) {
			// Alors on récupère l'id auto-incrémenté généré par MySQL
			$this->id = $pdo->lastInsertId();

			// On retourne VRAI car l'ajout a parfaitement fonctionné
			return true;
			// => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
            
		}

		// Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
		return false;
        
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
			    SET name = :name, subtitle = :subtitle, picture = :picture, updated_at = NOW()
                WHERE id = :id";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':subtitle', $this->subtitle);
		$query->bindValue(':picture', $this->picture);
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
}
