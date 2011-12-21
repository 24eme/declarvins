<div id="forms_errors" style="color: red;">
    <?php include_partial('drm_recap/itemFormErrors', array('form' => $form)) ?>
</div>
<div id="saving_notification" style="display: none; position: fixed; top: 0; left: 50%; background: #114C8D; color: #fff">
    Sauvegarde en cours...
</div>
<div id="error_notification" style="display: none; position: fixed; top: 0; left: 50%; background: #900000; color: #fff">
    Il existe des erreurs !
</div>

<div id="colonnes_dr">
    <?php include_partial('drm_recap/itemHeader'); ?>    
    <div id="col_saisies">
        <script type="text/javascript">
            /* Colonne avec le focus par défaut */
            var colFocusDefaut = null;
        </script>

        <div id="col_saisies_cont">
            <?php foreach ($drm_appellation->getDocument()->produits->get($drm_appellation->getLabel()->getKey()) as $appellation): ?>
            	<?php foreach ($drm_appellation->getDocument()->produits->get($drm_appellation->getLabel()->getKey())->get($appellation->getKey()) as $produit): ?>
                	<?php if ($produit->getAppellation()->getKey() == $drm_appellation->getKey() && !$produit->stock_vide): ?>
                    	<?php include_component('drm_recap', 'itemForm', array('produit' => $produit, 'detail' => $produit->getDetail(), 'form' => $form)); ?>
                	<?php endif; ?>
            	<?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <a class="btn_ajouter" href="<?php echo url_for('drm_recap_ajout', $config_appellation); ?>">Ajouter Dénomination</a>
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
