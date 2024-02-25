
<div class="container my-4">

<a href="<?= $router->generate('main-home')   ?>" class="btn btn-success float-end">Retour</a>

<h2>Ajouter une catégorie</h2>

<form action="" method="POST" class="mt-5">

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" value="" class="form-control" id="email" name="email" placeholder="Email de connexion">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="text" value="" class="form-control" id="password" name="password" placeholder="Mot de passe">

    </div>
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary mt-5">Valider</button>
    </div>
</form>
</div>