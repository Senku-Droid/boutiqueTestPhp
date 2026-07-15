<div class="card">
    <!-- 1. L'image du produit (avec sécurité si l'image est manquante) -->
    <div class="card-image">
        <?php if (!empty($produit["photo"])): ?>
            <img src="asset/images/imagesProduits/<?= $produit["photo"] ?>" alt="<?= $produit["libelle"] ?>">
        <?php else: ?>
            <img src="https://placehold.co/300x200?text=Pas+d+image" alt="Image indisponible">
        <?php endif; ?>
    </div>

    <!-- 2. Les infos du produit -->
    <div class="card-body">
        <span class="category"><?= $produit["categorie"] ?></span>
        <h3><?= $produit["libelle"] ?></h3>
        <p class="description"><?= $produit["description"] ?></p>
    </div>

    <!-- 3. Le prix, le stock et le bouton -->
    <div class="card-footer">
        <div class="price"><?= number_format($produit["prix"], 2, ',', ' ') ?> €</div>
        <?php $hasStock = (int) $produit["stock"] > 0; ?>
        
        <!-- Condition pour le stock -->
        <?php if ($hasStock): ?>
            <p class="stock in-stock"><span class="stock-dot" aria-hidden="true"></span>En stock <span class="stock-count"><?= $produit["stock"] ?></span></p>
        <?php else: ?>
            <p class="stock out-of-stock"><span class="stock-dot" aria-hidden="true"></span>Rupture de stock</p>
        <?php endif; ?>

        <?php if ($hasStock): ?>
            <a href="#" class="btn-add">Ajouter au panier</a>
        <?php else: ?>
            <button type="button" class="btn-add btn-disabled" disabled aria-disabled="true">Indisponible</button>
        <?php endif; ?>
    </div>
</div>