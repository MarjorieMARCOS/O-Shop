<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

	<div class="container-fluid">

		<a class="navbar-brand" href="<?= $router->generate('main-home'); ?>">oShop</a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">

				<?php if (null != $_SESSION['User']) : ?>

					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'main') ? 'active' : ''; ?>" href="<?= $router->generate('main-home'); ?>">Accueil
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'category') && !str_contains($viewName, 'home-order') ? 'active' : ''; ?>" href="<?= $router->generate('category-list'); ?>">Catégories
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'product') ? 'active' : ''; ?>" href="<?= $router->generate('product-list'); ?>">Produits
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'type') ? 'active' : ''; ?>" href="<?= $router->generate('type-list'); ?>">Types</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'brand') ? 'active' : ''; ?>" href="<?= $router->generate('brand-list'); ?>">Marques</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'tag') ? 'active' : ''; ?>" href="<?= $router->generate('tag-list'); ?>">Tags</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'category/home-orde') ? 'active' : ''; ?>" href="<?= $router->generate('category-home-order'); ?>">Catégories de l'accueil</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'type/footer-order') ? 'active' : ''; ?>" href="<?= $router->generate('type-footer-order'); ?>">Type de l'accueil</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'brand/footer-order') ? 'active' : ''; ?>" href="<?= $router->generate('brand-footer-order'); ?>">Marque de l'accueil</a>
					</li>

					<?php if($_SESSION['User']->isAdmin()): ?>
						<li class="nav-item">
							<a class="nav-link <?= str_contains($viewName, 'user') ? 'active' : ''; ?>" href="<?= $router->generate('user-list') ?>">Utilisateurs</a>
						</li>
					<?php endif; ?>

				<?php endif; ?>
			</ul>

			<div class="d-flex">
				<ul class="navbar-nav mr-auto">
					<span class="navbar-brand"> </span>

					<?php if (null === $_SESSION['User']) : ?>
						<li class="nav-item">
							<a class="nav-link" href="<?= $router->generate('session-login') ?>">Login</a>
						</li>
					<?php else: ?>
						<li class="nav-item">
							<a class="nav-link" href="<?= $router->generate('session-logout') ?>"><?= $_SESSION['User']->getFirstname(); ?> - logout</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>

		</div>
	</div>
</nav>