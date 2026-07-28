<?php
// Le tableau complet avec TOUS les produits du cours
$produits = [
    [
        "reference" => "PHN001",
        "libelle" => "Téléphone portable iPhone 13",
        "description" => "Smartphone haut de gamme avec écran Super Retina XDR et triple appareil photo.",
        "stock" => 0,
        "prix" => 1099.99,
        "categorie" => "Téléphones",
        "photo" => "phone2.jpg"
    ],
    [
        "reference" => "PHN002",
        "libelle" => "Samsung Galaxy S21",
        "description" => "Smartphone Android avec écran Dynamic AMOLED et caméra triple ultra-haute résolution.",
        "stock" => 35,
        "prix" => 899.99,
        "categorie" => "Téléphones",
        "photo" => "phone3.jpg"
    ],
    [
        "reference" => "TEL001",
        "libelle" => "Téléviseur LED Sony 65 pouces",
        "description" => "Téléviseur 4K HDR avec écran LED et processeur X1 Ultimate pour des images exceptionnelles.",
        "stock" => 20,
        "prix" => 1499.99,
        "categorie" => "Télévisions",
        "photo" => "tele1.jpg"
    ],
    [
        "reference" => "AUD001",
        "libelle" => "Casque sans fil Sony WH-1000XM4",
        "description" => "Casque audio Bluetooth avec réduction de bruit active et qualité audio exceptionnelle.",
        "stock" => 40,
        "prix" => 349.99,
        "categorie" => "Audio",
        "photo" => "casque.jpg"
    ],
    [
        "reference" => "VID001",
        "libelle" => "Caméra Sony Alpha A7 III",
        "description" => "Appareil photo hybride plein format avec 24,2 mégapixels et stabilisation d'image intégrée.",
        "stock" => 15,
        "prix" => 1999.99,
        "categorie" => "Appareils photo",
        "photo" => "camera1.jpg"
    ],
    [
        "reference" => "COM001",
        "libelle" => "Ordinateur de bureau Dell XPS 8940",
        "description" => "PC de bureau puissant avec processeur Intel Core i7 et carte graphique NVIDIA GeForce RTX 3060.",
        "stock" => 25,
        "prix" => 1599.99,
        "categorie" => "Ordinateurs de bureau"
    ],
    [
        "reference" => "LAP001",
        "libelle" => "Ordinateur portable MacBook Pro 14 pouces",
        "description" => "Ordinateur portable Apple avec processeur M1 Pro, écran Retina XDR et jusqu'à 64 Go de RAM.",
        "stock" => 30,
        "prix" => 1999.99,
        "categorie" => "Ordinateurs portables",
        "photo" => "portable1.jpg"
    ],
    [
        "reference" => "TAB001",
        "libelle" => "Tablette Apple iPad Air (2022)",
        "description" => "Tablette Apple avec écran Liquid Retina, puce A15 Bionic et compatibilité avec Apple Pencil (2e génération).",
        "stock" => 20,
        "prix" => 599.99,
        "categorie" => "Tablettes",
        "photo" => "tablet1.jpg"
    ],
    [
        "reference" => "PHN003",
        "libelle" => "OnePlus 9 Pro",
        "description" => "Smartphone Android avec écran Fluid AMOLED et caméra Hasselblad quadripartite.",
        "stock" => 30,
        "prix" => 999.99,
        "categorie" => "Téléphones",
        "photo" => "phone4.jpg"
    ],
    [
        "reference" => "TEL002",
        "libelle" => "Téléviseur QLED Samsung 55 pouces",
        "description" => "Téléviseur 4K QLED avec Quantum HDR, processeur Quantum 4K et technologie Ambient Mode+.",
        "stock" => 20,
        "prix" => 1199.99,
        "categorie" => "Télévisions",
        "photo" => "tele2.jpg"
    ],
    [
        "reference" => "AUD002",
        "libelle" => "Écouteurs sans fil Apple AirPods Pro",
        "description" => "Écouteurs True Wireless avec réduction active du bruit et résistance à l'eau et à la transpiration.",
        "stock" => 50,
        "prix" => 249.99,
        "categorie" => "Audio",
        "photo" => "airpod.jpg" // Corrigé ici avec le 'r' pour coller à ton fichier !
    ],
    [
        "reference" => "VID002",
        "libelle" => "Caméra GoPro HERO10 Black",
        "description" => "Caméra d'action avec vidéo 5,3K à 60 ips, HyperSmooth 4.0 et fonction de streaming en direct.",
        "stock" => 10,
        "prix" => 499.99,
        "categorie" => "Appareils photo",
        "photo" => "gopro.jpg"
    ],
    [
        "reference" => "COM002",
        "libelle" => "Ordinateur de bureau HP Pavilion Gaming",
        "description" => "PC de bureau pour le jeu avec processeur Intel Core i5, carte graphique NVIDIA GeForce GTX 1660 et 8 Go de RAM.",
        "stock" => 15,
        "prix" => 899.99,
        "categorie" => "Ordinateurs de bureau",
        "photo" => "ordi1.jpg"
    ],
    [
        "reference" => "LAP002",
        "libelle" => "Ordinateur portable ASUS ZenBook 14",
        "description" => "Ultraportable avec processeur Intel Core i7, écran NanoEdge Full HD et 16 Go de RAM.",
        "stock" => 25,
        "prix" => 1299.99,
        "categorie" => "Ordinateurs portables",
        "photo" => "portable2.jpg"
    ],
    [
        "reference" => "TAB002",
        "libelle" => "Tablette Samsung Galaxy Tab S7+",
        "description" => "Tablette Android avec écran Super AMOLED de 12,4 pouces, S Pen inclus et processeur Qualcomm Snapdragon 865+.",
        "stock" => 20,
        "prix" => 849.99,
        "categorie" => "Tablettes"
    ]
];

