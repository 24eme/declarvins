
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
        	<h1>Produit</h1>
        	<div class="section_label_strong">
            	<label>Produit: </label>
                <strong><?php echo ($form->getObject()->produit)? $form->getObject()->getLibelleProduit("%g% %a% %l% %co%", true) : null; ?><?php if ($form->getObject()->millesime): ?>&nbsp;<?php echo $form->getObject()->millesime; ?><?php endif; ?></strong>
            </div>
            <?php if (isset($form['cepages'])): ?>
            <div id="section_cepages" class="section_label_strong">
                <?php echo $form['cepages']->renderError() ?>
                <?php echo $form['cepages']->renderLabel() ?>
                <?php echo $form['cepages']->render() ?>
            </div>
            <?php endif; ?>

        	<h1>Volume / Prix</h1>
            <div class="section_label_strong">
                <?php echo $form['volume_propose']->renderError() ?>
                <?php echo $form['volume_propose']->renderLabel() ?>
                <?php echo $form['volume_propose']->render() ?> <strong><?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
            </div>
            <div class="section_label_strong">
                <?php echo $form['prix_unitaire']->renderError() ?>
                <?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong><?php if (isset($form['prix_total_unitaire'])): ?><span id="vrac_cotisation_interpro" data-cotisation-value="<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>">&nbsp;+&nbsp;<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>&nbsp;€ HT / HL de cotisation interprofessionnelle acheteur (<?php echo (ConfigurationVrac::REPARTITION_CVO_ACHETEUR)? ConfigurationVrac::REPARTITION_CVO_ACHETEUR*100 : 0; ?>%).</span><?php endif; ?>
            </div>
            <?php if (isset($form['prix_total_unitaire'])): ?>
            <div class="section_label_strong">
                <?php echo $form['prix_total_unitaire']->renderError() ?>
                <?php echo $form['prix_total_unitaire']->renderLabel() ?>
                <?php echo $form['prix_total_unitaire']->render(array('disabled' => 'disabled')) ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
            </div>
            <?php endif; ?>
            <div id="vrac_type_prix" class="section_label_strong bloc_condition" data-condition-cible="<?php if (isset($form['mercuriale_mois']) && isset($form['mercuriale_annee']) && isset($form['variation_hausse']) && isset($form['variation_baisse'])): ?>#bloc_vrac_mercuriale|#bloc_vrac_var_baisse|#bloc_vrac_var_hausse|<?php endif; ?>#bloc_vrac_determination_prix|#bloc_vrac_determination_prix_date">
                <?php echo $form['type_prix']->renderError() ?>
                <?php echo $form['type_prix']->renderLabel() ?>
                <?php echo $form['type_prix']->render() ?> 
            </div>
            <div id="bloc_vrac_determination_prix_date" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo implode("|", $form->getTypePrixNeedDetermination()) ?>">
                <?php echo $form['determination_prix_date']->renderError() ?>
                <?php echo $form['determination_prix_date']->renderLabel('Date de détermination du prix définitif*: <a href="" class="msg_aide" data-msg="help_popup_vrac_determination_prix_date" title="Message aide"></a>') ?>
                <?php echo $form['determination_prix_date']->render(array('class' => 'datepicker')) ?> 
            </div>
            <div id="bloc_vrac_determination_prix" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo implode("|", $form->getTypePrixNeedDetermination()) ?>">
                <?php echo $form['determination_prix']->renderError() ?>
                <?php echo $form['determination_prix']->renderLabel('Modalité de fixation du prix définitif ou de révision du prix* (celui-ci sera communiqué à l\'interprofession par les parties au contrat)') ?>
                <?php echo $form['determination_prix']->render(array("style" => "height: 60px;")) ?> 
            </div>
            <h1>Paiement</h1>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_paiements|#bloc_vrac_delai">
                <?php echo $form['conditions_paiement']->renderError() ?>
                <?php echo $form['conditions_paiement']->renderLabel() ?>
                <?php echo $form['conditions_paiement']->render() ?>
            </div>
            <div id="bloc_vrac_paiements" class="table_container bloc_conditionner" data-condition-value="<?php echo $form->getCgpEcheancierNeedDetermination() ?>">
            	<!--  <p>Rappel du volume total proposé : <strong><?php echo $form->getObject()->volume_propose ?>&nbsp;hl</strong></p>  -->
                <table id="table_paiements">
                    <thead>
                        <tr>
                            <th>Date (jj/mm/aaaa)</th>
                            <th>Montant du paiement (€ HT)</th>
                            <th class="dernier"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($form['paiements'] as $formPaiement): ?>
                        <?php include_partial('form_paiements_item', array('form' => $formPaiement)) ?>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><a class="btn_ajouter_ligne_template" data-container="#table_paiements tbody" data-template="#template_form_paiements_item" href="#"><span>Ajouter</span></a></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php if(isset($form['delai_paiement'])): ?>
            <?php if ($form->isConditionneDelaiPaiement()): ?>
            <div id="bloc_vrac_delai" class="section_label_strong bloc_conditionner bloc_condition" data-condition-value="<?php echo $form->getCgpDelaiNeedDetermination() ?>" data-condition-cible="#bloc_vrac_delai_autre">
            <?php else: ?>
            <div class="section_label_strong">
            <?php endif; ?>
                <?php echo $form['delai_paiement']->renderError() ?>
                <?php echo $form['delai_paiement']->renderLabel() ?>
                <?php echo $form['delai_paiement']->render() ?>
                <p style="padding: 10px 0 0 210px;"><em><strong>Acompte obligatoire de 15%</strong> dans les 10 jours suivants la signature du contrat</em></p>
            </div>
            <?php endif; ?>
            <?php if(isset($form['delai_paiement_autre'])): ?>
            <div id="bloc_vrac_delai_autre" class="section_label_strong bloc_conditionner" data-condition-value="autre">
                <?php echo $form['delai_paiement_autre']->renderError() ?>
                <?php echo $form['delai_paiement_autre']->renderLabel() ?>
                <?php echo $form['delai_paiement_autre']->render() ?>
            </div>
            <?php endif; ?>
            
            <h1>Retiraison / Enlèvement</h1>
            <div class="section_label_strong">
                <?php echo $form['type_retiraison']->renderError() ?>
                <?php echo $form['type_retiraison']->renderLabel() ?>
                <?php echo $form['type_retiraison']->render() ?>
            </div>
            <?php if (!$form->conditionneIVSE()): ?>
            <div class="section_label_strong">
                <?php echo $form['vin_livre']->renderError() ?>
                <?php echo $form['vin_livre']->renderLabel() ?>
                <?php echo $form['vin_livre']->render() ?>
            </div>
            <?php endif; ?>
                <?php if(isset($form['date_debut_retiraison'])): ?>
                <div class="section_label_strong">
                    <?php echo $form['date_debut_retiraison']->renderError() ?>
                    <?php echo $form['date_debut_retiraison']->renderLabel() ?>
                    <?php echo $form['date_debut_retiraison']->render(array('class' => 'datepicker')) ?>
                    &nbsp;(jj/mm/aaaa)
                </div>
                <?php endif; ?>
                <div class="section_label_strong">
                    <?php echo $form['date_limite_retiraison']->renderError() ?>
                    <?php echo $form['date_limite_retiraison']->renderLabel() ?>
                    <?php echo $form['date_limite_retiraison']->render(array('class' => 'datepicker')) ?>
                    &nbsp;(jj/mm/aaaa)
                </div>
                <?php if(isset($form['clause_reserve_retiraison'])): ?>
                <div class="section_label_strong">
                    <?php echo $form['clause_reserve_retiraison']->renderError() ?>
                    <?php echo $form['clause_reserve_retiraison']->renderLabel() ?>
                    <?php echo $form['clause_reserve_retiraison']->render() ?>
                </div>
                <?php endif; ?>
            	<?php if ($form->conditionneIVSE()): ?>
            	<p>En cas de calendrier de retiraison, indiquez les échéances dans la case &laquo;commentaires&raquo; de l'étape validation</p>
            	
            	<?php endif; ?>
        </div>

        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('<?php if ($form->getObject()->hasVersion()): ?>Attention, vous êtes sur le point d\'annuler les modifications en cours<?php else: ?>Attention, ce contrat sera supprimé de la base<?php endif; ?>')"><span><?php if($form->getObject()->hasVersion()): ?>Annuler les modifications<?php else: ?>supprimer le contrat<?php endif; ?></span></a>
        </div> 
    </form>
<?php include_partial('form_collection_template', array('partial' => 'form_paiements_item',
    'form' => $form->getFormTemplatePaiements()));
?>