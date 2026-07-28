<?php
declare(strict_types=1);

function esc(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getSelectedCatalogueFilters(array $query): array
{
	$categorie = trim((string) ($query['categorie'] ?? ''));
	$stock = trim((string) ($query['stock'] ?? 'all'));
	$tri = trim((string) ($query['tri'] ?? 'prix_asc'));

	$stocksAutorises = ['all', 'in', 'out'];
	if (!in_array($stock, $stocksAutorises, true)) {
		$stock = 'all';
	}

	$trisAutorises = ['prix_asc', 'prix_desc', 'nom_asc', 'nom_desc'];
	if (!in_array($tri, $trisAutorises, true)) {
		$tri = 'prix_asc';
	}

	return [
		'categorie' => $categorie,
		'stock' => $stock,
		'tri' => $tri,
	];
}

function getProductCategories(array $produits): array
{
	$categories = array_values(array_unique(array_map(
		static fn (array $produit): string => (string) ($produit['categorie'] ?? ''),
		$produits
	)));

	sort($categories, SORT_NATURAL | SORT_FLAG_CASE);
	return $categories;
}

function filterProducts(array $produits, string $categorieSelectionnee, string $stockSelectionne): array
{
	return array_values(array_filter(
		$produits,
		static function (array $produit) use ($categorieSelectionnee, $stockSelectionne): bool {
			$categorieOk = $categorieSelectionnee === '' || ($produit['categorie'] ?? '') === $categorieSelectionnee;

			$enStock = (int) ($produit['stock'] ?? 0) > 0;
			$stockOk = true;
			if ($stockSelectionne === 'in') {
				$stockOk = $enStock;
			} elseif ($stockSelectionne === 'out') {
				$stockOk = !$enStock;
			}

			return $categorieOk && $stockOk;
		}
	));
}

function sortProducts(array &$produits, string $triSelectionne): void
{
	switch ($triSelectionne) {
		case 'prix_desc':
			usort($produits, static fn (array $a, array $b): int => (float) $b['prix'] <=> (float) $a['prix']);
			break;
		case 'nom_asc':
			usort($produits, static fn (array $a, array $b): int => strcasecmp((string) $a['libelle'], (string) $b['libelle']));
			break;
		case 'nom_desc':
			usort($produits, static fn (array $a, array $b): int => strcasecmp((string) $b['libelle'], (string) $a['libelle']));
			break;
		case 'prix_asc':
		default:
			usort($produits, static fn (array $a, array $b): int => (float) $a['prix'] <=> (float) $b['prix']);
			break;
	}
}
