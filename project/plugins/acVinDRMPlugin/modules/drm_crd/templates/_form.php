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
	<div class="ligne_form">
		<span class="error"><?php echo $form['centilisation']->renderError() ?></span>
		<?php echo $form['centilisation']->renderLabel() ?>
		<?php echo $form['centilisation']->render() ?>
	</div>
	<?php if ($form->getDRM()->canSetStockDebutMois()): ?>
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
});
</script>