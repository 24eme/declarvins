<?php use_helper('Float'); ?>
<?php use_helper('Produit'); ?>

<div id="col_recolte_<?php echo $form->getObject()->getKey() ?>" class="col_recolte" style="<?php echo ($produit->pas_de_mouvement) ? 'opacity: 0.3;' : '' ?>">
    <form action="<?php echo url_for('drm_recap_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
        <h2><?php echo $form->getObject()->getCouleur()->getConfig()->libelle ?></h2>
        <div class="col_cont">
            <?php if($config_appellation->hasCepage()): ?>
            <p class="cepage"><?php echo $form->getObject()->getCepage()->getConfig()->libelle ?></p>
            <?php endif; ?>
            <?php if($config_appellation->hasMillesime()): ?>
            <p class="millesime"><?php echo $form->getObject()->getMillesime()->getConfig()->libelle ?></p>
            <?php endif; ?>
            <p class="label">
                <?php echo labelsLibelles($form->getObject()->getLabelLibelles()) ?><br />
                            <?php echo $form->getObject()->label_supplementaire ?></p>
            <div class="groupe" data-groupe-id="1">
                <p><input type="text" value="<?php echo echoFloat($form->getObject()->total_debut_mois) ?>" class="num num_float somme_stock_debut" readonly="readonly" /></p>
                <ul>
                    <?php foreach($form['stocks_debut'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['stocks_debut'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_fin'][$key]->getValue()),
                                                                        'class' => 'num num_float',
                        												'readonly' => 'readonly')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="2">
                <p><input type="text" value="<?php echo $form->getObject()->total_entrees ?>" class="num num_float somme_groupe somme_entrees" readonly="readonly" /></p>
                <ul>
                    <?php foreach($form['entrees'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => sprintFloat($form['entrees'][$key]->getValue()),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="3">
                <p><input type="text" value="<?php echo $form->getObject()->total_sorties ?>" class="num num_float somme_groupe somme_sorties" readonly="readonly" /></p>
                <ul>
                    <?php foreach($form['sorties'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => sprintFloat($form['sorties'][$key]->getValue()),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>  -->
            <div class="groupe" data-groupe-id="4">
                <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>
                <ul>
                    <?php foreach($form['stocks_fin'] as $key => $subform): ?>
                    <li>
                        <?php echo $form['stocks_fin'][$key]->render(array('data-val-defaut' => sprintFloat($form['stocks_fin'][$key]->getValue()),
                                                                        'class' => 'num num_float')) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col_btn">
				<button class="btn_valider" type="submit">Valider</button>
                <button class="btn_reinitialiser" type="submit">Annuler</button>
            </div>
        </div>
    </form>
</div>