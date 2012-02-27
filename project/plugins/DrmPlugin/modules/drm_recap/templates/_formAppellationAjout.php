<form id="form_appellation_ajout" method="post" action="<?php echo url_for('drm_recap_appellation_ajout_ajax', $certification) ?>" class="popup_form" data-popup-success="popup" data-popup="#popup_ajout_detail" data-popup-config="configForm" data-popup-titre="Ajouter un produit">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<?php echo $form['appellation_autocomplete']->renderLabel() ?> 
		<?php echo $form['appellation_autocomplete']->render() ?>
		<span class="error"><?php echo $form['appellation_autocomplete']->renderError() ?></span>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function () {
		var produits = JSON.parse($("#<?php echo $form['appellation_autocomplete']->renderId() ?>").attr('autocomplete-data'));
		$("#<?php echo $form['appellation_autocomplete']->renderId() ?>").autocomplete({
			minLength: 0,
			source: produits,
			focus: function(event, ui)
	        {
	        	$('#<?php echo $form['appellation_autocomplete']->renderId() ?>').val(ui.item[1]);
	        	$('#<?php echo $form['appellation']->renderId() ?>').val(ui.item[0]);
				
	            return false;
	        },
	        select: function(event, ui)
	        {
	        	$('#<?php echo $form['appellation_autocomplete']->renderId() ?>').val(ui.item[1]);
	        	$('#<?php echo $form['appellation']->renderId() ?>').val(ui.item[0]);
					
	            return false;
	        }
		});	
		$("#<?php echo $form['appellation_autocomplete']->renderId() ?>").data('autocomplete')._renderItem = function(ul, item)
	    {
	        var tab = item['value'].split('|@');
	        return $('<li></li>')
	        .data("item.autocomplete", tab)
	        .append('<a><span class="appellation">'+tab[1]+'</a>' )
	        .appendTo(ul);
	    };
});
</script>