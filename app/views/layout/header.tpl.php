<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>oShop BackOffice | </title>

	<!-- Getting bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<!--
        And getting Font Awesome 4.7 (free)
        To get HTML code icons : https://fontawesome.com/v4.7.0/icons/
    -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
		integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

	<!-- We can still have our own CSS file -->
	<link rel="stylesheet" href="/assets/css/style.css">
</head>

<?php
// On inclut des sous-vues => "partials"
include __DIR__ . '/../partials/nav.tpl.php';
?>

<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

		<div class="container-fluid">

			<a class="navbar-brand" href="index.html">oShop</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" href="<?= $router->generate('main-home') ?>">Accueil
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link " href="<?= $router->generate('main-category') ?>">Catégories
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link " href="<?= $router->generate('main-product') ?>">Produits
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