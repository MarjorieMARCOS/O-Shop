<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
abstract class CoreModel
{
	/**
	 * @var int
	 */
	protected int $id = 0;
	/**
	 * @var string
	 */
	protected $created_at;
	/**
	 * @var string
	 */
	protected $updated_at;

	abstract public static function findAll(): array;
	abstract public static function find(int $id): CoreModel;
	abstract public function insert(): void;
	abstract public function update(): void;
	abstract public function delete(): void;

	/**
	 * Fait soit un insert() soit un update()
	 * pour enregistrer un objet dans la BDD
	 *
	 * @return void
	 */
	public function save(): void
	{
		// Si $this->id est vide
		// je dois faire un insert pour ajouter un nouvel enregistrement
		// Si $this->id a une valeur
		// je dois mettre à jour l'enregistrement

		if( empty($this->id) || 0 === $this->id ) {
			// $this->id est vide
			// J'enregistre une nouvelle ligne dans la table
			$this->insert();
		} else {
			// this->id a une valeur
			// je dois mettre à jour l'enresgistrement existant
			$this->update();			
		}
	}

	/**
	 * Get the value of id
	 *
	 * @return  int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * Get the value of created_at
	 *
	 * @return  string
	 */
	public function getCreatedAt(): string
	{
		return $this->created_at;
	}

	/**
	 * Get the value of updated_at
	 *
	 * @return  string
	 */
	public function getUpdatedAt(): string
	{
		return $this->updated_at;
	}
}
