<div id="forms_errors" style="color: red;">
    <?php include_partial('drm_recap/itemFormErrors', array('form' => $form)) ?>
</div>

<!-- C'est degelousse -->
<p style="text-align:right; padding-bottom: 4px;">
    <a href="<?php echo url_for('drm_recap_ajout_ajax', $drm_lieu) ?>" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_detail" data-popup-config="configForm">Ajouter un produit</a>
</p>
<h1>En droits suspendus</h1>
<div id="colonnes_dr">
    <?php include_partial('drm_recap/itemHeader', array('config_lieu' => $config_lieu, 'acquittes' => false)); ?>    
    <div id="col_saisies">
        <script type="text/javascript">
            /* Colonne avec le focus par défaut */
            var colFocusDefaut = 1;
        </script>

        <div id="col_saisies_cont">
            <?php $produits = $produits->getRawValue(); krsort($produits); foreach ($produits as $produit): ?>
                <?php if ($produit->hasMouvementCheck()): ?>
                    <?php
                    include_component('drm_recap', 'itemForm', array('produit' => $produit,
                        'config_lieu' => $config_lieu,
                        'detail' => $produit,
                    	'active' => ($detail && $detail->getHash() == $produit->getHash()),
                        'form' => $form
                    ));
                    ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<h1>En droits acquittés</h1>
<div id="colonnes_dr_acq">
    <?php include_partial('drm_recap/itemHeader', array('config_lieu' => $config_lieu, 'acquittes' => true)); ?>    
    <div id="col_saisies_acq">
        <div id="col_saisies_cont_acq">
            <?php foreach ($produits as $produit): ?>
                <?php if ($produit->hasMouvementCheck()): ?>
                    <?php
                    include_component('drm_recap', 'itemFormAcq', array('produit' => $produit,
                        'config_lieu' => $config_lieu,
                        'detail' => $produit,
                    	'active' => ($detail && $detail->getHash() == $produit->getHash()),
                        'form' => $form
                    ));
                    ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>