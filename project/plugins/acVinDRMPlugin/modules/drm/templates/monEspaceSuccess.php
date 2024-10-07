<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <h1>Déclaration Récapitulative Mensuelle</h1>

    <button id="telecharger_pdf" style="margin-left: 629px; padding-bottom: 6px;"><a href="/docs/correspondances_mouvements.pdf" target="_blank">correspondances mouvements CIEL</a></button>

    <p class="intro">Bienvenue sur votre espace DRM. Que voulez-vous faire ?</p>

    <?php if ($sf_user->hasFlash('erreur_drm')): ?>
    <div id="flash_message" style="padding-top: 0px">
		<div class="flash_error"><?php echo $sf_user->getFlash('erreur_drm') ?></div>
	</div>
    <?php endif; ?>

	<?php if ($hasDrmEnCours): ?>
	<div id="flash_message" style="padding-top: 0px">
		<div class="flash_warning">Vous n'avez pas validé votre DRM de <?php echo strftime('%B %Y', strtotime($drmEnCours->periode.'-01')); ?> : <a href="<?php echo url_for('drm_init', $drmEnCours) ?>">Accéder à la déclaration en cours</a></div>
	</div>
	<?php endif; ?>

	<?php if ($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE && !$hasDrmEnCours): ?>
    	<?php if($lastDrmInfos = $sf_user->getDerniereDrmSaisie()): ?>
    			<div id="flash_message" style="padding-top: 0px">
    			<?php if ($lastDrmInfos['valide'] === 'NEW'): ?>
    				<?php if (str_replace('-', '', $lastDrmInfos['periode']) < date('Ym')): ?>
    			        <div class="flash_error">/!\ Vous devez saisir votre DRM de <?php echo strftime('%B %Y', strtotime($lastDrmInfos['periode'].'-01')); ?> /!\</div>
    				<?php endif; ?>
    			<?php else: ?>
    				<?php if (!$lastDrmInfos['valide'] && str_replace('-', '', $lastDrmInfos['periode']) < date('Ym')): ?>
    			        <div class="flash_error">/!\ Vous n'avez pas validé votre DRM de <?php echo strftime('%B %Y', strtotime($lastDrmInfos['periode'].'-01')); ?> /!\</div>
    			    <?php elseif (str_replace('-', '', $lastDrmInfos['periode']) < date('Ym', strtotime('-1 month'))): ?>
    			        <div class="flash_error">/!\ Vous devez saisir votre DRM de <?php echo strftime('%B %Y', strtotime('next month',strtotime($lastDrmInfos['periode'].'-01'))); ?> /!\</div>
    				<?php endif; ?>
    			<?php endif; ?>
    			</div>
    		<?php endif; ?>
    <?php endif; ?>
    <p style="padding-bottom: 10px;">
        Vous pouvez saisir vos stocks en <strong><?php $dateFormat = new sfDateFormat('fr_FR'); echo ucfirst($dateFormat->format(date('Y').'-'.$etablissement->getMoisToSetStock().'-01', 'MMMM')); ?>
    </p>
    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
                <?php include_component('drm', 'historiqueList', array('etablissement' => $etablissement, 'historique' => false)) ?>
            </div>
        </div>
        <?php include_component('drm', 'campagnes', array('etablissement' => $etablissement)); ?>
    </section>
	        <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$hasDrmEnCours): ?>
	        <br /><br />
	        <h1>Espace Admin <a href="" class="msg_aide" data-msg="help_popup_monespace_admin" data-doc="/docs/notice.pdf|/docs/correspondances_mouvements.pdf" title="Message aide"></a></h1>
	    	<p class="intro">Saisir une DRM d'un mois différent.</p>
	        <div id="espace_admin" style="float: left; width: 670px;">
	            <div class="contenu clearfix">
	            	<?php include_partial('formCampagne', array('etablissement' => $etablissement,
	                                                            'form' => $formCampagne)) ?>
	            </div>
	        </div>
	        <?php endif; ?>
</section>

