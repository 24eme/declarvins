<?php include_component('global', 'navTop', array('active' => 'daids')); ?>

<section id="contenu">

    <?php include_partial('daids/header', array('daids' => $daids)); ?>
    <?php include_component('daids', 'etapes', array('daids' => $daids, 'etape' => 'informations', 'pourcentage' => '5')); ?>

    <!-- #principal -->
    <section id="principal">
		<div id="application_dr">
			<div id="drm_informations">
		        <p>Veuillez tout d'abord confirmer les informations ci-dessous :<br /><br /></p>
		        <form action="<?php echo url_for('daids_informations', $daids) ?>" method="post">
			        <?php echo $form->renderGlobalErrors() ?>
					<?php echo $form->renderHiddenFields() ?>
                                        <div class="ligne_form">
						<?php echo $form['confirmation']->renderError() ?>
					</div>
					<div class="ligne_form">
						<label for="champ_4">Raison sociale :</label>
						<span class="valeur valeur_2"><?php echo $etablissement->raison_sociale ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_4">Nom commercial :</label>
						<span class="valeur valeur_2"><?php echo $etablissement->nom ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_1">CVI :</label>
						<span class="valeur"><?php echo $etablissement->cvi ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_2">N° SIRET :</label>
						<span class="valeur"><?php echo $etablissement->siret ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_3">N° entrepositaire agréé :</label>
						<span class="valeur"><?php echo $etablissement->no_tva_intracommunautaire ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_6">Adresse du chai :</label>
						<span class="valeur"><?php echo $etablissement->siege->adresse ?><br /><?php echo $etablissement->siege->code_postal ?> <?php echo $etablissement->siege->commune ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_7">Lieu ou est tenue la comptabilité matière :</label>
						<span class="valeur"><?php if (!$etablissement->comptabilite->adresse): ?>IDEM<?php else: ?><?php echo $etablissement->comptabilite->adresse ?><br /><?php echo $etablissement->comptabilite->code_postal ?> <?php echo $etablissement->comptabilite->commune ?><?php endif; ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_8">Service des douanes :</label>
						<span class="valeur"><?php echo $etablissement->service_douane ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_9">Numéro d’Accise :</label>
						<span class="valeur">1654546764</span>
					</div>
					<?php if(!$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
					<?php echo $form['confirmation']->render() ?>
					<?php endif; ?>
					<div class="ligne_btn">
						<button type="submit" class="btn_suiv"><span>VALIDER</span></button>
						<a href="#" class="btn_popup btn_popup_trigger" data-popup="#popup_confirm_modif_infos" data-popup-config="configConfirmModifInfos" data-popup-titre="Etes-vous sûr de vouloir modifier ces informations ?"></a>
					</div>
					<div class="ligne_btn">
           				<a href="<?php echo url_for('daids_delete_one', $daids) ?>" class="annuler_saisie btn_remise"><span>annuler la saisie</span></a>
        			</div>
		        </form>
			</div>
	    </div>
    </section>
</section>