include 'produitsTrier.php';
?>

<div class="container">
    <h1>Notre Catalogue Tech</h1>

    <div class="catalog-layout">
        <aside class="filters-panel">
            <h2>Filtres</h2>
            <form method="get" action="index.php" class="filters-form">
                <label for="categorie">Categorie</label>
                <select name="categorie" id="categorie">
                    <option value="">Toutes les categories</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>" <?= $categorieSelectionnee === $categorie ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="stock">Disponibilite</label>
                <select name="stock" id="stock">
                    <option value="all" <?= $stockSelectionne === 'all' ? 'selected' : '' ?>>Tous</option>
                    <option value="in" <?= $stockSelectionne === 'in' ? 'selected' : '' ?>>En stock</option>
                    <option value="out" <?= $stockSelectionne === 'out' ? 'selected' : '' ?>>Rupture</option>
                </select>

                <label for="tri">Trier par</label>
                <select name="tri" id="tri">
                    <option value="prix_asc" <?= $triSelectionne === 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="prix_desc" <?= $triSelectionne === 'prix_desc' ? 'selected' : '' ?>>Prix decroissant</option>
                    <option value="nom_asc" <?= $triSelectionne === 'nom_asc' ? 'selected' : '' ?>>Nom A-Z</option>
                    <option value="nom_desc" <?= $triSelectionne === 'nom_desc' ? 'selected' : '' ?>>Nom Z-A</option>
                </select>

                <div class="filters-actions">
                    <button type="submit" class="btn-add">Appliquer</button>
                    <a href="index.php" class="filters-reset">Reinitialiser</a>
                </div>
            </form>
        </aside>

        <section>
            <p class="filters-result"><?= count($produitsFiltres) ?> produit(s) trouve(s)</p>
            <div class="products-grid">
                <?php foreach ($produitsFiltres as $produit): ?>
                    <?php include 'vues/templates/product.php'; ?>
                <?php endforeach; ?>
            </div>

            <?php if (count($produitsFiltres) === 0): ?>
                <p class="filters-empty">Aucun produit ne correspond a vos filtres.</p>
            <?php endif; ?>
        </section>
    </div>
</div>