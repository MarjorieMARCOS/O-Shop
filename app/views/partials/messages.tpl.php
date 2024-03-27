<?php 

if (0 != sizeof($_SESSION['ErrorMessages'])) : ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<?php foreach ($_SESSION['ErrorMessages'] as $message) : ?>
			<div><?= $message; ?></div>
		<?php endforeach; ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php
	$_SESSION['ErrorMessages'] = [];
endif;
?>

<?php if (0 != sizeof($_SESSION['Notifications'])) : ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php foreach ($_SESSION['Notifications'] as $message) : ?>
			<div><?= $message; ?></div>
		<?php endforeach; ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php
	$_SESSION['Notifications'] = [];
endif;
?>