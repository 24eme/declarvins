<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_mouvements_generaux_product_ajout', $form->getDRM()->declaration->certifications->getOrAdd($certification)) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['hashref']->renderError() ?></span>
		<?php echo $form['hashref']->renderLabel() ?>
		<?php echo $form['hashref']->render() ?>
	</div>
    <?php if ($form->hasLabel()):?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['label']->renderError() ?></span>
		<?php echo $form['label']->renderLabel() ?>
		<?php echo $form['label']->render() ?>
		
	</div>
    <?php endif; ?>
	<a href="#" id="lien_<?php echo $form['disponible']->renderId() ?>" style="font-size: 12px">Je souhaite déclarer un stock disponible</a>
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
		$( "#<?php echo $form['hashref']->renderId() ?>" ).combobox();

		$('#lien_<?php echo $form['disponible']->renderId() ?>').click(function() {
	    	$(this).hide();
	    	$('#ligne_<?php echo $form['disponible']->renderId() ?>').show();
		$('#produit_<?php echo $certification; ?>_disponible.num_float').saisieNum(true);
		$('#produit_<?php echo $certification; ?>_disponible.num_float').nettoyageChamps();
		return false;
	    }); 
});
</script>
