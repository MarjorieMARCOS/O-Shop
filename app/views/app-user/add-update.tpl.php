<div class="container my-4">

	<a href="<?= $router->generate('user-list') ?>" class="btn btn-success float-end">Retour</a>

	<h2><?= $title; ?></h2>

	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<form action="" method="POST" class="mt-5">

		<div class="form-group">
			<label for="firstname" class="form-label">Prénom</label>
			<input type="text" class="form-control" id="firstname" name="firstname" value="<?= $user->getFirstname() ?>" placeholder="Prénom de l'utilisateur">
		</div>

		<div class="form-group">
			<label for="lastname" class="form-label">Nom</label>
			<input type="text" class="form-control" id="lastname" name="lastname" value="<?= $user->getLastname() ?>" placeholder="Nom de l'utilisateur">
		</div>

		<div class="form-group">
			<label for="email" class="form-label">Email</label>
			<input type="email" class="form-control" id="email" name="email" value="<?= $user->getEmail() ?>" placeholder="Email de l'utilisateur">
		</div>

		<div class="form-group">
			<label for="password" class="form-label">Mot de passe</label>
			<input type="password" class="form-control" id="password" name="password" value="" placeholder="Mot de passe de l'utilisateur">
		</div>

		<div class="mb-3">
			<label for="role" class="form-label">Rôle</label>
			<select class="form-control" id="role" name="role" aria-describedby="statusHelpBlock">
				<option value="catalog-manager" <?= !$user->isAdmin() ? 'selected' : ''; ?>>Catalog Manager</option>
				<option value="admin" <?= $user->isAdmin() ? 'selected' : ''; ?>>Admin</option>
			</select>
			<small id="statusHelpBlock" class="form-text text-muted">
				Rôle de l'utilisateur
			</small>
		</div>

		<div class="mb-3">
			<label for="status" class="form-label">Statut</label>
			<select class="form-control" id="status" name="status" aria-describedby="statusHelpBlock">
				<option value="1" <?= $user->isActif() ? 'selected' : ''; ?>>Actif</option>
				<option value="2" <?= !$user->isActif() ? 'selected' : ''; ?>>Inactif / désactivé</option>
			</select>
			<small id="statusHelpBlock" class="form-text text-muted">
				Statut de l'utilisateur
			</small>
		</div>

		<?php require __DIR__ . '/../partials/tokenCSRF.tpl.php'; ?>

		<div class="d-grid gap-2">
			<button type="submit" class="btn btn-primary mt-5">Valider</button>
		</div>
	</form>
</div>