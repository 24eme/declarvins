<ul id="onglets_principal">
    <?php foreach ($appellation_keys as $appellation_key): ?>
        <?php $appellation = $config_appellation->getCertification()->appellations->get($appellation_key); ?>
        <?php if ($appellation->getKey() == $config_appellation->getKey()): ?>
            <li class="actif">
                <strong>
                    <?php echo $appellation->libelle ?> 
                    <span class="completion completion_validee">(<span class="appellation_produit_saisie"><?php echo $appellations_updated[$appellation_key] ?></span>/<span class="appellation_produit_total"><?php echo $appellations[$appellation_key] ?></span>)</span>
                </strong>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo url_for('drm_recap_appellation', $appellation) ?>">
                    <?php echo $appellation->libelle ?> 
                    <span class="completion">(<span class="appellation_produit_saisie"><?php echo $appellations_updated[$appellation_key] ?></span>/<span class="appellation_produit_total"><?php echo $appellations[$appellation_key] ?></span>)</span>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    <li class="ajouter"><a class="btn_popup" data-popup="#popup_ajout_appelation" data-popup-ajax="true" data-popup-config="configForm" href="<?php echo url_for('drm_recap_appellation_ajout_ajax', $config_appellation->getCertification()) ?>">Ajouter une appellation</a></li>
</ul>
	