<div class="etablissement" id="etablissement<?php echo $indice ?>">
	<div class="ligne_form">
		<?php echo $form['raison_sociale']->renderError() ?>
		<?php echo $form['raison_sociale']->renderLabel() ?>
		<?php echo $form['raison_sociale']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['nom']->renderError() ?>
		<?php echo $form['nom']->renderLabel() ?>
		<?php echo $form['nom']->render() ?>
	</div>
	<div class="ligne_form">
		<?php echo $form['siret_cni']->renderError() ?>
		<?php echo $form['siret_cni']->renderLabel('SIRET*: <a href="" class="msg_aide" data-msg="help_popup_mandat_siret" title="Message aide"></a>') ?>
		<?php echo $form['siret_cni']->render() ?>
	</div>
	<?php if ($supprimer): ?>
	<a href="#" class="supprimer">Supprimer</a>
	<?php endif; ?>
</div>