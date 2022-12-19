<ul class="nav nav-tabs text-center">
    <?php foreach ($contrats as $contrat): ?>
        <li role="presentation"<?php if ($contrat->id === $vrac->_id) { echo 'class="active"'; } ?>>
            <a href="<?php echo url_for('vrac_visualisation', ['identifiant' => $etablissement, 'contrat' => $contrat->value[VracHistoryView::VRAC_VIEW_NUM]]) ?>">
                <?php echo $contrat->id ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>
