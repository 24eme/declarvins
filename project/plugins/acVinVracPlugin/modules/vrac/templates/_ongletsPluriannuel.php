<ul class="nav nav-tabs text-center">
    <?php foreach ($contrats as $contrat): ?>
        <li role="presentation"<?php if ($contrat->id === $vrac->_id) { echo ' class="active" style="font-weight: bold"'; } ?>>
            <a href="<?php echo url_for('vrac_visualisation', ['etablissement' => $etablissement, 'contrat' => $contrat->value[VracHistoryView::VRAC_VIEW_NUM]]) ?>">
                <?php echo ($contrat->value[VracHistoryView::VRAC_REF_PLURIANNUEL] === null) ? 'Cadre' : 'Application' ?>
                <?php echo (strpos($contrat->value[VracHistoryView::VRAC_VIEW_NUM], '-A') === false)? $contrat->value[VracHistoryView::VRAC_VIEW_NUM] : substr($contrat->value[VracHistoryView::VRAC_VIEW_NUM], 0, strpos($contrat->value[VracHistoryView::VRAC_VIEW_NUM], '-A')); ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>
