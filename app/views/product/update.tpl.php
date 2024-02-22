<?php
$product = $viewData['product'];

?>
<div class="container my-4">

<a href="<?= $router->generate('product-list')   ?>" class="btn btn-success float-end">Retour</a>

<h2>Ajouter un produit</h2>

<form action="<?= $router->generate('product-modify')   ?>" method="POST" class="mt-5">
<div class="mb-3">
        <label for="name" class="form-label">ID numéro : <?= $product->getId();  ?></label>
        <input type="hidden" name="id" value="<?= $product->getId();  ?>">
    </div>
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
        <label for="brand_id" class="form-label">brand_id</label>
        <input type="text" value="<?= $product->getBrandId();  ?>" class="form-control" id="brand_id" name="brand_id" placeholder="Prix du produit">
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">category_id</label>
        <input type="text" value="<?= $product->getCategoryId();  ?>" class="form-control" id="category_id" name="category_id" placeholder="Prix du produit">
    </div>
    <div class="mb-3">
        <label for="type_id" class="form-label">type_id</label>
        <input type="text" value="<?= $product->getTypeId();  ?>" class="form-control" id="type_id" name="type_id" placeholder="Prix du produit">
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary mt-5">Valider</button>
    </div>
</form>
</div>

