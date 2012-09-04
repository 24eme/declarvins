
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
            <?php if (isset($form['type_transaction'])): ?>
            <div class="section_label_strong">
                <?php echo $form['type_transaction']->renderError() ?>
                <?php echo $form['type_transaction']->renderLabel() ?>
                <?php echo $form['type_transaction']->render() ?>
            </div>
            <?php endif; ?>
            <div  id="listener_product" class="section_label_strong">
                <?php echo $form['produit']->renderError() ?>
                <?php echo $form['produit']->renderLabel() ?>
                <?php echo $form['produit']->render() ?>
            </div>
            <div  class="section_label_strong">
                <?php echo $form['millesime']->renderError() ?>
                <?php echo $form['millesime']->renderLabel() ?>
                <?php echo $form['millesime']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['labels']->renderError() ?>
                <?php echo $form['labels']->renderLabel() ?>
                <?php echo $form['labels']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['mentions']->renderError() ?>
                <?php echo $form['mentions']->renderLabel() ?>
                <?php echo $form['mentions']->render() ?>
            </div>
        	<div class="section_label_strong">
            	<?php echo $form['export']->renderError() ?>
            	<?php echo $form['export']->renderLabel() ?>
            	<?php echo $form['export']->render() ?>
        	</div>
            <div class="section_label_strong">
                <?php echo $form['volume_propose']->renderError() ?>
                <?php echo $form['volume_propose']->renderLabel() ?>
                <?php echo $form['volume_propose']->render() ?> hl
            </div>
            <div class="section_label_strong">
                <?php echo $form['prix_unitaire']->renderError() ?>
                <?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?> €/hl
            </div>
            <div class="section_label_strong">
                <?php echo $form['prix_total']->renderError() ?>
                <?php echo $form['prix_total']->renderLabel() ?>
                <?php echo $form['prix_total']->render(array('disabled' => 'disabled')) ?> € (dont <span id="vrac_cotisation_interpro"><?php echo ($form->getObject()->getPrixTotal() && $form->getObject()->getPartCvo())? ($form->getObject()->getPrixTotal() * $form->getObject()->getPartCvo() / 100 * ConfigurationVrac::REPARTITION_CVO_ACHETEUR) : 0;?></span> € de cotisation interprofessionnelle acheteur).
            </div>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_determination_prix">
                <?php echo $form['type_prix']->renderError() ?>
                <?php echo $form['type_prix']->renderLabel() ?>
                <?php echo $form['type_prix']->render() ?> 
            </div>
            <div id="bloc_vrac_determination_prix" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo implode("|", $form->getTypePrixNeedDetermination()) ?>">
                <?php echo $form['determination_prix']->renderError() ?>
                <?php echo $form['determination_prix']->renderLabel() ?>
                <?php echo $form['determination_prix']->render() ?> 
            </div>
            <?php if(isset($form['annexe'])): ?>
            <div  class="section_label_strong">
                <?php echo $form['annexe']->renderError() ?>
                <?php echo $form['annexe']->renderLabel() ?>
                <?php echo $form['annexe']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['has_transaction'])): ?>
            <div class="contenu_onglet">
                <?php echo $form['has_transaction']->renderError() ?>
                <?php echo $form['has_transaction']->render() ?> 
                <?php echo $form['has_transaction']->renderLabel() ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a> 
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
    </form>
    <?php include_partial('url_product_template', array('vrac' => $form->getObject(), 'etablissement' => $etablissement)); ?>
