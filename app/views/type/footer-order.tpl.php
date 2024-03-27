<div class="container my-4">

	<a href="<?= $router->generate('type-list') ?>" class="btn btn-success float-end">Retour</a>

	<h2>Sélection des types de la page d'accueil* </h2>
	<small>*dans le bas de page</small>

	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<form action="" method="POST" class="mt-5">
		<div class="row">
			<?php for ($i = 1; $i <= 7; $i++) : ?>
				<div class="col">
					<div class="form-group">
						<label for="spots<?= $i ?>">Emplacement #<?= $i ?></label>
	
						<select class="form-control" id="spots<?= $i ?>" name="emplacements[]">
							<option value="">choisissez :</option>
						
							<?php foreach ($typeList as $type) : ?>
								<option value="<?= $type->getId() ?>" <?= $type->getFooter_order() == $i ? 'selected' : ''; ?>>
									<?= $type->getName() ?>
								</option>
							<?php endforeach ?>

						</select>

					</div>
				</div>
			<?php endfor ?>

		</div>

		<?php require __DIR__ . '/../partials/tokenCSRF.tpl.php'; ?>

		<div class="d-grid gap-2">
			<button type="submit" class="btn btn-primary mt-5">Valider</button>
		</div>

	</form>

</div>