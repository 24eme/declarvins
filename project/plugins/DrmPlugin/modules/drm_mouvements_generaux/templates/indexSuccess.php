<script type="text/javascript">
    $(document).ready( function()
    {
        $('.showForm').click(function() {
            $('.showForm').hide();
            $.get(
            	$(this).attr('href'), 
            	{certification: $(this).attr('id')},
            	function(data) {
                	$('#form').html(data.content);
            	}
        	);
        	return false;
        });
        $('.closeForm').live('click', function() {
            $('#form').html('');
            $('.showForm').show();
        });
        $('#subForm').live('submit', function () {
            $.post($(this).attr('action'), $(this).serializeArray(),
            	function (data) {
                	if(data.success) {
                    	document.location.href = data.url;
                	} else {
                		$('#form').html(data.content);
                	}
            	}, "json"
            );
            $('.addRow').show();
            return false;
        });
        $('#forms :checkbox').change(function() {
            $(this).parents('form').submit();
        });
        $('.updateProduct').submit(function() {
        	$.post($(this).attr('action'), $(this).serializeArray());
        	return false;
        });
    })
</script>

<?php include_partial('global/navTop'); ?>

<section id="contenu">
    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'ajouts-liquidations', 'pourcentage' => '10')); ?>

    <section id="principal">

        <div id="contenu_onglet">
        	<?php if ($sf_user->hasFlash('notice')): ?>
        		<p><?php echo $sf_user->getFlash('notice') ?></p>
        	<?php endif; ?>
        	<div id="form" style="padding:10px 0;">
        	
        	</div>
        	<div id="forms">
        		<?php foreach ($forms as $certification => $tabForm): ?>
	            <div style="margin-bottom:30px;">
	                    <h2 style="font-size: 16px;"><?php echo $certificationLibelle[$certification] ?></h2>
	                    <table class="table_mouv" width="100%">
	                        <tr>
	                            <th width="240">Appellation</th>
	                            <th width="100">Couleur</th>
	                            <th width="150">Dénomination</th>
	                            <th width="100">Label</th>
	                            <th width="80">Disponible</th>
	                            <th width="80">Stock vide</th>
	                            <th width="80">Pas de mouvement</th>
	                        </tr>
	                        <?php
	                        if ($tabForm):
	                            foreach ($tabForm as $form):
	                                ?>
	                                <?php include_partial('produitLigneModificationForm', array('form' => $form)) ?>
	                                <?php
	                            endforeach;
	                        endif;
	                        ?>
	                    </table>
	                    <a href="<?php echo url_for('drm_mouvements_generaux_product_form') ?>" class="showForm " id="<?php echo $certification ?>" style="display: inline-block;width:100%;text-align:right;">Ajouter un nouveau produit</a>
	            </div>
	            <?php endforeach; ?>
            </div>
        </div>
        <div id="btn_etape_dr">
            <a href="<?php echo url_for('@drm_informations') ?>" class="btn_prec">Précédent</a>
            <a id="nextStep" href="<?php echo url_for('drm_recap', ConfigurationClient::getCurrent()->declaration->labels->AOP) ?>" class="btn_suiv">Suivant</a>
        </div>

    </section>
</section>