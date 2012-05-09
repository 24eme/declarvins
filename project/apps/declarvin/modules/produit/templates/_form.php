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
<script type="text/javascript">
<!--
$(document).ready( function() {
	$('h2').each(function() {
		var title = $(this);
		var addLink = title.children();
		var form = title.next();
		var counteur = form.next();
		var template = $('#template'+form.attr('id'));
		addLink.click(function() {
			var subForms = form.children();
			var lastIndex = parseInt(subForms.last().attr('data-key'));
			template.tmpl({index: (lastIndex + 1)}).appendTo(form);
			counteur.val(parseInt(counteur.val()) + 1);
		});
	});
	$('.removeForm').live("click", function(){
		var form = $(this).parents('.ligne_form');
		var forms = form.parents('.subForm');
		var nbForm = forms.children().length;
		if (nbForm > 1) {
			form.remove();
		} else {
			form.find('input:text, select').each(function () {
				$(this).val('');
			});
		}
    });
});
//-->
</script>

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
		<h2>Départements&nbsp;<a href="javascript:void(0)" class="addForm">+</a></h2>
		<div class="subForm" id="formsDepartement">
		<?php foreach ($form['secteurs'] as $subform): ?>
		  <?php include_partial('produit/subformDepartement', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_departement" value="<?php echo count($form['secteurs']) ?>" />
	<?php endif; ?>
	<?php if ($form->getObject()->hasDroits()): ?>
		<h2>Droits douane&nbsp;<a href="javascript:void(0)" class="addForm">+</a></h2>
		<div class="subForm" id="formsDouane">
		<?php foreach ($form['droit_douane'] as $subform): ?>
		  <?php include_partial('produit/subformDroits', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_douane" value="<?php echo count($form['droit_douane']) ?>" />
		<h2>Droits CVO&nbsp;<a href="javascript:void(0)" class="addForm">+</a></h2>
		<div class="subForm" id="formsCvo">
		<?php foreach ($form['droit_cvo'] as $subform): ?>
		  <?php include_partial('produit/subformDroits', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_cvo" value="<?php echo count($form['droit_cvo']) ?>" />
	<?php endif; ?>
	<?php if ($form->getObject()->hasLabels()): ?>
		<h2>Labels&nbsp;<a href="javascript:void(0)" class="addForm">+</a></h2>
		<div class="subForm" id="formsLabel">
		<?php foreach ($form['labels'] as $subform): ?>
		  <?php include_partial('produit/subformLabel', array('form' => $subform))?>
		<?php endforeach; ?>
		</div>
		<input class="counteur" type="hidden" name="nb_label" value="<?php echo count($form['labels']) ?>" />
	<?php endif; ?>
	<?php if ($form->getObject()->hasDetails()): ?>
		<h2>Activation des lignes</h2>
		<div class="subForm" id="!">
			<?php foreach ($form['detail'] as $detail): ?>
			<?php foreach ($detail as $type): ?>
			<div class="ligne_form">
				<span class="error"><?php echo $type['readable']->renderError() ?></span>
				<?php echo $type['readable']->renderLabel() ?>
				<?php echo $type['readable']->render() ?>
				<?php echo $type['writable']->render() ?>
			</div>
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