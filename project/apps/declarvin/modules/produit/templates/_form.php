<style>
<!--
.popup_form .ligne_form {
    margin: 0;
}
.popup_form .ligne_form td {
    width: 190px;
    padding: 0 5px 0 0;
    text-align: left;
}
.popup_form .ligne_form select {
    height: 22px;
    width: 54px;
}
.popup_form h2 {
    padding-top: 20px;
    text-transform: uppercase;
}
.popup_form table {
   display: inline;
}
-->
</style>
<form class="popup_form" id="form_ajout" action="<?php echo url_for('produit_modification', array('noeud' => $form->getObject()->getTypeNoeud(), 'hash' => str_replace('/', '-', $form->getHash()))) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['libelle']->renderError() ?></span>
		<?php echo $form['libelle']->renderLabel() ?>
		<?php echo $form['libelle']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['code']->renderError() ?></span>
		<?php echo $form['code']->renderLabel() ?>
		<?php echo $form['code']->render() ?>
	</div>
	<?php if ($form->getObject()->hasDepartements()): ?>
		<h2>Départements</h2>
		<?php foreach ($form['secteurs'] as $subform): ?>
		  <?php include_partial('produit/subformDepartement', array('form' => $subform))?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ($form->getObject()->hasDroits()): ?>
		<h2>Droits douane</h2>
		<?php foreach ($form['droit_douane'] as $subform): ?>
		  <?php include_partial('produit/subformDroits', array('form' => $subform))?>
		<?php endforeach; ?>
		<h2>Droits CVO</h2>
		<?php foreach ($form['droit_cvo'] as $subform): ?>
		  <?php include_partial('produit/subformDroits', array('form' => $subform))?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ($form->getObject()->hasLabels()): ?>
		<h2>Labels</h2>
		<?php foreach ($form['labels'] as $subform): ?>
		  <?php include_partial('produit/subformLabel', array('form' => $subform))?>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>