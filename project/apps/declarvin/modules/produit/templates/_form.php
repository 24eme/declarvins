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
}
.popup_form table {
   display: inline;
}
.popup_form .radio_form  {
	display: inline-block;
	width: 83px;
}
.popup_form .radio_form  label {
	display: inline-block;
	width: 64px;
}
-->
</style>


<form class="popup_form" id="form_ajout" action="<?php echo url_for('produit_modification', array('noeud' => $form->getObject()->getTypeNoeud(), 'hash' => str_replace('/', '-', $form->getHash()))) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
        <div class="contenu_onglet">
            <div class="ligne_form ">
                    <?php if($form['libelle']->hasError()) {?><span class="error"><?php echo $form['libelle']->renderError() ?></span><?php } ?>
                    <?php echo $form['libelle']->renderLabel() ?>
                    <?php echo $form['libelle']->render() ?>
            </div>
            <br />
            <div class="ligne_form">
                    <?php if($form['code']->hasError()){ ?><span class="error"><?php echo $form['code']->renderError() ?></span><?php } ?>
                    <?php echo $form['code']->renderLabel() ?>
                    <?php echo $form['code']->render() ?>
            </div>
		    <?php if ($form->getObject()->hasOIOC()): ?>
            <br />
            <div class="ligne_form">
                    <?php if($form['oioc']->hasError()){ ?><span class="error"><?php echo $form['oioc']->renderError() ?></span><?php } ?>
                    <?php echo $form['oioc']->renderLabel() ?>
                    <?php echo $form['oioc']->render() ?>
            </div>
		    <?php endif; ?>
		    <?php if ($form->getObject()->hasHasVrac()): ?>
            <br />
            <div class="ligne_form">
                    <?php if($form['has_vrac']->hasError()){ ?><span class="error"><?php echo $form['has_vrac']->renderError() ?></span><?php } ?>
                    <?php echo $form['has_vrac']->renderLabel() ?>
                    <?php echo $form['has_vrac']->render() ?>
            </div>
		    <?php endif; ?>
        </div>
	<?php if ($form->getObject()->hasDepartements()): ?>
                <h2>Départements&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
                <div class="subForm contenu_onglet" id="formsDepartement">
                    <p>Liste des départements :</p><br />
		<?php foreach ($form['secteurs'] as $subform): ?>
		  <?php include_partial('produit/subformDepartement', array('form' => $subform))?><br />
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_departement" value="<?php echo count($form['secteurs']) ?>" />
	<?php endif; ?>
	<?php if ($form->getObject()->hasDroits()): ?>
                <h2>Droits circulation&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></strong></h2>
		<div class="subForm contenu_onglet" id="formsDouane">
		<?php foreach ($form['droit_douane'] as $subform): ?>
		  <?php include_partial('produit/subformDroits', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_douane" value="<?php echo count($form['droit_douane']) ?>" />
		
                <h2>Cotisations interprofessionnelles&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
                <div class="subForm contenu_onglet" id="formsCvo">
		<?php foreach ($form['droit_cvo'] as $subform): ?>
		  <?php include_partial('produit/subformDroits', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_cvo" value="<?php echo count($form['droit_cvo']) ?>" />
	<?php endif; ?>
	<?php if ($form->getObject()->hasLabels()): ?>
                <h2>Labels&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
		<div class="subForm contenu_onglet" id="formsLabel">
		<?php foreach ($form['labels'] as $subform): ?>
		  <?php include_partial('produit/subformLabel', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_label" value="<?php echo count($form['labels']) ?>" />
	<?php endif; ?>
	<?php if ($form->getObject()->hasDetails()): ?>
		<h2>Activation des lignes</h2>
		<div class="subForm contenu_onglet" id="!">
			<?php foreach ($form['detail'] as $detail): ?>
			<?php foreach ($detail as $type): ?>
			<div class="ligne_form">
				<?php if($type['readable']->hasError()){ ?><span class="error"><?php echo $type['readable']->renderError() ?></span><?php } ?>				<?php echo $type['readable']->renderLabel() ?>
				<?php echo $type['readable']->render() ?>
				<?php echo $type['writable']->render() ?>
			</div>
                        <br />
			<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="ligne_form_btn">
		<!-- <button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button> -->
		<a name="annuler" class="btn_annuler btn_fermer" href="<?php echo url_for('produits') ?>">Annuler</a>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
<?php 
	include_partial('templateformsDepartement');
	include_partial('templateformsDouane');
	include_partial('templateformsCvo');
	include_partial('templateformsLabel');
?>