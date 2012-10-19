<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">
    
    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_monespace" data-doc="<?php echo url_for('drm_notice') ?>" title="Message aide"></a></h1>
    
    <p class="intro">Bienvenue sur votre espace DRM. Que voulez-vous faire ?</p>
    
    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
                <?php include_component('drm', 'historiqueList', array('etablissement' => $etablissement)) ?>
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
