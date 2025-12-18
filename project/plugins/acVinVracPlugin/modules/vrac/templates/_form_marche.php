
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
        	<h1>Produit</h1>
        	<div class="section_label_strong">
            	<label><?php if($form->getObject()->isConditionneIvse()): ?>Dénomination<?php else: ?>Appellation<?php endif; ?> concernée: </label>
                <strong><?php echo ($form->getObject()->produit)? $form->getObject()->getLibelleProduit("%g% %a% %l% %co%", true) : null; ?><?php if ($form->getObject()->millesime): ?>&nbsp;<?php echo $form->getObject()->millesime; ?><?php endif; ?></strong>
            </div>
            <?php if (isset($form['cepages'])): ?>
            <div id="section_cepages" class="section_label_strong">
                <?php echo $form['cepages']->renderError() ?>
                <?php echo $form['cepages']->renderLabel() ?>
                <?php echo $form['cepages']->render() ?>
            </div>
            <?php endif; ?>

        	<h1><?php if($form->getObject()->type_transaction == 'raisin'): ?>Quantité<?php else: ?>Volume<?php endif; ?> / Prix applicables</h1>

            <?php if (isset($form['contractualisation'])): ?>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_pourcentage_recolte|#bloc_surface|#bloc_volume_propose">
                <?php echo $form['contractualisation']->renderError() ?>
                <?php echo $form['contractualisation']->renderLabel() ?>
                <?php echo $form['contractualisation']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['pourcentage_recolte'])): ?>
            <div id="bloc_pourcentage_recolte" class="section_label_strong bloc_conditionner" data-condition-value="recolte">
                <?php echo $form['pourcentage_recolte']->renderError() ?>
                <?php echo $form['pourcentage_recolte']->renderLabel() ?>
                <?php echo $form['pourcentage_recolte']->render() ?>  <strong>%</strong>
                <p style="padding: 10px 0 0 210px;"><em><strong>Un pourcentage de la récolte</strong> totale revendiquée dans l'appellation d’origine contrôlée objet du contrat, provenant de son exploitation et indiquée sur sa déclaration de récolte.</em></p>
            </div>
            <?php endif; ?>
            <?php if (isset($form['surface'])): ?>
            <div id="bloc_surface" class="section_label_strong bloc_conditionner" data-condition-value="surface">
                <?php echo $form['surface']->renderLabel() ?>
                <?php echo $form['surface']->render() ?> <strong>HA</strong>
                <p style="padding: 10px 0 0 210px;"><em>La production revendiquée dans l'appellation d’origine contrôlée objet du contrat, provenant de son exploitation et récoltés sur <strong>la surface</strong> en production mentionnées.</em></p>
            </div>
            <?php endif; ?>
            <?php if (isset($form['contractualisation'])): ?>
                <div id="bloc_volume_propose" class="section_label_strong bloc_conditionner" data-condition-value="volume">
            <?php else: ?>
                <div class="section_label_strong">
            <?php endif; ?>
                <?php echo $form['volume_propose']->renderError() ?>
                <?php echo $form['volume_propose']->renderLabel() ?>
                <?php echo $form['volume_propose']->render() ?> <strong><?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
            </div>
            <?php if ($form->getObject()->isConditionneIr()) : ?>
                    <?php if (isset($form['type_prix_1'])&&isset($form['type_prix_2'])): ?>
                        <div id="vrac_type_prix" class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_type_prix|#bloc_vrac_determination_prix|#bloc_vrac_determination_prix_date|#bloc_vrac_pluriannuel_prix|#bloc_vrac_prix_unitaire|#bloc_vrac_prix_total_unitaire">
                            <?php echo $form['type_prix_1']->renderError() ?>
                            <?php echo $form['type_prix_1']->renderLabel() ?>
                            <?php echo $form['type_prix_1']->render() ?>
                        </div>
                        <div id="bloc_vrac_type_prix" class="section_label_strong bloc_conditionner" data-condition-value="non_definitif">
                            <?php echo $form['type_prix_2']->renderError() ?>
                            <?php echo $form['type_prix_2']->renderLabel() ?>
                            <?php echo $form['type_prix_2']->render() ?>
                        </div>
                        <em style="width:470px; display:block;margin:5px 0px 20px 0px;"><?php echo html_entity_decode($configurationVrac->clauses->prix->description) ?></em>
                    <?php endif; ?>

                    <?php if ($form->getObject()->isPluriannuel()): ?>
                        <?php if (isset($form['pluriannuel_prix_plancher'])&&isset($form['pluriannuel_prix_plafond'])&&isset($form['pluriannuel_clause_indexation'])): ?>
                            <div id="bloc_vrac_pluriannuel_prix" data-condition-value="determinable">
                                <div class="section_label_strong">
                                    <?php echo $form['pluriannuel_prix_plancher']->renderError() ?>
                                    <?php echo $form['pluriannuel_prix_plancher']->renderLabel() ?>
                                    <?php echo $form['pluriannuel_prix_plancher']->render() ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
                                </div>
                                <div class="section_label_strong">
                                    <?php echo $form['pluriannuel_prix_plafond']->renderError() ?>
                                    <?php echo $form['pluriannuel_prix_plafond']->renderLabel() ?>
                                    <?php echo $form['pluriannuel_prix_plafond']->render() ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
                                    <p style="padding: 10px 0 0 210px;">Pour chacune des campagnes suivantes, le prix plancher et le prix plafond sont déterminés en appliquant la clause d'indexation suivante :</p>
                                </div>
                                <div class="section_label_strong">
                                    <?php echo $form['pluriannuel_clause_indexation']->renderError() ?>
                                    <?php echo $form['pluriannuel_clause_indexation']->renderLabel() ?>
                                    <?php echo $form['pluriannuel_clause_indexation']->render() ?>
                                    <p style="padding: 10px 0 0 210px;"><em>Les indicateurs pouvant être pris en compte sont ceux relatifs aux coûts pertinents de production en agriculture et à l’évolution de ces coûts, ceux relatifs aux prix des produits agricoles et alimentaires constatés sur le ou les marchés où opère l’acheteur et à l’évolution de ces prix ou encore ceux relatifs aux quantités, à la composition, à la qualité, à la traçabilité des produits ou au respect d’un cahier des charges.</em></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    <div id="bloc_vrac_determination_prix" class="section_label_strong_2 bloc_conditionner" data-condition-value=<?php if ($form->getObject()->isPluriannuel()): ?> "determine"<?php else : ?>"determinable"<?php endif; ?>>
                        <?php if ($form->getObject()->isPluriannuel()): ?>
                            <?php echo $form['determination_prix']->renderLabel("<strong>REVISION DU PRIX DETERMINE</strong><br /><em>Clause obligatoire si la durée du contrat est égale ou supérieure à 3 ans.</em>") ?>
                            <span style="width:580px; display:inline-block;margin-top:10px;"><?php echo html_entity_decode($configurationVrac->revision_prix_determine) ?></span>
                            <?php else : ?>
                        <?php echo $form['determination_prix']->renderLabel("Modalité de fixation du prix déterminé ou de révision du prix*: <br /> (celui-ci sera communiqué à l'interprofession par les parties au contrat)") ?>
                        <span style="width:580px; display:inline-block;margin-top:10px;"><?php echo html_entity_decode($configurationVrac->prix_determinable) ?></span>
                        <?php endif; ?>
                        <?php echo $form['determination_prix']->renderError() ?>
                        <?php echo $form['determination_prix']->render(array("style" => "height: 60px;width:570px;")) ?>
                    </div>
                    <div id="bloc_vrac_determination_prix_date" class="section_label_strong bloc_conditionner" data-condition-value=<?php if ($form->getObject()->isPluriannuel()): ?> "determine"<?php else : ?>"non_definitif|determinable""<?php endif; ?>>
                        <?php echo $form['determination_prix_date']->renderError() ?>
                            <?php echo $form['determination_prix_date']->renderLabel("Date de fixation du prix définitif*:") ?>
                        <?php echo $form['determination_prix_date']->render(array('class' => 'datepicker')) ?>
                    </div>
                    <div id="bloc_vrac_determination_prix" class="section_label_strong bloc_conditionner" data-condition-value="non_definitif">
                        <?php echo $form['determination_prix']->renderError() ?>
                        <?php echo $form['determination_prix']->renderLabel() ?>
                        <?php echo $form['determination_prix']->render(array("style" => "height: 60px;")) ?>
                    </div>
            <?php endif; ?>
            <?php if($form->getObject()->isAdossePluriannuel() && $form->getObject()->pluriannuel_prix_plancher): ?>
                <p style="padding-left: 210px;">
                    <svg style="vertical-align: -.35em;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    le prix doit être compris entre <?php echo round($form->getObject()->pluriannuel_prix_plancher,2) ?> € HT / HL et <?php echo round($form->getObject()->pluriannuel_prix_plafond,2) ?> € HT / HL<?php if($form->getObject()->exist('pluriannuel_clause_indexation') && $form->getObject()->pluriannuel_clause_indexation): ?> (il faudra tenir compte de la clause d'indexation des prix, à savoir :  <?php echo $form->getObject()->pluriannuel_clause_indexation ?>).<?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if (isset($form['prix_unitaire'])&&isset($form['prix_unitaire'])): ?>
            <div class="section_label_strong" <?php if ($form->getObject()->isPluriannuel()) : ?> id="bloc_vrac_prix_unitaire" data-condition-value="determine"> <?php endif; ?>
                <?php echo $form['prix_unitaire']->renderError() ?>
                <?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong><?php if($form->getObject()->type_transaction == 'vrac' && $form->getObject()->premiere_mise_en_marche): ?><span id="vrac_cotisation_interpro" data-cotisation-value="<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>">&nbsp;+&nbsp;<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>&nbsp;€ HT / HL de cotisation interprofessionnelle<?php if(!$form->conditionneIVSE()): ?> acheteur<?php endif; ?> (<?php echo (ConfigurationVrac::REPARTITION_CVO_ACHETEUR)? ConfigurationVrac::REPARTITION_CVO_ACHETEUR*100 : 0; ?>%)*.</span><p style="padding-left:440px;">(*) Valeur indicative. Le taux CVO qui s’appliquera sera celui en vigueur au moment de la retiraison.</p><?php endif; ?>
            </div>
            <?php if (isset($form['prix_total_unitaire'])): ?>
            <div class="section_label_strong">
                <?php echo $form['prix_total_unitaire']->renderError() ?>
                <?php echo $form['prix_total_unitaire']->renderLabel() ?>
                <?php echo $form['prix_total_unitaire']->render(array('disabled' => 'disabled')) ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
            </div>
            <?php endif; ?>
            <div class="section_label_strong" <?php if ($form->getObject()->isPluriannuel()) : ?> id="bloc_vrac_prix_total_unitaire" data-condition-value="determine"> <?php endif; ?>
            	<label>Prix total HT:</label>
            	<strong><span id="prix_total_contrat">0.0</span> € HT</strong>
            </div>
            <?php endif; ?>
             <?php if (! $form->getObject()->isConditionneIr()) : ?>
                <?php if (isset($form['pluriannuel_prix_plancher'])&&isset($form['pluriannuel_prix_plafond'])&&isset($form['pluriannuel_clause_indexation'])): ?>
                <div class="section_label_strong">
                    <?php echo $form['pluriannuel_prix_plancher']->renderError() ?>
                    <?php echo $form['pluriannuel_prix_plancher']->renderLabel() ?>
                    <?php echo $form['pluriannuel_prix_plancher']->render(array( 'required' => 'required')) ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
                </div>
                <div class="section_label_strong">
                    <?php echo $form['pluriannuel_prix_plafond']->renderError() ?>
                    <?php echo $form['pluriannuel_prix_plafond']->renderLabel() ?>
                    <?php echo $form['pluriannuel_prix_plafond']->render(array( 'required' => 'required')) ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
                    <p style="padding: 10px 0 0 210px;">Pour chacune des campagnes suivantes, le prix plancher et le prix plafond sont déterminés en appliquant la clause d'indexation suivante :</p>
                </div>
                <div class="section_label_strong">
                    <?php echo $form['pluriannuel_clause_indexation']->renderError() ?>
                    <?php echo $form['pluriannuel_clause_indexation']->renderLabel() ?>
                    <?php echo $form['pluriannuel_clause_indexation']->render() ?>
                    <p style="padding: 10px 0 0 210px;"><em>Les indicateurs pouvant être pris en compte sont ceux relatifs aux coûts pertinents de production en agriculture et à l’évolution de ces coûts, ceux relatifs aux prix des produits agricoles et alimentaires constatés sur le ou les marchés où opère l’acheteur et à l’évolution de ces prix ou encore ceux relatifs aux quantités, à la composition, à la qualité, à la traçabilité des produits ou au respect d’un cahier des charges.</em></p>
                </div>
                <div class="section_label_strong">
                    <label>Prix applicable</label>
                    <span>Pour chaque campagne, les co-contractants déterminent librement pour le contrat d'application, le prix applicable, entre le prix plancher et le prix plafond.</span>
                    <p style="padding: 10px 0 0 210px;"><em>A défaut, d'accord entre les parties, celles-ci se tourneront vers la Commission d'Ethique d'Inter-Rhône pour les aider à statuer.</em></p>
                </div>
                <?php endif; ?>
                <?php if (isset($form['type_prix_1'])&&isset($form['type_prix_2'])): ?>
                <div id="vrac_type_prix" class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_type_prix|#bloc_vrac_determination_prix|#bloc_vrac_determination_prix_date">
                    <?php echo $form['type_prix_1']->renderError() ?>
                    <?php echo $form['type_prix_1']->renderLabel() ?>
                    <?php echo $form['type_prix_1']->render() ?>
                </div>
                <div id="bloc_vrac_type_prix" class="section_label_strong bloc_conditionner" data-condition-value="non_definitif">
                    <?php echo $form['type_prix_2']->renderError() ?>
                    <?php echo $form['type_prix_2']->renderLabel() ?>
                    <?php echo $form['type_prix_2']->render() ?>
                </div>
                <?php endif; ?>
                <div id="bloc_vrac_determination_prix_date" class="section_label_strong bloc_conditionner" data-condition-value="non_definitif">
                    <?php echo $form['determination_prix_date']->renderError() ?>
                        <?php echo $form['determination_prix_date']->renderLabel('Date de détermination du prix définitif*: <a href="" class="msg_aide" data-msg="help_popup_vrac_determination_prix_date" title="Message aide"></a>') ?>
                    <?php echo $form['determination_prix_date']->render(array('class' => 'datepicker')) ?>
                </div>
                <div id="bloc_vrac_determination_prix" class="section_label_strong bloc_conditionner" data-condition-value="non_definitif">
                    <?php echo $form['determination_prix']->renderError() ?>
                    <?php echo $form['determination_prix']->renderLabel() ?>
                    <?php echo $form['determination_prix']->render(array("style" => "height: 60px;")) ?>
                </div>
            <?php endif; ?>
            <h1>Paiement</h1>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_paiements|#bloc_vrac_delai">
                <?php echo $form['conditions_paiement']->renderError() ?>
                <?php echo $form['conditions_paiement']->renderLabel() ?>
                <?php if ($form->getObject()->isPluriannuel()): ?>
                <a class="msg_aide" title="Message aide" data-msg="help_popup_vrac_conditions_paiement" href="" style="margin: 0 10px 0 -30px;"></a>
                <?php endif; ?>
                <?php echo $form['conditions_paiement']->render() ?>
            </div>
            <?php if (!$form->getObject()->isPluriannuel()): ?>
            <div id="bloc_vrac_paiements" class="table_container bloc_conditionner" data-condition-value="<?php echo $form->getCgpEcheancierNeedDetermination() ?>">
            	<p>Nombre d'échéances prévues : <input type="text" name="echeances" id="echeances" /> <input id="generateur" type="button" value="générer" /></p>

                <?php
                $today = date('Y-m-d');
                $annee = (($today >= date('Y').'-10-01' && $today <= date('Y').'-12-31') || $form->getObject()->type_transaction != 'vrac')? (date('Y')+1) : date('Y');
                $limite = "$annee-09-30";
                $date1 = new DateTime();
                $date2 = new DateTime($limite);
                $nbJour = ceil($date2->diff($date1)->format("%a") / 2);
                $date1->modify("+$nbJour day");
                if ($form->getObject()->contrat_pluriannuel||$form->getObject()->isAdossePluriannuel()) {
                    $moitie = "30/06/$annee";
                    if (date('Ymd') > $annee.'0630') {
                        $moitie = date("t/m/Y");
                    }
                    $fin = "15/12/$annee";
                    if ($ref = $form->getObject()->getContratPluriannelReferent()) {
                        if ($form->getObject()->getCampagne() == $ref->pluriannuel_campagne_fin) {
                            $fin = "30/09/$annee";
                        }
                    }
                } else {
                    $moitie = $date1->format('d/m/Y');
                    $fin = $date2->format('d/m/Y');
                }
                ?>
                <p>&nbsp;</p>
                <?php if($form->getObject()->isConditionneIr()): ?>
                <p>Les accords interprofessionnels impliquent que la totalité du montant de la transaction soit réglée au plus tard le <?php echo $fin ?> et la moitié du montant, soit <span id="prix_moitie_contrat">0.0</span> € HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?>, avant le <?php echo $moitie ?></p>
                <?php elseif($form->getObject()->isConditionneCivp()): ?>
                <p>le délai devra respecter le cadre légal</p>
                <?php endif; ?>
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
                            <td colspan="3"><a id="table_paiements_add" class="btn_ajouter_ligne_template" data-container="#table_paiements tbody" data-template="#template_form_paiements_item" href="#"><span>Ajouter</span></a></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>
            <?php if ($form->isConditionneDelaiPaiement()): ?>
            <div id="bloc_vrac_delai" class="section_label_strong bloc_conditionner bloc_condition" data-condition-value="<?php echo $form->getCgpDelaiNeedDetermination() ?>" data-condition-cible="#bloc_vrac_delai_autre">
            <?php else: ?>
            <div class="section_label_strong">
            <?php endif; ?>
            <?php if(isset($form['delai_paiement'])): ?>
                <?php echo $form['delai_paiement']->renderError() ?>
                <?php echo $form['delai_paiement']->renderLabel() ?>
                <?php echo html_entity_decode($form['delai_paiement']->render()) ?>
                <?php if(isset($form['delai_paiement_autre'])): ?>
                <div id="bloc_vrac_delai_autre" class="section_label_strong bloc_conditionner" data-condition-value="autre" style="display: flex;justify-content: flex-end;width: 735px;margin-top: -40px;">
                    <div style="width:195px;">
                        <?php echo $form['delai_paiement_autre']->renderError() ?>
                        <?php echo $form['delai_paiement_autre']->renderLabel() ?>
                        <?php echo $form['delai_paiement_autre']->render(array("style" => "width:100px;")) ?><?php if ($form->getObject()->isConditionneIr()) : ?><span>&nbsp;jours</span><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($form->hasAcompteInfo()&&!$form->getObject()->isPluriannuel()&&!$form->getObject()->isAdossePluriannuel()): ?>
                    <?php if ($form->getObject()->isConditionneIr()) : ?>
                        <p style="padding: 10px 0 0 210px;"><em><strong>Acompte obligatoire de 15%</strong> dans les 10 jours suivants la signature du contrat (Article L. 665-3 du Code rural).</em></p>
                        <?php else: ?>
                        <p style="padding: 10px 0 0 210px;"><em><strong>Acompte obligatoire d'au moins 15%</strong> dans les 10 jours suivants la signature du contrat<br />Si la facture est établie par l'acheteur, le délai commence à courir à compter de la date de livraison.</em></p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if(isset($form['dispense_acompte'])): ?>
            <div class="vracs_ligne_form" style="margin: 10px 0 0 208px;">
                <?php echo $form['dispense_acompte']->renderError() ?>
                <?php echo $form['dispense_acompte']->render(array('style' => 'margin-top: 0px;vertical-align: top;')) ?>
                <?php echo $form['dispense_acompte']->renderLabel() ?>
            </div>
            <?php endif; ?>
            </div>
            <h1>Retiraison / Enlèvement</h1>
            <?php if(isset($form['type_retiraison'])): ?>
            <div class="section_label_strong">
                <?php echo $form['type_retiraison']->renderError() ?>
                <?php echo $form['type_retiraison']->renderLabel() ?>
                <?php echo $form['type_retiraison']->render() ?>
            </div>
            <?php endif; ?>
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
                <?php if(isset($form['date_limite_retiraison'])): ?>
                <div class="section_label_strong">
                    <?php echo $form['date_limite_retiraison']->renderError() ?>
                    <?php echo $form['date_limite_retiraison']->renderLabel() ?>
                    <?php echo $form['date_limite_retiraison']->render(array('class' => 'datepicker')) ?>
                    &nbsp;(jj/mm/aaaa)
                </div>
                <?php endif; ?>
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
<script type="text/javascript">
$( document ).ready(function() {
	$("#generateur").click(function() {
		var nbEcheances = parseInt($("#echeances").val());
		var prix = parseFloat($('#vrac_marche_prix_unitaire').val());
        var prixTotal = parseFloat($('#vrac_marche_prix_total_unitaire').val());
        if (!isNaN(prixTotal) && prixTotal > 0) {
        	prix = prixTotal;
        }
        var volume = parseFloat($('#vrac_marche_volume_propose').val());
        var total = 0;
        if (!isNaN(prix) && !isNaN(volume)) {
        	total = parseFloat(prix*volume).toFixed(2);
        	if (isNaN(total)) {
        		total = 0;
        	}
        }
		if(!isNaN(nbEcheances) && nbEcheances > 0) {
			var echeance;
			if (total > 0) {
				echeance = parseFloat(total/nbEcheances).toFixed(2);
			}
			$('#table_paiements tbody').html('');
			for (var i=0;i<nbEcheances;i++) {
				$("#table_paiements_add").trigger( "click" );
			}
			if (echeance) {
    			$('#table_paiements tbody input.num_float').each(function () {
    				$(this).val(echeance);
    			});
			}
		}
    });

    var volume = $("#vrac_marche_volume_propose");
    var prix_total_unitaire = $("#vrac_marche_prix_total_unitaire");
    var prix_unitaire = $("#vrac_marche_prix_unitaire");

    function updatePrixTotal()
	{
        var vol = parseFloat(volume.val());
        var prix = parseFloat(prix_total_unitaire.val());

        if(isNaN(prix)) {
        	prix = parseFloat(prix_unitaire.val());
        }

        if(isNaN(vol)) {
            vol = 0;
        }

        if(isNaN(prix)) {
            prix = 0;
        }

        var total = vol * prix;

        $("#prix_total_contrat").html(total.toFixed(2));
        if (total > 0) {
        	$("#prix_moitie_contrat").html((total/2).toFixed(2));
        }
	}

    $("#vrac_marche_volume_propose").change(function() {
        updatePrixTotal();
    });

    $("#vrac_marche_prix_unitaire").change(function() {
        updatePrixTotal();
    });

    updatePrixTotal();

    function makeInputPrixRequired() {
        document.getElementById("vrac_marche_type_prix_1_determinable").addEventListener('change', function(){
            document.getElementById("vrac_marche_pluriannuel_prix_plancher").required = this.checked ;
            document.getElementById("vrac_marche_pluriannuel_prix_plafond").required = this.checked ;

            if(document.getElementById("bloc_vrac_pluriannuel_prix")) {
                document.getElementById("vrac_marche_prix_unitaire").required = !this.checked ;
                document.getElementById("vrac_marche_prix_unitaire").required = !this.checked ;
                document.getElementById("vrac_marche_prix_unitaire").value = '';
            }


        })

        document.getElementById("vrac_marche_type_prix_1_determine").addEventListener('change', function(){
            document.getElementById("vrac_marche_pluriannuel_prix_plancher").required = !this.checked ;
            document.getElementById("vrac_marche_pluriannuel_prix_plafond").required = !this.checked ;

            if(document.getElementById("bloc_vrac_pluriannuel_prix")) {
                document.getElementById("vrac_marche_prix_unitaire").required = this.checked ;
                document.getElementById("vrac_marche_prix_unitaire").required = this.checked ;
            }
        })
    }

    makeInputPrixRequired();

});
</script>
