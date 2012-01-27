<div id="popup_ajout_produit_<?php echo $certification ?>" class="popup_contenu">
	<form  class="popup_form" id="subForm" action="<?php echo url_for('@drm_mouvements_generaux_product_form') ?>" method="post">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<input type="hidden" name="certification" value="<?php echo $certification ?>" />
		<div class="ligne_form">
			<?php echo $form['produit']->renderLabel() ?>
			<?php echo $form['produit']->render() ?>
			<span class="error"><?php echo $form['produit']->renderError() ?></span>
		</div>
		<!--<div class="ligne_form">
			<?php echo $form['appellation']->renderLabel() ?>
			<?php echo $form['appellation']->render() ?>
			<span class="error"><?php echo $form['appellation']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['couleur']->renderLabel() ?>
			<?php echo $form['couleur']->render() ?>
			<span class="error"><?php echo $form['couleur']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['cepage']->renderLabel() ?>
			<?php echo $form['cepage']->render() ?>
			<span class="error"><?php echo $form['cepage']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['millesime']->renderLabel() ?>
			<?php echo $form['millesime']->render() ?>
			<span class="error"><?php echo $form['millesime']->renderError() ?></span>
		</div>-->
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
		var produits = JSON.parse($("#produit_AOP_produit").attr('autocomplete-data'));
		$("#produit_AOP_produit").autocomplete({
			minLength: 0,
			source: produits,
			focus: function(event, ui)
	        {
	        	$("#produit_AOP_produit").val(ui.item[1] + ' ' + ui.item[3] + ' ' + ui.item[5] + ' ' + ui.item[7]);
	        	$('#produit_AOP_appellation').val(ui.item[0]);
	        	$('#produit_AOP_couleur').val(ui.item[2]);
	        	$('#produit_AOP_cepage').val(ui.item[4]);
	        	$('#produit_AOP_millesime').val(ui.item[6]);
				
	            return false;
	        },
	        select: function(event, ui)
	        {
	        	$("#produit_AOP_produit").val(ui.item[1] + ' ' + ui.item[3] + ' ' + ui.item[5] + ' ' + ui.item[7]);
	        	$('#produit_AOP_appellation').val(ui.item[0]);
	        	$('#produit_AOP_couleur').val(ui.item[2]);
	        	$('#produit_AOP_cepage').val(ui.item[4]);
	        	$('#produit_AOP_millesime').val(ui.item[6]);
					
	            return false;
	        }
		});	
		$("#produit_AOP_produit").data('autocomplete')._renderItem = function(ul, item)
	    {
	        var tab = item['value'].split('|@');
	        return $('<li></li>')
	        .data("item.autocomplete", tab)
	        .append('<a><span class="appellation"><strong>'+tab[1]+'</strong></span> <span class="couleur">'+tab[3]+'</span> <span class="cepage">'+tab[5]+'</span> <span class="millesime">'+tab[7]+'</span></a>' )
	        .appendTo(ul);
	    };
});
</script>