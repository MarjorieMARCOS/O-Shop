<div class="container my-4">
	
	<a href="<?= $router->generate('product-list'); ?>" class="btn btn-success float-end">Retour</a>

	<h2><?= $title; ?></h2>

	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<form action="" method="POST" class="mt-5">
		<div class="mb-3">
			<label for="name" class="form-label">Nom</label>
			<input value="<?= $product->getName(); ?>" type="text" class="form-control" id="name" name="name" placeholder="Nom du produit">
		</div>
		<div class="mb-3">
			<label for="description" class="form-label">Description</label>
			<input value="<?= $product->getDescription(); ?>" type="text" class="form-control" id="description" name="description" placeholder="Description" aria-describedby="descriptionHelpBlock">
			<small id="descriptionHelpBlock" class="form-text text-muted">
				Description du produit
			</small>
		</div>
		<div class="mb-3">
			<label for="picture" class="form-label">Image</label>
			<input value="<?= $product->getPicture(); ?>" type="text" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
			<small id="pictureHelpBlock" class="form-text text-muted">
				URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
			</small>
		</div>

		<div class="mb-3">
			<label for="price" class="form-label">Prix</label>
			<input type="number" value="<?= $product->getPrice(); ?>" step="any" class="form-control" id="price" name="price" placeholder="Prix du produit" min="1">
		</div>

		<div class="mb-3">
			<label for="rate" class="form-label">Note</label>
			<input type="number" value="<?= $product->getRate(); ?>" class="form-control" id="rate" name="rate" placeholder="Note du produit" min="0" max="5">
		</div>

		<div class="mb-3">
			<label for="status" class="form-label">Statut</label>
			<select class="form-control" id="status" name="status" aria-describedby="statusHelpBlock">
				<option value="1" <?= $product->getStatus() == '1' ? 'selected' : ''; ?>>Disponible</option>
				<option value="2" <?= $product->getStatus() == '2' ? 'selected' : ''; ?>>Indisponible</option>
			</select>
			<small id="statusHelpBlock" class="form-text text-muted">
				Statut du produit
			</small>
		</div>

		<div class="mb-3">
			<label for="brand" class="form-label">Marque</label>
			<select class="form-control" id="brand" name="brand" aria-describedby="brandHelpBlock">
				<?php foreach($brandList as $brand): ?>
					<option value="<?= $brand->getId(); ?>" <?= $product->getBrandId() == $brand->getId() ? 'selected' : ''; ?>><?= $brand->getName(); ?></option>
				<?php endforeach; ?>
			</select>
			<small id="brandHelpBlock" class="form-text text-muted">
				Marque du produit
			</small>
		</div>

		<div class="mb-3">
			<label for="type" class="form-label">Type</label>
			<select class="form-control" id="type" name="type" aria-describedby="typeHelpBlock">
				<?php foreach($typeList as $type): ?>
					<option value="<?= $type->getId(); ?>" <?= $product->getTypeId() == $type->getId() ? 'selected' : ''; ?>><?= $type->getName(); ?></option>
				<?php endforeach; ?>
			</select>
			<small id="typeHelpBlock" class="form-text text-muted">
				Type du produit
			</small>
		</div>

		<div class="mb-3">
			<label for="category" class="form-label">Categorie</label>
			<select class="form-control" id="category" name="category" aria-describedby="categoryHelpBlock">
				<?php foreach($categoryList as $category): ?>
					<option value="<?= $category->getId(); ?>" <?= $product->getCategoryId() == $category->getId() ? 'selected' : ''; ?>><?= $category->getName(); ?></option>
				<?php endforeach; ?>
			</select>
			<small id="categoryHelpBlock" class="form-text text-muted">
				Catégorie du produit
			</small>
		</div>

		<div class="mb-3">
			<label for="tags" class="form-label">Tags</label>
			<?php foreach ($tags as $tag) : ?>
				<div class="form-check form-switch">
					<!-- ajouter type="radio" dans l'input pour avoir des radio boutons, pour les marques par exemple -->
					<input class="form-check-input" name="tags[]" type="checkbox" value="<?= $tag->getId(); ?>" <?= in_array($tag, $productTags) ? 'checked' : ''; ?>>
					<label class="form-check-label">
						<?= $tag->getName() ?>
					</label>
				</div>
			<?php endforeach; ?>
		</div>

		<?php require __DIR__ . '/../partials/tokenCSRF.tpl.php'; ?>

		<div class="d-grid gap-2">
			<button type="submit" class="btn btn-primary mt-5">Valider</button>
		</div>
	</form>
</div>