<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'libelles')); ?>
<section id="contenu">
	<div class="clearfix" id="application_dr">
    	<h1>Modification du libellé</h1>

    	<div id="libelleModification">
			<form method="post" action="<?php echo url_for('admin_libelles_edit', array('type' => $type, 'key' => $key)) ?>">
			    <div class="ligne_form">
			        <?php echo $form->renderHiddenFields(); ?>
			        <?php echo $form->renderGlobalErrors(); ?>
			
			        <?php echo $form['libelle']->renderLabel() ?>
			        <?php echo $form['libelle']->render() ?>
			        <?php echo $form['libelle']->renderError() ?>
			    </div>

			    <div class="btnValidation">
			        <input class="btn_valider" type="submit" value="Ajouter"/>
			    </div>
			</form>
		</div>
	</div>
</section>