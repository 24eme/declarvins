<div id="application_dr" class="clearfix">
	<h1>Compte</h1>
	<div id="compteModification">
		<form method="post" action="<?php echo url_for('profil', array('identifiant' => $etablissement->identifiant)); ?>">
		    <?php echo $form->renderHiddenFields(); ?>
		    <?php echo $form->renderGlobalErrors(); ?>
			<div class="ligne_form">
			    <?php echo $form['nom']->renderLabel() ?>
			    <?php echo $form['nom']->render() ?>
			    <?php echo $form['nom']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['prenom']->renderLabel() ?>
			    <?php echo $form['prenom']->render() ?>
			    <?php echo $form['prenom']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['telephone']->renderLabel() ?>
			    <?php echo $form['telephone']->render() ?>
			    <?php echo $form['telephone']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['fax']->renderLabel() ?>
			    <?php echo $form['fax']->render() ?>
			    <?php echo $form['fax']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['email']->renderLabel() ?>
			    <?php echo $form['email']->render() ?>
			    <?php echo $form['email']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['mdp']->renderLabel() ?>
			    <?php echo $form['mdp']->render() ?>
			    <?php echo $form['mdp']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['mdp1']->renderLabel() ?>
			    <?php echo $form['mdp1']->render() ?>
			    <?php echo $form['mdp1']->renderError() ?>
			</div>
			
			<div class="ligne_form">
			    <?php echo $form['mdp2']->renderLabel() ?>
			    <?php echo $form['mdp2']->render() ?>
			    <?php echo $form['mdp2']->renderError() ?>
			</div>
			
		        <div class="btnValidation">
		            <input class="btn_valider" type="submit" value="Modifier"/>
		        </div>
		</form>
	</div>
</div>