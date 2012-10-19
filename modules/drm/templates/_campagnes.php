<ul id="nav_drm_annees">
    <?php $first = true; foreach ($campagnes as $c): ?>
        <?php if ($first): ?>
            <?php if ($campagne == $c): ?>
                    <li class="actif">
                        <strong>DRM <?php echo $c ?></strong>
                    </li>
            <?php else: ?>
                    <li><a href="<?php echo url_for('drm_mon_espace', $etablissement)?>">
                        DRM <?php echo $c ?>
                    </a></li>
            <?php endif;?>
        <?php else: ?>
            <?php if ($campagne == $c): ?>
                <li>
                    <strong>DRM <?php echo $c ?></strong>
                </li>
            <?php else: ?>
                <li><a href="<?php echo url_for('drm_historique', array('campagne' => $c, 'identifiant' => $etablissement->identifiant))?>">
                    DRM <?php echo $c ?>
                </a></li>
            <?php endif; ?>
        <?php endif;?>
    <?php $first = false; endforeach; ?>
</ul>