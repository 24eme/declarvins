<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">
    
    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_DR_lieu-dit" data-doc="doc_a_telecharger.pdf" title="Message aide"></a></h1>
    
    <p class="intro">Bienvenue sur votre espace DRM. Que pensez-vous faire ?</p>
    
    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
                <?php include_component('drm', 'historiqueList', array('historique' => $historique, 'limit' => 5)) ?>
            </div>
        </div>
    </section>
    <a href="<?php echo url_for('@drm_historique') ?>">Votre historique complet &raquo;</a>
    
    
        <?php //if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
        <br /><br />
        <h1>Espace Admin <a href="" class="msg_aide" data-msg="help_popup_DR_lieu-dit" data-doc="doc_a_telecharger.pdf" title="Message aide"></a></h1>
    	<p class="intro">Bienvenue sur votre espace Administrateur. Que pensez-vous faire ?</p>
        <div id="espace_admin" style="float: left; width: 670px;">
            <div class="contenu clearfix">
            	<?php include_partial('formCampagne', array('form' => $formCampagne)) ?>
            </div>
        </div>
        <?php //endif; ?>

</section>
