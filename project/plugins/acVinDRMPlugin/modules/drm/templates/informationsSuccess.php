<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'informations', 'pourcentage' => '5')); ?>

    <!-- #principal -->
    <section id="principal">
		<div id="application_dr">
			<div id="drm_informations">
		        <form action="<?php echo url_for('drm_informations', $drm) ?>" method="post">
				<div class="ligne_btn">
					<button type="submit" class="btn_suiv"><span>VALIDER</span></button>
					<a href="#" class="btn_popup btn_popup_trigger" data-popup="#popup_confirm_modif_infos" data-popup-config="configConfirmModifInfos" data-popup-titre="Etes-vous sûr de vouloir modifier ces informations ?"></a>
				</div>
		        <p>Veuillez tout d'abord confirmer les informations ci-dessous* :<br /><br /></p>
			        <?php echo $form->renderGlobalErrors() ?>
					<?php echo $form->renderHiddenFields() ?>
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
						<label for="champ_3">N° TVA intracommunautaire :</label>
						<span class="valeur"><?php echo $etablissement->no_tva_intracommunautaire ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_9">N° accises / EA :</label>
						<span class="valeur"><?php echo $etablissement->no_accises ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_6">Adresse du chai :</label>
						<span class="valeur"><?php echo $etablissement->siege->adresse ?><br /><?php echo $etablissement->siege->code_postal ?> <?php echo $etablissement->siege->commune ?> <?php echo $etablissement->siege->pays ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_7">Lieu ou est tenue la comptabilité matière :</label>
						<span class="valeur"><?php if (!$etablissement->comptabilite->adresse): ?>IDEM<?php else: ?><?php echo $etablissement->comptabilite->adresse ?><br /><?php echo $etablissement->comptabilite->code_postal ?> <?php echo $etablissement->comptabilite->commune ?> <?php echo $etablissement->comptabilite->pays ?><?php endif; ?></span>
					</div>
					<div class="ligne_form">
						<label for="champ_8">Service des douanes :</label>
						<span class="valeur"><?php echo $etablissement->service_douane ?></span>
					</div>
					<?php if(!$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                    <div class="ligne_form">
						<?php echo $form['confirmation']->renderError() ?>
					</div>
					<strong><?php echo $form['confirmation']->render() ?></strong>
					<?php endif; ?>
					<div class="ligne_btn">
						<button type="submit" class="btn_suiv"><span>VALIDER</span></button>
						<a href="#" class="btn_popup btn_popup_trigger" data-popup="#popup_confirm_modif_infos" data-popup-config="configConfirmModifInfos" data-popup-titre="Etes-vous sûr de vouloir modifier ces informations ?"></a>
					</div>
					<?php if($drm->isRectificative() && $drm->exist('ciel') && $drm->ciel->transfere): ?>
					<?php else: ?>
					<div class="ligne_btn">
           				<a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
        			</div>
        			<?php endif; ?>
		        </form>
			</div>
	    </div>
    </section>
</section>
