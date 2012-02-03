<div class="etablissement" id="etablissement<?php echo $indice ?>">
	<div class="ligne_form">
		<?php echo $form['raison_sociale']->renderError() ?>
		<?php echo $form['raison_sociale']->renderLabel() ?>
		<?php echo $form['raison_sociale']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['siret_cni']->renderError() ?>
		<?php echo $form['siret_cni']->renderLabel() ?>
		<?php echo $form['siret_cni']->render() ?>
	</div>
	<?php if ($supprimer): ?>
	<a href="#" class="supprimer">Supprimer</a>
	<?php endif; ?>
</div>