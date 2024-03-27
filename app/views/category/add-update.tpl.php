<div class="container my-4">

	<a href="<?= $router->generate('category-list'); ?>" class="btn btn-success float-end">Retour</a>

	<h2><?= $title; ?></h2>

	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<!-- 
		action défini vers quelle page on envoie le formulaire 
		avec une string vide "" on reste sur la meme URL
		si on voulait envoyer le formulaire sur une autre page,
		il faudrait indiquer l'URL dans action
	-->
	<!-- 
		method = POST défini qu'on envoie le formulaire avec la méthode HTTP POST
		(On peut aussi faire des forumaire en GET) 
	-->
	<form action="" method="POST" class="mt-5">

		<div class="mb-3">
			<label for="name" class="form-label">Nom</label>
			<input type="text" value="<?= $category->getName(); ?>" class="form-control" id="name" name="name" placeholder="Nom de la catégorie">
		</div>
		<div class="mb-3">
			<label for="subtitle" class="form-label">Sous-titre</label>
			<input type="text" value="<?= $category->getSubtitle(); ?>" class="form-control" id="subtitle" name="subtitle" placeholder="Sous-titre" aria-describedby="subtitleHelpBlock">
			<small id="subtitleHelpBlock" class="form-text text-muted">
				Sera affiché sur la page d'accueil comme bouton devant l'image
			</small>
		</div>
		<div class="mb-3">
			<label for="picture" class="form-label">Image</label>
			<input type="text" value="<?= $category->getPicture(); ?>" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
			<small id="pictureHelpBlock" class="form-text text-muted">
				URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
			</small>
		</div>

		<?php require __DIR__ . '/../partials/tokenCSRF.tpl.php'; ?>

		<div class="d-grid gap-2">
			<button type="submit" class="btn btn-primary mt-5">Valider</button>
		</div>
	</form>
</div>