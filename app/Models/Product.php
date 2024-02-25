<?php

namespace App\Models;

use App\Models\CoreModel;
use App\Utils\Database;
use \PDO;
use \Exception; 

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel
{
    private $name;
    private $description;
    private $picture;
    private $price;
    private $rate;
    private $status;
    private $brand_id;
    private $category_id;
    private $type_id;


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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        if(empty($description)) {
            return false;
        }
        $this->description = $description;
        return true;
        
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture(string $picture)
    {
        if(empty($picture)) {
            return false;
        }
        $this->picture = $picture;
        return true;
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

    public function setRate(int $rate)
    {
        if(empty($rate)) {
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
		if ($status != 1 && $status != 2) {
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

    /**
     * Méthode permettant de récupérer un enregistrement de la table Product en fonction d'un id donné
     *
     * @param int $productId ID du produit
     * @return ?Product
     */
    public static function find($productId): ?Product
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM product
            WHERE id = ' . $productId;

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $product = $pdoStatement->fetchObject(self::class);

        if (null === $product) {
            return null;
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
	 * Récupérer les 3 produits sur la home page
	 *
	 * @return Product[]
	 */
    public static function findOnly3(): array
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * 
                FROM `product`
                LIMIT 3';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $results;
    }

    

    /**
	 * Enregistre un nouveau produit dans la BDD
	 *
	 * @return boolean true si l'enregistrement s'est bien passé
	 */
    public function insert(): bool
    {

        $pdo = Database::getPDO();

        $sql = "INSERT INTO `product` (name, description, picture, price, rate, status, brand_id, category_id, type_id)
            VALUES (:name, :description, :picture, :price, :rate, :status, :brand_id, :type_id, :category_id)";
        $query = $pdo->prepare($sql);
        $query->bindValue(':name', $this->name);
        $query->bindValue(':description', $this->description);
        $query->bindValue(':picture', $this->picture);
        $query->bindValue(':price', $this->price);
        $query->bindValue(':rate', $this->rate);
        $query->bindValue(':status', $this->status);
        $query->bindValue(':brand_id', $this->brand_id);
        $query->bindValue(':category_id', $this->category_id);
        $query->bindValue(':type_id', $this->type_id);

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
		$sql = "UPDATE `product`
			    SET name = :name, description = :description, picture = :picture, price = :price, rate = :rate, status = :status, brand_id = :brand_id, category_id = :category_id, type_id = :type_id, updated_at = NOW()
                WHERE id = :id";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
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


		// Execution de la requête d'insertion (exec, pas query)
		$updatedRows = $query->execute();

		// Si aucune ligne n'est modifiée
		if (false === $updatedRows) {
			// Je lance une exception pour indiquer une erreur
			// Lancer une exception stoppe le reste de la fonction
			throw new Exception('Impossible de mettre à jour le produit');
		}
    }

}
