    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
        <h1>Paiement</h1>
        <div>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_paiements|#bloc_vrac_reference_contrat_pluriannuel">
                <?php echo $form['conditions_paiement']->renderError() ?>
                <?php echo $form['conditions_paiement']->renderLabel() ?>
                <?php echo $form['conditions_paiement']->render() ?>
            </div>
            <div id="bloc_vrac_paiements" class="table_container bloc_conditionner" data-condition-value="<?php echo $form->getCgpEcheancierNeedDetermination() ?>">
                <table id="table_paiements">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Volume</th>
                            <th>Montant</th>
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
                            <th colspan="2"><a class="btn_ajouter_ligne_template" data-container="#table_paiements tbody" data-template="#template_form_paiements_item" href="#"><span>Ajouter</span></a></th>
                            <th class="dernier"></th>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <?php if(isset($form['reference_contrat_pluriannuel'])): ?>
            <?php if ($form->conditionneReferenceContrat()): ?>
            <div id="bloc_vrac_reference_contrat_pluriannuel" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo $form->getCgpContratNeedDetermination() ?>">
            <?php else: ?>
            <div class="section_label_strong">
            <?php endif; ?>
                <?php echo $form['reference_contrat_pluriannuel']->renderError() ?>
                <?php echo $form['reference_contrat_pluriannuel']->renderLabel() ?>
                <?php echo $form['reference_contrat_pluriannuel']->render() ?>
            </div>
            <?php endif; ?>
            <?php if(isset($form['delai_paiement'])): ?>
            <div class="section_label_strong">
                <?php echo $form['delai_paiement']->renderError() ?>
                <?php echo $form['delai_paiement']->renderLabel() ?>
                <?php echo $form['delai_paiement']->render() ?>
            </div>
            <?php endif; ?>
        </div>
        <h1>Retiraison / Enlèvement</h1>
        <div class="section_label_strong">
            <?php echo $form['vin_livre']->renderError() ?>
            <?php echo $form['vin_livre']->renderLabel() ?>
            <?php echo $form['vin_livre']->render() ?>
        </div>
            <?php if(isset($form['date_debut_retiraison'])): ?>
            <div class="section_label_strong">
                <?php echo $form['date_debut_retiraison']->renderError() ?>
                <?php echo $form['date_debut_retiraison']->renderLabel() ?>
                <?php echo $form['date_debut_retiraison']->render() ?>
                &nbsp;(jj/mm/aaaa)
            </div>
            <?php endif; ?>
            <div class="section_label_strong">
                <?php echo $form['date_limite_retiraison']->renderError() ?>
                <?php echo $form['date_limite_retiraison']->renderLabel() ?>
                <?php echo $form['date_limite_retiraison']->render() ?>
                &nbsp;(jj/mm/aaaa)
            </div>
            <?php if(isset($form['clause_reserve_retiraison'])): ?>
            <div class="section_label_strong">
                <?php echo $form['clause_reserve_retiraison']->renderError() ?>
                <?php echo $form['clause_reserve_retiraison']->renderLabel() ?>
                <?php echo $form['clause_reserve_retiraison']->render() ?>
            </div>
            <?php endif; ?>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a> 
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
    </form>

<?php include_partial('form_collection_template', array('partial' => 'form_paiements_item',
    'form' => $form->getFormTemplatePaiements()));
?>
