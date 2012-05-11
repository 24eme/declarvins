<ul id="onglets_principal">
 <div id="msg_aide_drm">
        <a href="" class="msg_aide" data-msg="help_popup_drm_global" data-doc="notice.pdf" title="Message aide"></a>
 </div>
    <?php foreach ($appellation_keys as $appellation_hash): ?>
        <?php $appellation = $drm_appellation->getDocument()->get($appellation_hash)->getDeclaration() ?>
        <?php if ($appellation->getProduits()->hasMouvement()): ?>
        <?php if ($appellation->getKey() == $config_appellation->getKey()): ?>
            <li class="actif">
                <strong>
                    <?php echo $appellation->getConfig()->libelle ?> 
                    <span class="completion<?php if($appellations_updated[$appellation_hash] == $appellations[$appellation_hash]): ?> completion_validee<?php endif; ?>">
                        (<span class="appellation_produit_saisie"><?php echo $appellations_updated[$appellation_hash] ?></span>/<span class="appellation_produit_total"><?php echo $appellations[$appellation_hash] ?></span>)
                    </span>
                </strong>
            </li>
        <?php else: ?>
            <li>
                <a title="<?php echo $appellation->getConfig()->libelle ?>" href="<?php echo url_for('drm_recap_appellation', $appellation) ?>">
                    <?php echo $appellation->getConfig()->code ?> 
                    <span class="completion<?php if($appellations_updated[$appellation_hash] == $appellations[$appellation_hash]): ?> completion_validee<?php endif; ?>">
                        (<span class="appellation_produit_saisie"><?php echo $appellations_updated[$appellation_hash] ?></span>/<span class="appellation_produit_total"><?php echo $appellations[$appellation_hash] ?></span>)
                    </span>
                </a>
            </li>
        <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    <li class="ajouter"><a class="btn_popup" data-popup="#popup_ajout_appelation" data-popup-ajax="true" data-popup-config="configForm" href="<?php echo url_for('drm_recap_appellation_ajout_ajax', $drm_appellation->getCertification()) ?>">Ajouter une appellation</a></li>
</ul>