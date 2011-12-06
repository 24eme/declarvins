<?php include_partial('global/navTop'); ?>

<section id="contenu">

    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'recapitulatif', 'label' => $config_appellation->getLabel()->getKey(), 'pourcentage' => '30')); ?>

    <!-- #principal -->
    <section id="principal">
        <div id="application_dr">
            <?php include_component('drm_recap', 'onglets', array('config_appellation' => $config_appellation, 'drm_appellation' => $drm_appellation)) ?>

            <div id="contenu_onglet">
                
                <div id="popup_appellation_ajout" class="notice" style="display: none;">
                    <?php include_component('drm_recap', 'popupAppellationAjout', array('label' => $config_appellation->getLabel())) ?>
                </div>
                
                

                <?php include_partial('drm_recap/list', array('drm_appellation' => $drm_appellation, 'config_appellation' => $config_appellation, 'form' => $form)); ?>
                
                <div id="popup_raccourci" class="notice">
                    <h2>Raccourcis clavier :</h2>
                    <ul>
                        <li><kbd>Ctrl</kbd> + <kbd>Gauche</kbd> : Aller à la colonne de gauche</li>
                        <li><kbd>Ctrl</kbd> + <kbd>Droite</kbd> : Aller à la colonne de droite</li>
                        <li><kbd>Ctrl</kbd> + <kbd>M</kbd> : Commencer la saisie de la colonne courante</li>
                        <li><kbd>Ctrl</kbd> + <kbd>Suppr</kbd> : Supprimer la colonne courante</li>
                        <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> : Réinitialiser les valeurs de la colonne courante</li>
                        <li><kbd>Ctrl</kbd> + <kbd>Entrée</kbd> : Valider la colonne courante</li>
                    </ul>
                </div>
            
            </div>

            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_mouvements_generaux') ?>" class="btn_prec">Précédent</a>
                <a href="#" class="btn_suiv">Suivant</a>
            </div>
        </div>
    </section>
</section>


