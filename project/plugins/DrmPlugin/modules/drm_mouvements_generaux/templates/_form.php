<form  class="popup_form" id="form_ajout" action="<?php echo url_for(array('sf_route' => 'drm_mouvements_generaux_product_form', 
																		   'sf_subject' => $form->getObject()->getDocument(),
																		   'certification' => $certification)) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<input type="hidden" name="certification" value="<?php echo $certification ?>" />
	<div class="ligne_form">
		<span class="error"><?php echo $form['hashref']->renderError() ?></span>
		<?php echo $form['hashref']->renderLabel() ?>
		<?php echo $form['hashref']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['label']->renderError() ?></span>
		<?php echo $form['label']->renderLabel() ?>
		<?php echo $form['label']->render() ?>
		
	</div>
	<div id="ligne_<?php echo $form['label_supplementaire']->renderId() ?>" class="ligne_form" style="display: none">
		<span class="error"><?php echo $form['label_supplementaire']->renderError() ?></span>
		<?php echo $form['label_supplementaire']->renderLabel() ?>
		<?php echo $form['label_supplementaire']->render() ?>
	</div>
	<a href="#" id="lien_<?php echo $form['disponible']->renderId() ?>" style="font-size: 12px">Je souhaite déclarer un stock initial non nul</a>
	<div id="ligne_<?php echo $form['disponible']->renderId() ?>" class="ligne_form" style="display: none">
		<span class="error"><?php echo $form['disponible']->renderError() ?></span>
		<?php echo $form['disponible']->renderLabel() ?>
		<?php echo $form['disponible']->render(array('class' => 'num num_float')) ?>
	</div>
	<div class="ligne_form_btn">
		<!-- <button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button> -->
		<a name="annuler" class="btn_annuler btn_fermer" href="<?php echo url_for('produits') ?>">Annuler</a>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function () {
		$( "#<?php echo $form['hashref']->renderId() ?>" ).combobox();

		var checkLabelAutre = function() {
			if ($('#<?php echo $form['label']->renderId()?>_AUTRE:checked').length > 0) {
				$('#ligne_<?php echo $form['label_supplementaire']->renderId() ?>').show();
			} else {
				$('#ligne_<?php echo $form['label_supplementaire']->renderId() ?>').hide();
			}
		}

		checkLabelAutre();

		$('#<?php echo $form['label']->renderId()?>_AUTRE').click(function() {
			checkLabelAutre();
		});

		$('#lien_<?php echo $form['disponible']->renderId() ?>').click(function() {
	    	$(this).hide();
	    	$('#ligne_<?php echo $form['disponible']->renderId() ?>').show();
	    	return false;
	    }); 
});
</script>