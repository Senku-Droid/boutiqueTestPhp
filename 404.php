<?php
http_response_code(404);
include 'header.php';
?>

<div class="container">
	<h1>Erreur 404</h1>
	<p style="text-align: center; color: #666;">
		Page introuvable.
	</p>
	<a href="index.php" class="btn-add" style="max-width: 240px; margin: 24px auto 0;">
		Retour accueil
	</a>
</div>

<?php include 'footer.php'; ?>
