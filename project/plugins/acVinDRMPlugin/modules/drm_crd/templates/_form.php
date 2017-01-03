<style>
.popup_form .ligne_form .radio_list label {
	width: auto !important;
}
.popup_form .ligne_form .radio_list {
	display: inline-block;
}
.popup_form .ligne_form .radio_list li {
	float: left;
	width: 80px;
}
</style>
<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_crd_product_ajout', $form->getDRM()) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['categorie']->renderError() ?></span>
		<?php echo $form['categorie']->renderLabel() ?>
		<?php echo $form['categorie']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['type']->renderError() ?></span>
		<?php echo $form['type']->renderLabel() ?>
		<?php echo $form['type']->render() ?>
	</div>
	<div class="ligne_form bloc_condition" data-condition-cible="#bloc_autre">
		<span class="error"><?php echo $form['centilisation']->renderError() ?></span>
		<?php echo $form['centilisation']->renderLabel() ?>
		<?php echo $form['centilisation']->render() ?>
	</div>
	 <div id="bloc_autre" class="bloc_conditionner" data-condition-value="AUTRE" style="margin-bottom:10px;">
		<div class="ligne_form">
			<span class="error"><?php echo $form['bib']->renderError() ?></span>
			<?php echo $form['bib']->renderLabel() ?>
			<?php echo $form['bib']->render() ?>
		</div>
		<div class="ligne_form">
			<span class="error"><?php echo $form['centilitre']->renderError() ?></span>
			<?php echo $form['centilitre']->renderLabel() ?>
			<?php echo $form['centilitre']->render() ?>
			<small style="font-size: 11px; padding: 0 0 0 205px; display: inline-block; line-height: normal;"><i>Exprimée en <strong><u>centilitre</u></strong> (cL).<br />Nombre acceptant <strong><u>un</u></strong> chiffre après la virgule.</i></small>
		</div>
	</div>
	<?php if ($form->getDRM()->canSetStockDebutMois() || $form->getDRM()->isFirstCiel()): ?>
	<a href="#" id="lien_<?php echo $form['disponible']->renderId() ?>" style="font-size: 12px">Je souhaite déclarer un stock disponible</a>
	<?php endif; ?>
	<div id="ligne_<?php echo $form['disponible']->renderId() ?>" class="ligne_form" style="display: none">
		<span class="error"><?php echo $form['disponible']->renderError() ?></span>
		<?php echo $form['disponible']->renderLabel() ?>
		<?php echo $form['disponible']->render(array('class' => 'num num_float')) ?>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>	
<script type="text/javascript">
$(document).ready(function () {

		$('#lien_<?php echo $form['disponible']->renderId() ?>').click(function() {
	    	$(this).hide();
	    	$('#ligne_<?php echo $form['disponible']->renderId() ?>').show();
			return false;
	    }); 
		$('.bloc_condition').each(function() {
			var blocCondition = $(this);
			var input = blocCondition.find('select');
	    	var bloc = blocCondition.attr('data-condition-cible');
	    	
	    	var traitement = function(input, b) {
				if ($(b).exists()) {
					var value = $(b).attr('data-condition-value');
					if (value == input.val()) {
						$(b).show();
					}
				}
	    	}
	    	
	    	if(input.length == 0) {
	    		$(bloc).show();
	    	} else {
	    		$(bloc).hide();
	    	}
	    	
	    	input.each(function() {
	    		traitement($(this), bloc);
	    	});

	        input.change(function()
	        {
	        	$(bloc).hide();
				traitement($(this), bloc);
	        });
		});
});
</script>