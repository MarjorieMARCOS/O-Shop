<div class="container my-4">

	<a href="<?= $router->generate('type-add'); ?>" class="btn btn-success float-end">Ajouter</a>

	<h2>Liste des types</h2>

	<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

	<table class="table table-striped table-hover mt-4">
		<thead>
			<tr>
				<th class="w-25" scope="col">#</th>
				<th class="w-50" scope="col">Nom</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($typeList as $type) : ?>
				<!-- Product -->
				<tr>
					<th class="w-25" scope="row"><?= $type->getId(); ?></th>
					<td class="w-50"><?= $type->getName(); ?></td>
					<td class="text-end">
						<a href="<?= $router->generate('type-edit', ['id' => $type->getId()]); ?>" class="btn btn-sm btn-warning">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						</a>
						<!-- Example single danger button -->
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-trash-o" aria-hidden="true"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="<?= $router->generate('type-delete', ['id' => $type->getId()]); ?>?tokenCSRF=<?= $tokenCSRF; ?>">Oui,
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