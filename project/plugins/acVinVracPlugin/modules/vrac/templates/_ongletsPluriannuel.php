<ul class="nav nav-tabs text-center vracs">
    <?php $i=0; foreach ($contrats as $contrat): ?>
        <li role="presentation"<?php if ($contrat->id === $vrac->_id) { echo ' class="active" style="font-weight: bold"'; } ?>>
            <a href="<?php echo url_for('vrac_visualisation', ['etablissement' => $etablissement, 'contrat' => str_replace('VRAC-', '', $contrat->value[VracHistoryView::VRAC_VIEW_NUMCONTRAT])]) ?>" class="visualisation_contrat">

                <?php echo ($contrat->value[VracHistoryView::VRAC_REF_PLURIANNUEL] === null) ? 'Cadre' : '<span class="statut '.statusColor($contrat->value[VracHistoryView::VRAC_VIEW_STATUT]).'" style="width:10px;height:10px;"></span> Application' ?>
                <?php echo (strpos($contrat->value[VracHistoryView::VRAC_VIEW_NUM], '-A') === false)? $contrat->value[VracHistoryView::VRAC_VIEW_NUM] : $i; ?>
            </a>
        </li>
    <?php $i++; endforeach ?>
</ul>
