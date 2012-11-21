<div id="forms_errors" style="color: red;">
    <?php include_partial('daids_recap/itemFormErrors', array('form' => $form)) ?>
</div>

<div id="colonnes_dr">
    <?php include_partial('daids_recap/itemHeader', array('config_lieu' => $config_lieu, 'configurationDAIDS' => $configurationDAIDS)); ?>    
    <div id="col_saisies">
        <script type="text/javascript">
            /* Colonne avec le focus par défaut */
            var colFocusDefaut = 1;
        </script>

        <div id="col_saisies_cont">
            <?php $produits = $produits->getRawValue(); krsort($produits); foreach ($produits as $produit): ?>
                    <?php
                    include_component('daids_recap', 'itemForm', array('produit' => $produit,
                        'config_lieu' => $config_lieu,
                        'detail' => $produit,
                    	'active' => ($detail && $detail->getHash() == $produit->getHash()),
                		'configurationDAIDS' => $configurationDAIDS,
                        'form' => $form));
                    ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>