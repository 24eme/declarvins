<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header'); ?>
    <?php include_component('drm', 'etapes', array('etape' => 'recapitulatif', 'certification' => $config_appellation->getCertification()->getKey(), 'pourcentage' => '30')); ?>

    <!-- #principal -->
    <section id="principal">
        <div id="application_dr">
            <?php include_component('drm_recap', 'onglets', array('config_appellation' => $config_appellation, 'drm_appellation' => $drm_appellation)) ?>
            <div id="contenu_onglet">
				<!--<div class="notice">
					<h2>Raccourcis clavier :</h2>
					<ul>
						<li><kbd>Ctrl</kbd> + <kbd>Gauche</kbd> : Aller à la colonne de gauche</li>
						<li><kbd>Ctrl</kbd> + <kbd>Droite</kbd> : Aller à la colonne de droite</li>
						<li><kbd>Ctrl</kbd> + <kbd>M</kbd> : Commencer la saisie de la colonne courante</li>
						<li><kbd>Ctrl</kbd> + <kbd>Z</kbd> : Réinitialiser les valeurs de la colonne courante</li>
						<li><kbd>Ctrl</kbd> + <kbd>Entrée</kbd> : Valider la colonne courante</li>
					</ul>
				</div>-->
                <?php include_component('drm_recap', 'list', array('drm_appellation' => $drm_appellation, 'config_appellation' => $config_appellation, 'form' => $form)); ?>
            </div>
            <div id="btn_etape_dr">
                <?php if ($appellation = $drm_appellation->getProduitsAppellation()->getPrevious()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $config_appellation->getCertification()->appellations->get($appellation->getKey())) ?>" class="btn_prec">
                <?php elseif ($certification = $drm_appellation->getCertification()->getProduitsCertification()->getPrevious()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $drm_appellation->getDocument()->declaration->certifications->get($certification->getKey())->appellations->getLast()) ?>" class="btn_prec">
                <?php else: ?>
                <a href="<?php echo url_for('drm_mouvements_generaux') ?>" class="btn_prec">
                <?php endif; ?>
                	<span>Précédent</span>
                </a>
                <?php if ($appellation = $drm_appellation->getProduitsAppellation()->getNext()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $config_appellation->getCertification()->appellations->get($appellation->getKey())) ?>" class="btn_suiv">
                <?php elseif ($certification = $drm_appellation->getCertification()->getProduitsCertification()->getNext()): ?>
                <a href="<?php echo url_for('drm_recap', $config_appellation->getDocument()->declaration->certifications->get($certification->getKey())) ?>" class="btn_suiv">
                <?php else: ?>
                <a href="<?php echo url_for('drm_vrac') ?>" class="btn_suiv">
                <?php endif; ?>
                	<span>Suivant</span>
                </a>
            </div>
        </div>
    </section>
</section>
<?php //$run->end(); ?>


