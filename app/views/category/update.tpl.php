<?php 
$category = $viewData['category'];

?>

<div class="container my-4">
    
<a href="<?= $router->generate('category-list')   ?>" class="btn btn-success float-end">Retour</a>

<h2>Mettre à jour une catégorie</h2>

<form action="<?= $router->generate('category-modify');   ?>" method="POST" class="mt-5">
<div class="mb-3">
        <label for="name" class="form-label">ID numéro : <?= $category->getId();  ?></label>
        <input type="hidden" name="id" value="<?= $category->getId();  ?>">
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" value="<?= $category->getName();  ?>" class="form-control" id="name" name="name" placeholder="Nom de la catégorie">
    </div>
    <div class="mb-3">
        <label for="subtitle" class="form-label">Sous-titre</label>
        <input type="text" value="<?= $category->getSubtitle();  ?>" class="form-control" id="subtitle" name="subtitle" placeholder="Sous-titre"
            aria-describedby="subtitleHelpBlock">
        <small id="subtitleHelpBlock" class="form-text text-muted">
            Sera affiché sur la page d'accueil comme bouton devant l'image
        </small>
    </div>
    <div class="mb-3">
        <label for="picture" class="form-label">Picture</label>
        <input type="text" value="<?= $category->getPicture();  ?>" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png"
            aria-describedby="pictureHelpBlock">
        <small id="pictureHelpBlock" class="form-text text-muted">
            URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/"
                target="_blank">cette page</a>
        </small>
    </div>
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary mt-5">Valider</button>
    </div>
</form>
</div>

