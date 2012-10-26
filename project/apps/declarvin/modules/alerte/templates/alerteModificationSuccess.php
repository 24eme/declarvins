<?php include_component('global', 'navBack', array('active' => 'alertes', 'subactive' => '')); ?>
<section id="contenu">
	<div class="clearfix" id="application_dr">
    	<h1>Modification de l'alerte</h1>

    	<div id="libelleModification">
			<form method="post" action="<?php echo url_for('alerte_edit', array('id' => $id)) ?>">
			    <?php echo $form->renderHiddenFields(); ?>
			    <?php echo $form->renderGlobalErrors(); ?>
			    <div class="ligne_form">
			        <?php echo $form['statut']->renderLabel() ?>
			        <?php echo $form['statut']->render() ?>
			        <?php echo $form['statut']->renderError() ?>
			    </div>
			    <div class="ligne_form">
			        <?php echo $form['commentaire']->renderLabel() ?>
			        <?php echo $form['commentaire']->render() ?>
			        <?php echo $form['commentaire']->renderError() ?>
			    </div>

			    <div class="btnValidation">
			        <input class="btn_valider" type="submit" value="Modifier"/>
			    </div>
			</form>
		</div>
	</div>
</section>