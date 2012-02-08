<div id="forms_errors" style="color: red;">
    <?php include_partial('drm_recap/itemFormErrors', array('form' => $form)) ?>
</div>
<div id="saving_notification" style="display: none; position: fixed; top: 0; left: 50%; background: #114C8D; color: #fff">
    Sauvegarde en cours...
</div>
<div id="error_notification" style="display: none; position: fixed; top: 0; left: 50%; background: #900000; color: #fff">
    Il existe des erreurs !
</div>

<!-- C'est degelousse -->
 <p style="text-align:right; padding-bottom: 4px;">
 <a href="<?php //echo url_for('drm_mouvements_generaux_product_form', $config_appellation) ?>" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_detail"  data-popup-config="configAjoutProduit">Ajouter un produit</a>
 </p>

<div id="colonnes_dr">
    <?php include_partial('drm_recap/itemHeader', array('config_appellation' => $config_appellation)); ?>    
    <div id="col_saisies">
		<script type="text/javascript">
			/* Colonne avec le focus par défaut */
			var colFocusDefaut = 1;
		</script>

        <div id="col_saisies_cont">
            <?php foreach ($produits as $produit): ?>
                    	<?php include_component('drm_recap', 'itemForm', array('produit' => $produit,
                                                                               'config_appellation' => $config_appellation,
                                                                               'detail' => $produit->getDetail(), 
                                                                               'form' => $form)); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.form_detail').submit( function () {
            $.post($(this).attr('action'), $(this).serializeArray(), 
            function (data) {
                $('#saving_notification').hide();
                if(!data.success) {
                    $('#error_notification').show();
                }
                $('#forms_errors').html(data.content);
            }, "json");
            $('#error_notification').hide();
            $('#saving_notification').show();
            return false;
        });
    })
</script>
