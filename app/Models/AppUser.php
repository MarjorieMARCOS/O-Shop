<?php

namespace App\Models;

use App\Utils\Database;
use App\Models\CoreModel;
use PDO;
use \Exception;


class AppUser extends CoreModel
{
    private $name;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $role;
    private $status;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstanem()
    {
        return $this->firstname;
    }

    public function setFirstanem($firstanem)
    {
        $this->firstname = $firstanem;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }
 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Méthode permettant de récupérer l'email lors d'une connexion
     *
     * @param string $appUserEmail email de l'user
     * 
     */
    public static function findByEmail(string $appUserEmail)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT app_user.* FROM `app_user` WHERE `email` = :email';

        // exécuter la requet sql
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':email', $appUserEmail, PDO::PARAM_STR);
        $pdoStatement->execute();
        $appUser = $pdoStatement->fetchObject(self::class);

        if(false ===  $appUser) {
			// La catégorie est introuvable, elle n'existe pas
			throw new Exception('Connexion impossible');
		}

		// retourner le résultat une instance de Catégorie
		return  $appUser;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table app-user
     *
     * @return AppUser[]
     */
    public static function findAll(): array
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->query($sql);
        $appUser = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $appUser;
    }


	/**
	 * Enregistre un nouvel user dans la BDD
	 *
	 * @return boolean true si l'enregistrement s'est bien passé
	 */
	public function insert(): bool
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `app_user` (name, password, password, firstname, lastname, role, status)
			VALUES (:name, :password, :password, :password, :firstname, :lastname, role, status)";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':password', $this->password);
		$query->bindValue(':firstname', $this->firstname);
        $query->bindValue(':lastname', $this->lastname);
        $query->bindValue(':role', $this->role);
        $query->bindValue(':status', $this->status);

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
		$sql = "UPDATE `app_user`
			    SET name = :name, password = :password, firstname = :firstname, lastname = :lastname, role = :role, status = :status
                WHERE id = :id";

		// Préperation de la requete
		$query = $pdo->prepare($sql);
		$query->bindValue(':name', $this->name);
		$query->bindValue(':password', $this->password);
		$query->bindValue(':firstname', $this->firstname);
        $query->bindValue(':lastname', $this->lastname);
        $query->bindValue(':role', $this->role);
        $query->bindValue(':status', $this->status);
        $query->bindValue(':id', $this->id);

		// Execution de la requête d'insertion (exec, pas query)
		$updatedRows = $query->execute();

		// Si aucune ligne n'est modifiée
		if (false === $updatedRows) {
			// Je lance une exception pour indiquer une erreur
			// Lancer une exception stoppe le reste de la fonction
			throw new Exception('Impossible de mettre à jour les identifiants');
		}
	

    }
}



