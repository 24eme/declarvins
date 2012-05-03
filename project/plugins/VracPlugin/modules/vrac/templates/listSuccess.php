<?php include_partial('global/navTop', array('active' => 'vrac')); ?>

<section id="contenu">
    
    <h1>Vrac</h1>
    
    <p class="intro">Bienvenue sur votre espace Vrac. Que voulez-vous faire ?</p>
    
    <section id="principal">
        <div id="recap_drm">
            <div id="drm_annee_courante" >
                <?php include_partial('vrac/historiqueList', array('config' => $config, 'vracs' => $historique->getVracActif())) ?>
            </div>
        </div>
    </section>
    <a href="<?php echo url_for('@vrac_historique') ?>">Votre historique complet &raquo;</a>


</section>
