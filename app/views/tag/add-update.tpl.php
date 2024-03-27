<div class="container my-4">

	<a href="<?= $router->generate('tag-list'); ?>" class="btn btn-success float-end">Retour</a>

	<h2><?= $title ?? 'Ajouter un tag'; ?></h2>
	
	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<form action="" method="POST" class="mt-5">
	
		<div class="mb-3">
			<label for="name" class="form-label">Nom</label>
			<input type="text" value="<?= $tag->getName(); ?>" class="form-control" id="name" name="name" placeholder="Nom du type">
		</div>
		
		<?php require __DIR__ . '/../partials/tokenCSRF.tpl.php'; ?>
		
		<div class="d-grid gap-2">
			<button type="submit" class="btn btn-primary mt-5">Valider</button>
		</div>
	</form>
</div>