<div id="col_recolte_<?php echo $form->getObject()->getKey() ?>" class="col_recolte" style="<?php echo ($produit->pas_de_mouvement) ? 'opacity: 0.3;' : '' ?>">
    <form action="<?php echo url_for('drm_recap_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
        <h2><?php echo $form['label']->render(array('data-val-defaut' => $form['label']->getValue(), 'style' => "width: 90px; background: transparent; border: none; color: #fff; font-weight: bold; text-align: center; font-size: 13px; outline: none;")) ?></h2>
        <div class="col_cont">
            <p>
                <?php echo $form['couleur']->render(array('data-val-defaut' => $form['couleur']->getValue())) ?>
            </p>
            <p>
                <?php echo $form['label_supplementaire']->render(array('data-val-defaut' => $form['label_supplementaire']->getValue())) ?>
            </p>

            <div class="groupe" data-groupe-id="1">
                <p>
                    <?php echo $form['stocks']['theorique']->render(array('data-val-defaut' => $form['stocks']['theorique']->getValue(),
                                                                        'class' => 'num num_float somme_stock_debut')) ?>
                </p>
                <ul>
                    <?php foreach($form['stocks'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['stocks'][$key]->render(array('data-val-defaut' => $form['stocks'][$key]->getValue(),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="2">
                <p><input type="text" value="<?php echo $form->getObject()->entrees->total ?>" data-val-defaut="<?php echo $form->getObject()->entrees->total ?>" class="num num_float somme_groupe somme_entrees" id="<?php echo $col_id; ?>-2" name="<?php echo $col_id; ?>-2" readonly="readonly" /></p>
                <ul>
                    <?php foreach($form['entrees'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => $form['entrees'][$key]->getValue(),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="3">
                <p><input type="text" value="<?php echo $form->getObject()->sorties->total ?>" data-val-defaut="<?php echo $form->getObject()->sorties->total ?>" class="num num_float somme_groupe somme_sorties" id="<?php echo $col_id; ?>-3" name="<?php echo $col_id; ?>-3" readonly="readonly" /></p>
                <ul>
                    <?php foreach($form['sorties'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => $form['sorties'][$key]->getValue(),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <p><input type="text" value="0" data-val-defaut="0" class="num num_float somme_stock_fin" id="<?php echo $col_id; ?>-4" name="<?php echo $col_id; ?>-4" readonly="readonly" /></p>

            <div class="col_btn">
                <div class="btn_col_inactive">
                    <button class="btn_modifier" type="submit">Modifier</button>
                    <button class="btn_supprimer" type="submit">Supprimer</button>
                </div>
                <div class="btn_col_active">
                    <button class="btn_reinitialiser" type="submit">Réinitialiser</button>
                    <button class="btn_valider" type="submit">Valider</button>
                </div>
            </div>
        </div>
    </form>
</div>