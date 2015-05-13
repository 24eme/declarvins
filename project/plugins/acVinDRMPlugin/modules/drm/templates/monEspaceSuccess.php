<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<?php if ($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
	<?php if($lastDrmInfos = $sf_user->getDerniereDrmSaisie()): ?>
			<?php if ($lastDrmInfos['valide'] === 'NEW'): ?>
				<?php if (str_replace('-', '', $lastDrmInfos['periode']) < date('Ym')): ?>
			    <div id="flash_message">
			        <div class="flash_error">/!\ Vous devez saisir votre DRM de <?php echo strftime('%B %Y', strtotime($lastDrmInfos['periode'].'-01')); ?> /!\</div>
			    </div>
				<?php endif; ?>
			<?php else: ?>
				<?php if (!$lastDrmInfos['valide'] && str_replace('-', '', $lastDrmInfos['periode']) < date('Ym')): ?>
			    <div id="flash_message">
			        <div class="flash_error">/!\ Vous n'avez pas validé votre DRM de <?php echo strftime('%B %Y', strtotime($lastDrmInfos['periode'].'-01')); ?> /!\</div>
			    </div>
			    <?php elseif (str_replace('-', '', $lastDrmInfos['periode']) < date('Ym', strtotime('-1 month'))): ?>
			    <div id="flash_message">
			        <div class="flash_error">/!\ Vous devez saisir votre DRM de <?php echo strftime('%B %Y', strtotime('next month',strtotime($lastDrmInfos['periode'].'-01'))); ?> /!\</div>
			    </div>
				<?php endif; ?>
			<?php endif; ?>			
		<?php endif; ?>
<?php endif; ?>

<section id="contenu">
    
    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_monespace" data-doc="<?php echo url_for('drm_notice') ?>" title="Message aide"></a></h1>
    
    <p class="intro">Bienvenue sur votre espace DRM. Que voulez-vous faire ?</p>
    
    <?php if ($sf_user->hasFlash('erreur_drm')): ?>
    <div id="flash_message">
		<div class="flash_error"><?php echo $sf_user->getFlash('erreur_drm') ?></div>
	</div>
    <?php endif; ?>
    
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
	        <h1>Espace Admin <a href="" class="msg_aide" data-msg="help_popup_monespace_admin" data-doc="notice.pdf" title="Message aide"></a></h1>
	    	<p class="intro">Saisir une DRM d'un mois différent.</p>
	        <div id="espace_admin" style="float: left; width: 670px;">
	            <div class="contenu clearfix">
	            	<?php include_partial('formCampagne', array('etablissement' => $etablissement,
	                                                            'form' => $formCampagne)) ?>
	            </div>
	        </div>
	        <?php endif; ?>
</section>

