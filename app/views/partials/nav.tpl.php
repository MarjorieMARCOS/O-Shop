<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

		<div class="container-fluid">

			<a class="navbar-brand" href="<?= $router->generate('main-home') ?>">oShop</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'main')? 'active': ''; ?>" href="<?= $router->generate('main-home') ?>">Accueil
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'category')? 'active': ''; ?>" href="<?= $router->generate('category-list') ?>">Catégories
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= str_contains($viewName, 'product')? 'active': ''; ?>" href="<?= $router->generate('product-list') ?>">Produits
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link " href="#">Types</a>
					</li>
					<li class="nav-item">
						<a class="nav-link " href="#">Marques</a>
					</li>
					<li class="nav-item">
						<a class="nav-link " href="#">Tags</a>
					</li>
					<li class="nav-item">
						<a class="nav-link " href="#">Sélection Accueil</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>