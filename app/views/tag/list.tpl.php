<div class="container my-4">
	<a href="<?= $router->generate('tag-add'); ?>" class="btn btn-success float-end">Ajouter</a>
	<h2>Liste des tags</h2>

	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<table class="table table-striped table-sm table-hover mt-4">
		
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Nom</th>
				<th scope="col"></th>
			</tr>
		</thead>
		
		<tbody>
			<!-- Categorie -->
			<?php foreach ($tagList as $tag) : ?>
				<tr>
					<th scope="row"><?= $tag->getId(); ?></th>
					<td><?= $tag->getName(); ?></td>
					<td class="col-md-1">
						<a href="<?= $router->generate('tag-edit', ['id' => $tag->getId()]); ?>" class="m-1 btn btn-sm btn-warning">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						</a>
						<!-- Example single danger button -->
						<div class="btn-group">
							<button type="button" class="m-1 btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-trash-o" aria-hidden="true"></i>
							</button>
							
							<div class="dropdown-menu">
								<a class="dropdown-item" href="<?= $router->generate('tag-delete', ['id' => $tag->getId()]) . '?tokenCSRF=' . $tokenCSRF; ?>">Oui, je veux supprimer</a>
								<a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			<!-- Categorie -->
		</tbody>
	</table>
</div>