<div id="col_recolte_<?php echo $form->getObject()->getKey() ?>" class="col_recolte" style="<?php echo ($produit->pas_de_mouvement) ? 'opacity: 0.3;' : '' ?>">
    <form action="<?php echo url_for('drm_recap_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
        <h2><?php echo $form->getObject()->getCouleur()->getKey() ?></h2>
        <div class="col_cont">
            <p class="label"><?php echo implode(', ', $form->getObject()->label->toArray()) ?><br />
                            <?php echo $form->getObject()->label_supplementaire ?></p>
            <div class="groupe" data-groupe-id="1">
                <p><input type="text" value="<?php echo $form->getObject()->total_debut_mois ?>" data-val-defaut="<?php echo $form->getObject()->total_debut_mois ?>" class="num num_float somme_stock_debut" id="<?php echo $col_id; ?>-1" name="<?php echo $col_id; ?>-1" readonly="readonly" /></p>
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
                <p><input type="text" value="<?php echo $form->getObject()->total_entrees ?>" data-val-defaut="<?php echo $form->getObject()->total_entrees ?>" class="num num_float somme_groupe somme_entrees" id="<?php echo $col_id; ?>-2" name="<?php echo $col_id; ?>-2" readonly="readonly" /></p>
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
                <p><input type="text" value="<?php echo $form->getObject()->total_sorties ?>" data-val-defaut="<?php echo $form->getObject()->total_sorties ?>" class="num num_float somme_groupe somme_sorties" id="<?php echo $col_id; ?>-3" name="<?php echo $col_id; ?>-3" readonly="readonly" /></p>
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
                <button class="btn_reinitialiser" type="submit">Réinitialiser</button>
				<button class="btn_valider" type="submit">Valider</button>
            </div>
        </div>
    </form>
</div>