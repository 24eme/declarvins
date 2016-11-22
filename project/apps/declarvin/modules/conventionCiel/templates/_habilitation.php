<div class="etablissement">
	<div class="ligne_form">
		<?php echo $form['no_accises']->renderError() ?>
		<?php echo $form['no_accises']->renderLabel() ?>
		<?php echo $form['no_accises']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['nom']->renderError() ?>
		<?php echo $form['nom']->renderLabel() ?>
		<?php echo $form['nom']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['prenom']->renderError() ?>
		<?php echo $form['prenom']->renderLabel() ?>
		<?php echo $form['prenom']->render() ?>
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
	<div class="ligne_form">
		<?php echo $form['mensualisation']->renderError() ?>
		<?php echo $form['mensualisation']->renderLabel() ?>
		<?php echo $form['mensualisation']->render() ?>
	</div>
	<?php if ($indice > 0): ?>
	<a href="#" class="supprimer">Supprimer</a>
	<?php endif; ?>
</div>