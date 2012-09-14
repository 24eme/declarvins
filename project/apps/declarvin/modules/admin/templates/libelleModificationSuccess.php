<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'libelles')); ?>
<section id="contenu">
	<form method="post" action="<?php echo url_for('admin_libelles_edit', array('type' => $type, 'key' => $key)) ?>">
	    <div class="ligne_form ligne_form_label">
	        <?php echo $form->renderHiddenFields(); ?>
	        <?php echo $form->renderGlobalErrors(); ?>
	
	        <?php echo $form['libelle']->renderLabel() ?>
	        <?php echo $form['libelle']->render() ?>
	        <?php echo $form['libelle']->renderError() ?>
	    </div>
		<br />
	    <div class="btnValidation">
	    	<span>&nbsp;</span>
	        <input class="btn_valider" type="submit" value="Valider" />
	    </div>
	</form>
</section>