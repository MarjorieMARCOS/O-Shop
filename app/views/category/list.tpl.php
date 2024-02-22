<div class="container my-4">

	<?php
	// dump($categoryList);
	?>

	<a href="<?= $router->generate('category-add') ?>" class="btn btn-success float-end">Ajouter</a>
	
	<h2>Liste des catégories</h2>

	<table class="table table-striped table-hover mt-4">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Nom</th>
				<th scope="col">Sous-titre</th>
				<th scope="col">Picture</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($categoryList as $category) : ?>
				<!-- Categorie -->
				<tr>
					<th scope="row"><?= $category->getId(); ?></th>
					<td><?= $category->getName(); ?></td>
					<td><?= $category->getSubtitle(); ?></td>
					<td><?= $category->getPicture(); ?></td>
					<td class="text-end">
						<a href="<?= $router->generate('category-update', ['id' => $category->getId()]); ?>" class="btn btn-sm btn-warning">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						</a>
						<!-- Example single danger button -->
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-trash-o" aria-hidden="true"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#">Oui,
									je veux supprimer</a>
								<a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>