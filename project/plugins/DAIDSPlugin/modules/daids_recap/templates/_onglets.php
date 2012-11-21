<ul id="onglets_principal">
    <?php foreach ($items as $item): ?>
            <?php if ($item->getHash() == $daids_lieu->getHash()): ?>
                <li class="actif">
                    <strong>
                        <?php echo $item->getLibelle(ESC_RAW) ?>
                        <span class="completion<?php if($item->isComplete()): ?> completion_validee<?php endif; ?>">
                            (<span class="appellation_produit_saisie"><?php echo $item->nbComplete() ?></span>/<span class="appellation_produit_total"><?php echo $item->nbToComplete() ?></span>)
                        </span>
                    </strong>
                </li>
            <?php else: ?>
                <li>
                    <a title="<?php echo $item->getLibelle(ESC_RAW) ?>" href="<?php echo url_for('daids_recap_lieu', $item) ?>">
                        <?php echo $item->getConfig()->getCodeFormat("%a%%l%") ?> 
                        <span class="completion<?php if($item->isComplete()): ?> completion_validee<?php endif; ?>">
                            (<span class="appellation_produit_saisie"><?php echo $item->nbComplete() ?></span>/<span class="appellation_produit_total"><?php echo $item->nbToComplete() ?></span>)
                        </span>
                    </a>
                </li>
            <?php endif; ?>
    <?php endforeach; ?>
</ul>