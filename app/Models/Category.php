<?php

namespace App\Models;
use App\Models\CoreModel;
use App\Utils\Database;
use \PDO;

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
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */
    public function setName(string $name)
    {
        if(empty($name)) {
			return false;
		}
		$this->name = $name;
		return true;
    }

    /**
     * Get the value of subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */
    public function setSubtitle($subtitle)
    {        
        if(empty($subtitle)) {
        return false;
    }
    $this->subtitle = $subtitle;
    return true;
   
    }

    /**
     * Get the value of picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */
    public function setPicture($picture)
    {
        if(empty($picture)) {
            return false;
        }
        $this->picture = $picture;
        return true;
    }

    /**
     * Get the value of home_order
     */
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
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
    public static function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

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

    public function update($id)
    {
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "UPDATE `category`
			    SET name = :name, subtitle = :subtitle, picture = :picture
                WHERE id = " . $id;

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':subtitle', $this->subtitle);
		$query->bindValue(':picture', $this->picture);

		// Execution de la requête d'insertion (exec, pas query)
		$updatedRows = $query->execute();

		 // On retourne VRAI, si au moins une ligne ajoutée
         return ($updatedRows > 0);
    }
}
