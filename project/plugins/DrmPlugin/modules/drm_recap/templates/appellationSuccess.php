<section id="contenu">
    <?php include_partial('drm_recap/ongletsAppellations', array('config_appellation' => $config_appellation, 
                                                                 'drm_appellation' => $drm_appellation)) ?>
    
    <div>
    <?php include_component('drm_recap', 'popupAppellationAjout', array('label' => $config_appellation->getLabel())) ?>
    </div>
    
    <?php include_partial('drm_recap/list', array('drm_appellation' => $drm_appellation,
                                                  'form' => $form)); ?>
    <div style="clear:both"></div>
    <a href="<?php echo url_for('drm_recap_ajout', $config_appellation); ?>">Ajouter une dénomination</a> 
</section>


