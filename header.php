<?php
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$cssHref = ($basePath !== '' ? $basePath : '') . '/style.css';
$cssVersion = file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'style.css')
    ? (string) filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'style.css')
    : (string) time();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop</title>
    <!-- On lie notre fichier de style CSS -->
    <link rel="stylesheet" href="<?= htmlspecialchars($cssHref, ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($cssVersion, ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <header class="navbar">
        <div class="logo">💻 TechShop</div>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="#">Boutique</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>