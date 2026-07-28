<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'appController' . DIRECTORY_SEPARATOR . 'appController.php';

$filters = getSelectedCatalogueFilters($_GET);
$categorieSelectionnee = $filters['categorie'];
$stockSelectionne = $filters['stock'];
$triSelectionne = $filters['tri'];

$categories = getProductCategories($produits);
$produitsFiltres = filterProducts($produits, $categorieSelectionnee, $stockSelectionne);
sortProducts($produitsFiltres, $triSelectionne);
