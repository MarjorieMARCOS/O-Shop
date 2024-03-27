<?php

namespace App\Models;

use App\Utils\Database;
use Exception;
use PDO;

class AppUser extends CoreModel
{
	private string $email = '';
	private string $password = '';
	private string $firstname = '';
	private string $lastname = '';
	private string $role = '';
	private int $status = 1;

	public static function findByEmail(string $email): AppUser
	{
		// se connecter à la BDD
		$pdo = Database::getPDO();

		// écrire notre requête
		$sql = 'SELECT * FROM `app_user` WHERE `email` LIKE :email LIMIT 1';

		// exécuter notre requête
		$query = $pdo->prepare($sql);

		$query->bindValue(':email', $email);

		$query->execute();

		// un seul résultat => fetchObject
		$user = $query->fetchObject(self::class);

		// L'utilisateur n'est pas trouvé
		if (false === $user) {
			throw new Exception('Utilisateur introuvable');
		}

		return $user;
	}

	public static function findAll(): array
	{
		$pdo = Database::getPDO();
		$sql = 'SELECT * FROM `app_user`';
		$pdoStatement = $pdo->query($sql);
		$results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

		return $results;
	}

	public static function find(int $id): AppUser
	{
		// se connecter à la BDD
		$pdo = Database::getPDO();

		// écrire notre requête
		$sql = 'SELECT * FROM `app_user` WHERE id = :id';

		// exécuter notre requête
		$query = $pdo->prepare($sql);

		$query->bindValue(':id', $id);

		$query->execute();

		// un seul résultat => fetchObject
		$user = $query->fetchObject(self::class);

		// L'utilisateur n'est pas trouvé
		if (false === $user) {
			throw new Exception('Echec recherche utilisateur');
		}

		return $user;
	}

	public function insert(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `app_user` (email, password, firstname, lastname, role, status)
            VALUES (:email, :password, :firstname, :lastname, :role, :status)";

		// Préparation de la quete INSERT
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':email', $this->email);
		$query->bindValue(':firstname', $this->firstname);
		$query->bindValue(':lastname', $this->lastname);
		// Enregistre le hash du mot de passe
		$query->bindValue(':password', $this->password);
		$query->bindValue(':role', $this->role);
		$query->bindValue(':status', $this->status);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$insertedRow = $query->execute();

		// Si au moins une ligne ajoutée
		if (0 === $insertedRow) {
			throw new Exception("Echec enregistrement utilisateur");
		}
		// Alors on récupère l'id auto-incrémenté généré par MySQL
		$this->id = $pdo->lastInsertId();
	}

	public function update(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE INTO
		$sql = "UPDATE `app_user`
            SET email = :email, 
                password = :password, 
                firstname = :firstname,
                lastname = :lastname, 
                role = :role, 
                status = :status, 
                updated_at = NOW()
            WHERE id = :id";

		// Préparation de la quete UPDATE
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->bindValue(':email', $this->email, PDO::PARAM_STR);
		$query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
		$query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
		// Calcul le hash du mot de passe
		$query->bindValue(':password', $this->password, PDO::PARAM_STR);
		$query->bindValue(':role', $this->role, PDO::PARAM_STR);
		$query->bindValue(':status', $this->status, PDO::PARAM_INT);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$isUpdated = $query->execute();

		if(0 === $isUpdated) {
			throw new Exception('Echec modification utilisateur');
		}
	}

	public function delete(): void
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `app_user`
					WHERE id = :id';

		// On aurait pu faire un query / exec car $id est déjà filtré par alto router 
		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$pdoStatement->execute();

		// On retourne VRAI, une ligne a été supprimée
		if (1 != $pdoStatement->rowCount()) {
			throw new Exception('Echec supression utilisateur');
		}
	}

	/**
	 * Get the value of email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 *
	 * @return  self
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get the value of password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @return  self
	 */
	public function setPassword($password)
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);

		return $this;
	}

	/**
	 * Get the value of firstname
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Set the value of firstname
	 *
	 * @return  self
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * Get the value of lastname
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Set the value of lastname
	 *
	 * @return  self
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * Get the value of role
	 */
	public function getRole()
	{
		return $this->role;
	}

	/**
	 * Set the value of role
	 *
	 * @return  self
	 */
	public function setRole(string $role): bool
	{
		if($role != 'admin' && $role != 'catalog-manager') {
			return false;
		}

		$this->role = $role;
		return true;
	}

	/**
	 * Get the value of status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set the value of status
	 *
	 * @return  self
	 */
	public function setStatus(int $status): bool
	{
		if($status != 1 && $status != 2) {
			return false;
		}

		$this->status = $status;
		return true;
	}

	public function isAdmin(): bool
	{
		return 'admin' == $this->role;
	}

	public function isActif(): bool
	{
		return 1 == $this->status;
	}
}
