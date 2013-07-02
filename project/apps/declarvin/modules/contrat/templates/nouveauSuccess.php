<section id="contenu">
	<?php if($sf_user->hasFlash('notice')) : ?>
	<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
	<?php endif; ?>
	<form id="creation_compte" method="post" action="<?php echo url_for('contrat_nouveau', array('nocontrat' => $form->getObject()->no_contrat)) ?>">
		<?php echo $form->renderHiddenFields(); ?>
		<?php echo $form->renderGlobalErrors(); ?>
		<input id="contrat_nb_etablissement" type="hidden" name="nb_etablissement" value="<?php echo $nbEtablissement ?>" />
		<h1><strong>Étape 1 :</strong> Création de compte</h1>
                <p class="intro">Afin de créer votre compte veuillez remplir les champs suivants :</p>
		<div class="col">
			<div class="ligne_form">
				<?php echo $form['nom']->renderError() ?>
				<?php echo $form['nom']->renderLabel() ?>
				<?php echo $form['nom']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['prenom']->renderError() ?>
				<?php echo $form['prenom']->renderLabel() ?>
				<?php echo $form['prenom']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['fonction']->renderError() ?>
				<?php echo $form['fonction']->renderLabel() ?>
				<?php echo $form['fonction']->render() ?>
			</div>
		</div>
		<div class="col">
			<div class="ligne_form">
				<?php echo $form['email']->renderError() ?>
				<?php echo $form['email']->renderLabel() ?>
				<?php echo $form['email']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['email2']->renderError() ?>
				<?php echo $form['email2']->renderLabel() ?>
				<?php echo $form['email2']->render() ?>
			</div>	
			<div class="ligne_form">
				<?php echo $form['telephone']->renderError() ?>
				<?php echo $form['telephone']->renderLabel() ?>
				<?php echo $form['telephone']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['fax']->renderError() ?>
				<?php echo $form['fax']->renderLabel() ?>
				<?php echo $form['fax']->render() ?>
			</div>
		</div>
		
		<div id="infos_etablissements">
			<h1>Etablissement <a href="" class="msg_aide" data-msg="help_popup_mandat_etablissements" title="Message aide"></a></h1>
			<p class="intro">Veuillez ajouter vos établissements</p>
			<?php 
				include_partial('templateEtablissement');
				include_partial('etablissement', array('indice' => 0, 'form' => $form['etablissements'][0], 'supprimer' => false));
				$i=0;
				foreach ($form['etablissements'] as $etablissement) {
					if ($i > 0) {
						include_partial('etablissement', array('indice' => $i, 'form' => $etablissement, 'supprimer' => true));
					}
					$i++; 
				}
			?>
                       
		</div>
                
        <strong class="champs_obligatoires">* Champs obligatoires</strong>
		<div class="ligne_btn">
            <a href="#" class="btn_ajouter" id="ajouter_etablissement">Ajouter <span>un établissement</span></a>
		</div>
		<div class="ligne_btn">
			<button type="submit" class="btn_valider"><span>Valider</span></button>
		</div>
	</form>
</section>
<!-- fin #contenu -->