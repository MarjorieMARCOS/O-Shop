<?php
$product = $viewData['product'];
$categoryList = $viewData['categoryList'];
$typeList =	$viewData['typeList'];
$brandList = $viewData['brandList'];

?>
<div class="container my-4">

<a href="<?= $router->generate('product-list')   ?>" class="btn btn-success float-end">Retour</a>

<h2>Ajouter un produit</h2>

<form action="" method="POST" class="mt-5">

    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" value="<?= $product->getName();  ?>" class="form-control" id="name" name="name" placeholder="Nom du produit">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" value="" class="form-control" id="description" name="description" placeholder="Description"
            aria-describedby="descriptionHelpBlock">
        <small id="descriptionHelpBlock" class="form-text text-muted">
            Sera affiché sur la page d'accueil description en dessous de l'image
        </small>
    </div>
    <div class="mb-3">
        <label for="picture" class="form-label">Image</label>
        <input type="text" value="<?= $product->getPicture();  ?>" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png"
            aria-describedby="pictureHelpBlock">
        <small id="pictureHelpBlock" class="form-text text-muted">
            URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/"
                target="_blank">cette page</a>
        </small>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Prix</label>
        <input type="text" value="<?= $product->getPrice();  ?>" class="form-control" id="price" name="price" placeholder="Prix du produit">
    </div>
    <div class="mb-3">
        <label for="rate" class="form-label">Note</label>
        <input type="text" value="<?= $product->getRate();  ?>" class="form-control" id="rate" name="rate" placeholder="Prix du produit">
    </div>

        <div class="mb-3">
			<label for="status" class="form-label">Statut</label>
			<select class="form-control" id="status" name="status" aria-describedby="statusHelpBlock">
				<option value="1">Disponible</option>
				<option value="2">Indisponible</option>
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

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary mt-5">Valider</button>
    </div>
</form>
</div>

