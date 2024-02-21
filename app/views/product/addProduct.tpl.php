
<div class="container my-4">

<a href="<?= $router->generate('main-product')   ?>" class="btn btn-success float-end">Retour</a>

<h2>Ajouter un produit</h2>

<form action="" method="POST" class="mt-5">

    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" value="" class="form-control" id="name" name="name" placeholder="Nom du produit">
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
        <input type="text" value="" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png"
            aria-describedby="pictureHelpBlock">
        <small id="pictureHelpBlock" class="form-text text-muted">
            URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/"
                target="_blank">cette page</a>
        </small>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Prix</label>
        <input type="text" value="" class="form-control" id="price" name="price" placeholder="Prix du produit">
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary mt-5">Valider</button>
    </div>
</form>
</div>

