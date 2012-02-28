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
                <?php include_component('drm_recap', 'list', array('libelle_detail_ligne' => $libelle_detail_ligne, 'drm_appellation' => $drm_appellation, 'config_appellation' => $config_appellation, 'form' => $form)); ?>
            </div>
            <div id="btn_etape_dr">
                <?php if ($produits_appellation = $drm_appellation->getProduits()->getPrevious()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $produits_appellation->getDeclaration()->getConfig()) ?>" class="btn_prec">
                <?php elseif ($produits_certification = $drm_appellation->getCertification()->getProduits()->getPrevious()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $produits_certification->getLast()->getDeclaration()->getConfig()) ?>" class="btn_prec">
                <?php else: ?>
                <a href="<?php echo url_for('drm_mouvements_generaux') ?>" class="btn_prec">
                <?php endif; ?>
                	<span>Précédent</span>
                </a>
                <?php if ($produits_appellation = $drm_appellation->getProduits()->getNext()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $produits_appellation->getDeclaration()->getConfig()) ?>" class="btn_suiv">
                <?php elseif ($produits_certification = $drm_appellation->getCertification()->getProduits()->getNext()): ?>
                <a href="<?php echo url_for('drm_recap_appellation', $produits_certification->getFirst()->getDeclaration()->getConfig()) ?>" class="btn_suiv">
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


