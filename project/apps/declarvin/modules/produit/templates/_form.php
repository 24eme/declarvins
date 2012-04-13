<form class="popup_form" id="form_ajout" action="<?php echo url_for('produit_modification', array('noeud' => $form->getObject()->getTypeNoeud(), 'hash' => str_replace('/', '-', substr($form->getObject()->getHash(),1)))) ?>" method="post">
	<table>
	<?php echo $form ?>
	</table>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>