<script type="text/javascript">
    $(document).ready( function()
    {
        $('#subForm').live('submit', function () {
            var id = $(this).parents('div').attr('id');
            $.post($(this).attr('action'), $(this).serializeArray(),
            	function (data) {
                	if(data.success) {
                    	document.location.href = data.url;
                	} else {
                		$('#'+id).html(data.content);
                		var linkAction = $('a[data-popup=#'+id+']');
                		$.initPopup(linkAction);
                	}
            	}, "json"
            );
            return false;
        });
    })
</script>
<?php include_partial('global/navTop', array('active' => 'drm')); ?>
<section id="contenu">

    <?php include_partial('drm/header'); ?>
    <?php include_component('drm', 'etapes', array('etape' => 'vrac', 'pourcentage' => '30')); ?>

    <section id="principal">
		<div id="application_dr">
			<div id="contenu_onglet">
				<?php if ($details->count() > 0): ?>
				<table width="100%">
					<thead>
						<tr>
							<th align="left">Produit</th>
							<th width="150px">Contrat</th>
							<th width="200px">Volume</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($details as $detail): ?>
						<?php include_partial('addContrat', array('detail' => $detail)) ?>	
						<?php
							if (isset($forms[$detail->getIdentifiant()])) { 
								foreach ($forms[$detail->getIdentifiant()] as $form) {
									include_partial('itemContrat', array('form' => $form));
								}
							}
						?>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php endif; ?>
			</div>
			<div id="btn_etape_dr">
	            <a href="<?php echo url_for('drm_mouvements_generaux') ?>" class="btn_prec">Précédent</a>
	            <a id="nextStep" href="<?php echo url_for('drm_validation') ?>" class="btn_suiv">Suivant</a>
	        </div>
		</div>
	</section>
</section>