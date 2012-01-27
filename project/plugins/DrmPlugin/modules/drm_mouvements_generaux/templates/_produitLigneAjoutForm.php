<div id="popup_ajout_produit_<?php echo $certification ?>" class="popup_contenu">
	<form  class="popup_form" id="subForm" action="<?php echo url_for(array('sf_route' => 'drm_mouvements_generaux_product_form','certification' => $certification)) ?>" method="post">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<input type="hidden" name="certification" value="<?php echo $certification ?>" />
		<div class="ligne_form">
			<?php echo $form['produit']->renderLabel() ?>
			<?php echo $form['produit']->render() ?>
			<span class="error"><?php echo $form['produit']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['label']->renderLabel() ?>
			<div style="width: 240px; height: 100px; display: inline-block; overflow-x: hidden; overflow-y: scroll;">
				<?php echo $form['label']->render(array('class' => 'select_multiple')) ?>
			</div>
			<span class="error"><?php echo $form['label']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['label_supplementaire']->renderLabel() ?>
			<?php echo $form['label_supplementaire']->render() ?>
			<span class="error"><?php echo $form['label_supplementaire']->renderError() ?></span>
		</div>
		<div class="ligne_form_btn">
			<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
			<button name="valider" class="btn_valider" type="submit">Valider</button>
		</div>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function () {
		var produits = JSON.parse($("#<?php echo $form['produit']->renderId() ?>").attr('autocomplete-data'));
		$("#<?php echo $form['produit']->renderId() ?>").autocomplete({
			minLength: 0,
			source: produits,
			focus: function(event, ui)
	        {
	        	$('#<?php echo $form['produit']->renderId() ?>').val(ui.item[1] + ' ' + ui.item[3] + ' ' + ui.item[5] + ' ' + ui.item[7]);
	        	$('#<?php echo $form['appellation']->renderId() ?>').val(ui.item[0]);
	        	$('#<?php echo $form['couleur']->renderId() ?>').val(ui.item[2]);
	        	$('#<?php echo $form['cepage']->renderId() ?>').val(ui.item[4]);
	        	$('#<?php echo $form['millesime']->renderId() ?>').val(ui.item[6]);
				
	            return false;
	        },
	        select: function(event, ui)
	        {
	        	$('#<?php echo $form['produit']->renderId() ?>').val(ui.item[1] + ' ' + ui.item[3] + ' ' + ui.item[5] + ' ' + ui.item[7]);
	        	$('#<?php echo $form['appellation']->renderId() ?>').val(ui.item[0]);
	        	$('#<?php echo $form['couleur']->renderId() ?>').val(ui.item[2]);
	        	$('#<?php echo $form['cepage']->renderId() ?>').val(ui.item[4]);
	        	$('#<?php echo $form['millesime']->renderId() ?>').val(ui.item[6]);
					
	            return false;
	        }
		});	
		$("#<?php echo $form['produit']->renderId() ?>").data('autocomplete')._renderItem = function(ul, item)
	    {
	        var tab = item['value'].split('|@');
	        return $('<li></li>')
	        .data("item.autocomplete", tab)
	        .append('<a><span class="appellation"><strong>'+tab[1]+'</strong></span> <span class="couleur">'+tab[3]+'</span> <span class="cepage">'+tab[5]+'</span> <span class="millesime">'+tab[7]+'</span></a>' )
	        .appendTo(ul);
	    };
});
</script>