<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_recap_ajout_ajax', $config_appellation) ?>" method="post" >
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<label>Appellation:</label>
		<?php echo $config_appellation->libelle ?>
		<small style="font-size:9px">(<a href="#">modifier)</a></small>
	</div>
	<div class="ligne_form">
		<?php echo $form['produit']->renderLabel() ?>
		<?php echo $form['produit']->render() ?>
		<span class="error"><?php echo $form['produit']->renderError() ?></span>
	</div>
	<div class="ligne_form">
		<?php echo $form['label']->renderLabel() ?>
		<?php echo $form['label']->render()//array("class"=>"select_multiple")) ?>
		<span class="error"><?php echo $form['label']->renderError() ?></span>
	</div>
	<div class="ligne_form">
		<?php echo $form['label_supplementaire']->renderLabel() ?>
		<?php echo $form['label_supplementaire']->render() ?>
		<span class="error"><?php echo $form['label_supplementaire']->renderError() ?></span>
	</div>
	<a href="#" id="lien_produit_disponible" style="font-size: 12px">Je souhaite déclarer un stock initial non nul</a>
	<div id="ligne_produit_disponible" class="ligne_form" style="display: none">
		<?php echo $form['disponible']->renderLabel() ?>
		<?php echo $form['disponible']->render(array('class' => 'num num_float')) ?>
		<span class="error"><?php echo $form['disponible']->renderError() ?></span>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function () {
		var produits = JSON.parse($("#<?php echo $form['produit']->renderId() ?>").attr('autocomplete-data'));
		$("#<?php echo $form['produit']->renderId() ?>").autocomplete({
			minLength: 0,
			source: produits,
			focus: function(event, ui)
	        {
	        	$('#<?php echo $form['produit']->renderId() ?>').val(ui.item[1]);
	        	$('#<?php echo $form['hashref']->renderId() ?>').val(ui.item[0]);
				
	            return false;
	        },
	        select: function(event, ui)
	        {
	        	$('#<?php echo $form['produit']->renderId() ?>').val(ui.item[1]);
	        	$('#<?php echo $form['hashref']->renderId() ?>').val(ui.item[0]);
	            return false;
	        }
		});	
		$("#<?php echo $form['produit']->renderId() ?>").data('autocomplete')._renderItem = function(ul, item)
	    {
	        var tab = item['value'].split('|@');
	        return $('<li></li>')
	        .data("item.autocomplete", tab)
	        .append('<a><span class="appellation">'+tab[1]+'</a>' )
	        .appendTo(ul);
	    };
	    $('#lien_produit_disponible').click(function() {
	    	$(this).hide();
	    	$('#ligne_produit_disponible').show();
	    	return false;
	    });
});
</script>