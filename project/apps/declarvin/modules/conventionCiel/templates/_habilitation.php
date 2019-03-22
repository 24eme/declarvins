<div class="etablissement">
	<div class="ligne_form">
		<?php echo $form['no_accises']->renderError() ?>
		<?php echo $form['no_accises']->renderLabel() ?>
		<?php echo $form['no_accises']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['identifiant']->renderError() ?>
		<?php echo $form['identifiant']->renderLabel() ?>
		<?php echo $form['identifiant']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['droit_teleprocedure']->renderError() ?>
		<?php echo $form['droit_teleprocedure']->renderLabel() ?>
		<?php echo $form['droit_teleprocedure']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['droit_telepaiement']->renderError() ?>
		<?php echo $form['droit_telepaiement']->renderLabel() ?>
		<?php echo $form['droit_telepaiement']->render() ?>
	</div>
</div>