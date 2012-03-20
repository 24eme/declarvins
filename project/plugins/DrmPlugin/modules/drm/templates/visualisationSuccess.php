<?php use_helper('Float'); ?>
<?php include_partial('global/navTop', array('active' => 'drm')); ?>


<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>

    <!-- #principal -->
    <section id="principal">

        <div id="contenu_onglet">
            <?php include_partial('drm/recap', array('drm' => $drm)) ?>
        </div>

        <div id="contenu_onglet">
            <div class="tableau_ajouts_liquidations">
                <h2>Droits totaux</h2>
                <table class="tableau_recap" style="width: 300px;">
                    <tbody>
                        <tr class="alt">
                            <td>CVO</td>
                            <td><?php echo echoFloat($drm->getTotalCvo()) ?>€</td>
                        </tr>
                        <tr class="alt">
                            <td>Douane</td>
                            <td><?php echo echoFloat($drm->getTotalDouane()) ?>€</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tableau_ajouts_liquidations">
                <h2>Droits par code</h2>
                <table class="tableau_recap">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>CVO</th>
                            <th>Douane</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drm->getTotalDroitByCode() as $code => $value): $value = $value->getRawValue(); ?>
                        <tr class="alt">
                            <td><?php echo $code ?></td>
                            <td><?php echo (isset($value['cvo']))? echoFloat($value['cvo']) : echoFloat(0.00); ?></td>
                            <td><?php echo (isset($value['douane']))? echoFloat($value['douane']) : echoFloat(0.00); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <tbody>
                </table>
            </div>
        </div>

    	<a href="<?php echo url_for('drm_pdf', array('campagne_rectificative' => $drm->getCampagneAndRectificative())) ?>">Télécharger le PDF</a>

        <div id="btn_etape_dr">
            <?php if($drm_suivante && $drm_suivante->isRectificative()): ?>
            <a href="<?php echo url_for('drm_init', array('campagne_rectificative' => $drm_suivante->getCampagneAndRectificative())) ?>" class="btn_suiv">
                <span>Passer à la DRM suivante</span>
            </a>
            <?php else: ?>
            <a href="<?php echo url_for('drm_mon_espace') ?>" class="btn_suiv">
                <span>Retour à mon espace</span>
            </a>
            <?php endif; ?>
        </div>
        
    </section>
</section>
