<ul class="nav nav-tabs text-center">
    <?php foreach ($contrats as $contrat): ?>
        <li role="presentation"<?php if ($contrat->id === $vrac->_id) { echo 'class="active"'; } ?>>
            <a href="<?php echo url_for('vrac_visualisation', ['identifiant' => $etablissement, 'contrat' => $contrat->value[VracHistoryView::VRAC_VIEW_NUM]]) ?>">
                <?php echo ($contrat->value[VracHistoryView::VRAC_REF_PLURIANNUEL] === null) ? 'Cadre' : 'Application' ?> <?php echo $contrat->value[VracHistoryView::VRAC_VIEW_NUM] ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>
