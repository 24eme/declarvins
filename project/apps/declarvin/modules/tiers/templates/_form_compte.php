<div id="application_dr" class="clearfix">
	<h1>Votre compte</h1>
	<div id="compteModification">
		<form method="post" action="<?php echo url_for('profil', $etablissement); ?>">
		    <?php echo $form->renderHiddenFields(); ?>
		    <?php echo $form->renderGlobalErrors(); ?>
			<div class="ligne_form">
			    <label>N° contrat d'inscription:</label>
			    <strong><?php echo str_replace('CONTRAT-', '', $compte->contrat) ?></strong>
			</div>
			<div class="ligne_form">
			    <label>Nom:</label>
			    <strong><?php echo $compte->getNom() ?></strong>
			</div>
			
			<div class="ligne_form">
			    <label>Prénom:</label>
			    <strong><?php echo $compte->getPrenom() ?></strong>
			</div>
			
			<div class="ligne_form">
			    <label>Adresse e-mail:</label>
			    <strong><?php echo $compte->getEmail() ?></strong>
			</div>
			
			<div class="ligne_form">
			    <label>Adhésion CIEL:</label>
			    <?php if ($compte->exist('dematerialise_ciel') && $compte->dematerialise_ciel): ?>
			    <strong>oui</strong>
			    <?php else: ?>
			    <?php if($compte->isTiers()): $convention = $compte->getConventionCiel(); ?>
			    	<?php if ($convention && $convention->valide):?>
			    	<strong>en attente</strong>
				    <?php else: ?>
				    <strong>non</strong>
				    <?php endif; ?>
			    <?php else: ?>
			    <strong>non</strong>
			    <?php endif; ?>
			    <?php endif; ?>
			</div>
			
			<br />
			
			<div class="ligne_form">
			    <label>Identifiant:</label>
			    <strong><?php echo $compte->getLogin() ?></strong>
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
			    <label>&nbsp;</label>
                <?php include_partial('global/stronger_password', ['inputPasswordTarget' => $form['mdp1']->renderId()]) ?>
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