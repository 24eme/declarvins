<div class="tableau_ajouts_liquidations">
    <?php if(!$drm->declaration->hasMouvement()): ?>
        <p>Pas de mouvement pour l'ensemble des produits</p>
    <?php endif; ?>
    <?php if($drm->declaration->hasStockEpuise()): ?>
        <p>Stock épuisé pour l'ensemble des produits</p>
    <?php endif; ?>
</div>