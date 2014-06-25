<div class="filtre_produits_item">
	<div class="ligne_form">
		<?php echo $form['declaration']->renderError() ?>
		<?php echo $form['declaration']->renderLabel(null, array('class' => 'intitule_champ')) ?>
		<?php echo $form['declaration']->render() ?>
		<a href="#" data-container="div.filtre_produits_item" class="btn_supprimer_ligne_template">Supprimer</a>
	</div>
</div>