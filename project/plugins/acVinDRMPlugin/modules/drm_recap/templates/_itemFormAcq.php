<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>

<div id="col_recolte_acq_<?php echo $form->getObject()->getKey() ?>" class="col_recolte<?php if ($active): ?> col_active<?php endif; ?>" data-input-focus="#drm_detail_entrees_achat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->hasVersion()) ? versionnerCssClass() : '' ?>">
    <form action="<?php echo url_for('drm_recap_update', $form->getObject()) ?>?acquittes=1" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
        <h2><?php echo $form->getObject()->getCouleur()->getConfig()->libelle ?></h2>
        <div class="col_cont">
            <?php if($config_lieu->hasCepage()): ?>
            <p class="cepage"><?php echo $form->getObject()->getCepage()->getConfig()->libelle ?></p>
            <?php endif; ?>
            <p class="label" style="font-size: 12px; text-align: center;">
                <?php echo $form->getObject()->getLabelsLibelle() ?><br />
                <?php echo $form->getObject()->label_supplementaire ?>
            </p>
            <div class="groupe" data-groupe-id="5">
                <p class="<?php echo isVersionnerCssClass($form->getObject(), 'acq_total_debut_mois') ?>">
                    <?php echo $form['acq_total_debut_mois']->render(array('data-val-defaut' => sprintFloat($form->getObject()->acq_total_debut_mois, "%01.04f"), 'class' => 'num num_float somme_stock_debut')) ?>
                </p>
            </div>

            <div class="groupe" data-groupe-id="6">
                <p class="<?php echo isVersionnerCssClass($form->getObject(), 'acq_total_entrees') ?>">
                    <input type="text" value="<?php echo $form->getObject()->acq_total_entrees ?>" class="num num_float somme_groupe somme_entrees" data-val-defaut="<?php echo $form->getObject()->acq_total_entrees ?>" readonly="readonly" />
                </p>
                <ul>
                    <?php $nbItem = count($form['entrees']); $i=0; foreach($form['entrees'] as $key => $subform): $i++; ?>
                    <?php $class = 'num num_float'; if ($i==1) $class .= ' premier'; if ($i==$nbItem) $class .= ' dernier';?>
                    <li class="<?php echo isVersionnerCssClass($form->getObject()->entrees, $key) ?>">
                        <?php echo $form['entrees'][$key]->render(array('data-val-defaut' => sprintFloat($form['entrees'][$key]->getValue(), "%01.04f"),
                                                                        'class' => $class)) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="groupe" data-groupe-id="7">
                <p class="<?php echo isVersionnerCssClass($form->getObject(), 'acq_total_sorties') ?>">
                    <input type="text" value="<?php echo $form->getObject()->acq_total_sorties ?>" class="num num_float somme_groupe somme_sorties" data-val-defaut="<?php echo $form->getObject()->acq_total_sorties ?>" readonly="readonly" />
                </p>
                <ul>
                    <?php  $nbItem = count($form['sorties']); $i=0; foreach($form['sorties'] as $key => $subform): $i++; ?>
                    <?php $class = 'num num_float'; if ($i==1) $class .= ' premier'; if ($i==$nbItem) $class .= ' dernier';?>
                    <li class="<?php echo isVersionnerCssClass($form->getObject()->sorties, $key) ?>">
                        <?php echo $form['sorties'][$key]->render(array('data-val-defaut' => sprintFloat($form['sorties'][$key]->getValue(), "%01.04f"),
                                                                        'class' => $class)) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- <p><input type="text" value="0" class="num num_float somme_stock_fin" readonly="readonly" /></p>  -->
            <div class="groupe" data-groupe-id="8">
                <p class="<?php echo isVersionnerCssClass($form->getObject(), 'acq_total') ?>">
                    <input type="text" value="<?php echo $form->getObject()->acq_total ?>" class="num num_float somme_stock_fin" readonly="readonly" data-val-defaut="<?php echo sprintFloat($form->getObject()->acq_total, "%01.04f") ?>" />
                </p>
            </div>

            <div class="col_btn">
                <button class="btn_valider" type="submit">Valider</button>
                <button class="btn_reinitialiser" type="submit">Annuler</button>
            </div>
        </div>
    </form>
</div>