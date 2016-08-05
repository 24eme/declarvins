<div class="etablissement">
	<div class="ligne_form">
		<p><strong><?php echo ($etablissement->raison_sociale)? $etablissement->raison_sociale : $etablissement->nom ?></strong></p>
	</div>
	<div class="ligne_form">
		<?php echo $form['cvi']->renderError() ?>
		<?php echo $form['cvi']->renderLabel() ?>
		<?php echo $form['cvi']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['no_accises']->renderError() ?>
		<?php echo $form['no_accises']->renderLabel() ?>
		<?php echo $form['no_accises']->render() ?>
	</div>
</div>