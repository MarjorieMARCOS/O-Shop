
<?php

$categoryList = $viewData['categoryList'];

?>

<div class="container my-4">
		<a href="<?= $router->generate('main-category-ajouter')   ?>" class="btn btn-success float-end">Ajouter</a>
		
		<h2>Liste des catégories</h2>

		<table class="table table-striped table-hover mt-4">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nom</th>
					<th scope="col">Sous-titre</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<!-- Categorie -->
				<?php  foreach($categoryList as $category):   ?>
					<tr>
						<th scope="row"><?= $category->getId();   ?></th>
						<td><?= $category->getName();   ?></td>
						<td><?= ($category->getSubtitle() !== null)? $category->getSubtitle(): 'Pas de sous-titre en BDD' ;   ?></td>
						<td class="text-end">
							<a href="<?= $router->generate('main-category-ajouter') ?>" class="btn btn-sm btn-warning">
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
							</a>
							<!-- Example single danger button -->
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item"
										href="<?= $router->generate('main-category', ['id' => $category->getId()]) ?>">Oui,
										je veux supprimer</a>
									<a class="dropdown-item" href="<?= $router->generate('main-category', ['id' => $category->getId()]) ?>" data-toggle="dropdown">Oups !</a>
								</div>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
