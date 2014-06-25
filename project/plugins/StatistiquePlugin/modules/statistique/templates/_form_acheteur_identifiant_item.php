<div class="filtre_etablissements_item">
	<div class="ligne_form">
		<?php echo $form['identifiant']->renderError() ?>		
		<?php $l = (isset($label))? $label : null; echo $form['identifiant']->renderLabel($l, array('class' => 'intitule_champ')); ?>
		<?php echo $form['identifiant']->render() ?>
		<a href="#" data-container="div.filtre_etablissements_item" class="btn_supprimer_ligne_template">Supprimer</a>
	</div>
</div>