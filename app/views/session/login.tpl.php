<div class="container my-4">

	<div id="login-row" class="row justify-content-center align-items-center">

		<div id="login-column" class="col-md-6">

			<h2>Login</h2>

			<?php require __DIR__ . '/../partials/messages.tpl.php'; ?>

			<div class="box">
				<div class="float">
					<form class="form" action="" method="POST">
						<div class="form-group mt-4">
							<label for="username">E-mail:</label><br>
							<input type="text" name="email" id="username" class="form-control" required>
						</div>
						<div class="form-group mb-4 mt-4">
							<label for="password">Mot de passe :</label><br>
							<input type="password" name="password" id="password" class="form-control" required>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-info btn-md" value="Connexion">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>