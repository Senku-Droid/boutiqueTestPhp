<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

include 'header.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'appController' . DIRECTORY_SEPARATOR . 'appController.php';

$errors = [];
$successMessage = '';
$fieldErrors = [
	'nom' => '',
	'prenom' => '',
	'email' => '',
	'message' => '',
	'consent' => ''
];

$formData = [
	'nom' => '',
	'prenom' => '',
	'email' => '',
	'message' => '',
	'consent' => '',
	'website' => ''
];

if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (empty($_SESSION['contact_form_started_at'])) {
	$_SESSION['contact_form_started_at'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$formData['nom'] = trim((string) ($_POST['nom'] ?? ''));
	$formData['prenom'] = trim((string) ($_POST['prenom'] ?? ''));
	$formData['email'] = trim((string) ($_POST['email'] ?? ''));
	$formData['message'] = trim((string) ($_POST['message'] ?? ''));
	$formData['consent'] = (string) ($_POST['consent'] ?? '');
	$formData['website'] = trim((string) ($_POST['website'] ?? ''));

	$postedToken = (string) ($_POST['csrf_token'] ?? '');
	if ($postedToken === '' || !hash_equals($_SESSION['csrf_token'], $postedToken)) {
		$errors[] = 'Session invalide. Merci de recharger la page et de réessayer.';
	}

	// Honeypot: if bots fill this hidden field, reject immediately.
	if ($formData['website'] !== '') {
		$errors[] = 'Envoi refuse.';
	}

	$elapsed = time() - (int) ($_SESSION['contact_form_started_at'] ?? time());
	if ($elapsed < 3) {
		$errors[] = 'Envoi trop rapide. Merci de réessayer dans quelques secondes.';
	}

	if ($formData['nom'] === '') {
		$fieldErrors['nom'] = 'Le nom ne peut pas etre vide.';
	} elseif (!preg_match('/^[\p{L} -\']{2,60}$/u', $formData['nom'])) {
		$fieldErrors['nom'] = 'Le nom est invalide (2 à 60 caractères).';
	}

	if ($formData['prenom'] === '') {
		$fieldErrors['prenom'] = 'Le prenom ne peut pas etre vide.';
	} elseif (!preg_match('/^[\p{L} -\']{2,60}$/u', $formData['prenom'])) {
		$fieldErrors['prenom'] = 'Le prenom est invalide (2 à 60 caracteres).';
	}

	if ($formData['email'] === '') {
		$fieldErrors['email'] = 'L\'e-mail ne peut pas etre vide.';
	} elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
		$fieldErrors['email'] = 'L\'adresse e-mail est invalide.';
	}

	if ($formData['message'] === '') {
		$fieldErrors['message'] = 'Le message ne peut pas etre vide.';
	} elseif (mb_strlen($formData['message']) < 15 || mb_strlen($formData['message']) > 2000) {
		$fieldErrors['message'] = 'Le message doit contenir entre 15 et 2000 caractères.';
	}

	if ($formData['consent'] !== '1') {
		$fieldErrors['consent'] = 'Vous devez accepter les conditions d\'utilisation.';
	}

	if (!isset($_SESSION['contact_rate_limit'])) {
		$_SESSION['contact_rate_limit'] = [];
	}

	$now = time();
	$_SESSION['contact_rate_limit'] = array_values(array_filter(
		$_SESSION['contact_rate_limit'],
		static fn (int $timestamp): bool => ($now - $timestamp) < 600
	));

	if (count($_SESSION['contact_rate_limit']) >= 5) {
		$errors[] = 'Trop de tentatives. Merci de réessayer dans quelques minutes.';
	}

	$hasFieldErrors = (bool) array_filter($fieldErrors, static fn (string $message): bool => $message !== '');

	if (!$errors && !$hasFieldErrors) {
		$_SESSION['contact_rate_limit'][] = $now;

		$storageDir = __DIR__ . DIRECTORY_SEPARATOR . 'asset' . DIRECTORY_SEPARATOR . 'messages';
		if (!is_dir($storageDir)) {
			mkdir($storageDir, 0755, true);
		}

		$htaccessPath = $storageDir . DIRECTORY_SEPARATOR . '.htaccess';
		if (!file_exists($htaccessPath)) {
			file_put_contents($htaccessPath, "Require all denied\n", LOCK_EX);
		}

		$indexPath = $storageDir . DIRECTORY_SEPARATOR . 'index.php';
		if (!file_exists($indexPath)) {
			file_put_contents($indexPath, "<?php http_response_code(403);\n", LOCK_EX);
		}

		$safePayload = [
			'date' => date('c'),
			'nom' => $formData['nom'],
			'prenom' => $formData['prenom'],
			'email' => $formData['email'],
			'message' => $formData['message']
		];

		$line = json_encode($safePayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
		$written = file_put_contents($storageDir . DIRECTORY_SEPARATOR . 'contact.log', $line, FILE_APPEND | LOCK_EX);

		if ($written === false) {
			$errors[] = 'Une erreur technique est survenue. Merci de réessayer plus tard.';
		} else {
			$successMessage = 'Merci, votre message a bien été envoyé.';

			$formData = [
				'nom' => '',
				'prenom' => '',
				'email' => '',
				'message' => '',
				'consent' => '',
				'website' => ''
			];

			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
			$_SESSION['contact_form_started_at'] = time();
		}
	}
}
?>

<main class="contact-page">
	<section class="contact-card" aria-labelledby="contact-title">
		<div class="contact-hero">
			<p class="contact-kicker">Support TechShop</p>
			<h1 id="contact-title">Contactez-nous</h1>
			<p class="contact-subtitle">Une question sur un produit ou une commande ? N'hésitez pas à nous écrire.</p>
		</div>

		<?php if ($errors): ?>
			<div class="contact-alert contact-alert-error" role="alert" aria-live="assertive">
				<?php foreach ($errors as $error): ?>
					<p><?= esc($error) ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ($successMessage !== ''): ?>
			<div class="contact-alert contact-alert-success" role="status" aria-live="polite">
				<p><?= esc($successMessage) ?></p>
			</div>
		<?php endif; ?>

		<div class="contact-layout">
			<form method="post" action="contact.php" class="contact-form" novalidate>
				<input type="hidden" name="csrf_token" value="<?= esc((string) $_SESSION['csrf_token']) ?>">

				<div class="contact-row">
					<div class="contact-field">
						<label for="nom">Nom</label>
						<input id="nom" name="nom" type="text" maxlength="60" autocomplete="family-name" required value="<?= esc($formData['nom']) ?>" placeholder="Votre nom" class="<?= $fieldErrors['nom'] !== '' ? 'input-error' : '' ?>" aria-invalid="<?= $fieldErrors['nom'] !== '' ? 'true' : 'false' ?>" aria-describedby="error-nom">
						<?php if ($fieldErrors['nom'] !== ''): ?>
							<p id="error-nom" class="contact-field-error"><?= esc($fieldErrors['nom']) ?></p>
						<?php endif; ?>
					</div>
					<div class="contact-field">
						<label for="prenom">Prénom</label>
						<input id="prenom" name="prenom" type="text" maxlength="60" autocomplete="given-name" required value="<?= esc($formData['prenom']) ?>" placeholder="Votre prénom" class="<?= $fieldErrors['prenom'] !== '' ? 'input-error' : '' ?>" aria-invalid="<?= $fieldErrors['prenom'] !== '' ? 'true' : 'false' ?>" aria-describedby="error-prenom">
						<?php if ($fieldErrors['prenom'] !== ''): ?>
							<p id="error-prenom" class="contact-field-error"><?= esc($fieldErrors['prenom']) ?></p>
						<?php endif; ?>
					</div>
				</div>

				<div class="contact-field">
					<label for="email">Adresse e-mail</label>
					<input id="email" name="email" type="email" maxlength="190" autocomplete="email" required value="<?= esc($formData['email']) ?>" placeholder="exemple@email.fr" class="<?= $fieldErrors['email'] !== '' ? 'input-error' : '' ?>" aria-invalid="<?= $fieldErrors['email'] !== '' ? 'true' : 'false' ?>" aria-describedby="error-email">
					<?php if ($fieldErrors['email'] !== ''): ?>
						<p id="error-email" class="contact-field-error"><?= esc($fieldErrors['email']) ?></p>
					<?php endif; ?>
				</div>

				<div class="contact-field">
					<label for="message">Votre message</label>
					<textarea id="message" name="message" rows="6" maxlength="2000" required placeholder="Décrivez votre demande (ex: référence, commande, problème)" class="<?= $fieldErrors['message'] !== '' ? 'input-error' : '' ?>" aria-invalid="<?= $fieldErrors['message'] !== '' ? 'true' : 'false' ?>" aria-describedby="error-message"><?= esc($formData['message']) ?></textarea>
					<?php if ($fieldErrors['message'] !== ''): ?>
						<p id="error-message" class="contact-field-error"><?= esc($fieldErrors['message']) ?></p>
					<?php endif; ?>
				</div>

				<div class="contact-honeypot" aria-hidden="true">
					<label for="website">Site web</label>
					<input id="website" name="website" type="text" tabindex="-1" autocomplete="off" value="<?= esc($formData['website']) ?>">
				</div>

				<div class="contact-consent-wrap">
					<label class="contact-consent">
						<input type="checkbox" name="consent" value="1" <?= $formData['consent'] === '1' ? 'checked' : '' ?> aria-invalid="<?= $fieldErrors['consent'] !== '' ? 'true' : 'false' ?>" aria-describedby="error-consent">
						<span>J'accepte les conditions d'utilisation.</span>
					</label>
					<?php if ($fieldErrors['consent'] !== ''): ?>
						<p id="error-consent" class="contact-field-error"><?= esc($fieldErrors['consent']) ?></p>
					<?php endif; ?>
				</div>

				<button type="submit" class="contact-submit">Envoyer le message</button>
			</form>

			<aside class="contact-aside" aria-label="Informations utiles">
				<h2>Besoin d'aide rapide ?</h2>
				<p>Notre équipe répond en général sous 24h ouvrables.</p>
				<ul>
					<li>Support commandes et livraisons</li>
					<li>Disponibilité des produits</li>
					<li>Retours et remboursements</li>
				</ul>
			</aside>
		</div>
	</section>
</main>

<?php include 'footer.php'; ?>
