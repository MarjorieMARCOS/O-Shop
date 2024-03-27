<?php

namespace App\Controllers;

abstract class CrudController extends CoreController {

	// Action de lister des données
	abstract public function list(): void;

	// Action ajouter une donnée
	abstract public function add(): void;

	// Action modifier une donnée
	abstract public function update(int $id): void;

	// Action supprimer une donnée
	abstract public function delete(int $id): void;
}