<?php include_component('global', 'navTop', array('active' => 'daids')); ?>

<section id="contenu">
    
    <h1>DAI/DS</h1>
    
    <p class="intro">Bienvenue sur votre espace DAI/DS. Que voulez-vous faire ?</p>
    
    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
                <?php include_component('daids', 'historiqueList', array('etablissement' => $etablissement)) ?>
            </div>
        </div>
    </section>
    	<?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
	        <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$hasDaidsEnCours): ?>
	        <br /><br />
	        <h1>Espace Admin <a href="" class="msg_aide" data-msg="help_popup_monespace_admin" data-doc="notice.pdf" title="Message aide"></a></h1>
	    	<p class="intro">Saisir une DAI/DS d'une campagne différente.</p>
	    	<?php if ($sf_user->hasFlash('error_campagne')): ?>
			  <p><?php echo $sf_user->getFlash('error_campagne') ?></p>
			<?php endif; ?>
	        <div id="espace_admin" style="float: left; width: 670px;">
	            <div class="contenu clearfix">
	            	<?php include_partial('formCampagne', array('etablissement' => $etablissement,
	                                                            'form' => $formCampagne)) ?>
	            </div>
	        </div>
	        <?php endif; ?>
		<?php endif; ?>
</section>
