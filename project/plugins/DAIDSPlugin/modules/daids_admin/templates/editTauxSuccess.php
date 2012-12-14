<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'daids')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Edition</h1>
	    <form class="popup_form" id="form_ajout" action="<?php echo url_for('admin_daids_edit') ?>" method="post">
	    <?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<div class="contenu_onglet">
			<div class="ligne_form ">
				<span class="error"><?php echo $form['taux']->renderError() ?> </span>
				<?php echo $form['taux']->renderLabel() ?>
				<?php echo $form['taux']->render() ?>
			</div>
			<div class="ligne_form_btn">
				<a name="annuler" class="btn_annuler btn_fermer" href="<?php echo url_for('@admin_daids') ?>">Annuler</a>
				<button name="valider" class="btn_valider" type="submit">Valider</button>
			</div>
		</div>
	    </form>
	</div>
	</section>
</section>