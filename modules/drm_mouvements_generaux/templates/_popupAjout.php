<div id="popup_ajout_produit_<?php echo $certification_config->getKey() ?>" class="popup_contenu">
        <p>Saisissez n'importe quelle partie du nom de l'appellation pour qu'elle s'affiche.</p>
	<?php include_partial('form', array('form' => $form, 'certification_config' => $certification_config)) ?>
</div>