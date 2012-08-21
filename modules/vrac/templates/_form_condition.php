
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
        <h1>Paiement</h1>
        <div>
            <div class="section_label_strong">
                <?php echo $form['conditions_paiement']->renderError() ?>
                <?php echo $form['conditions_paiement']->renderLabel() ?>
                <?php echo $form['conditions_paiement']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['contrat_pluriannuel']->renderError() ?>
                <?php echo $form['contrat_pluriannuel']->renderLabel() ?>
                <?php echo $form['contrat_pluriannuel']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['reference_contrat_pluriannuel']->renderError() ?>
                <?php echo $form['reference_contrat_pluriannuel']->renderLabel() ?>
                <?php echo $form['reference_contrat_pluriannuel']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['delai_paiement']->renderError() ?>
                <?php echo $form['delai_paiement']->renderLabel() ?>
                <?php echo $form['delai_paiement']->render() ?>
            </div>
            <div class="section_label_strong bloc_condition" data-condition-cible="#vrac_paiements">
                <?php echo $form['echeancier_paiement']->renderError() ?>
                <?php echo $form['echeancier_paiement']->renderLabel() ?>
                <?php if($form->isEcheanchierPaiementOptionnel()): ?>
                    <?php echo $form['echeancier_paiement']->render() ?>
                <?php endif; ?>
            </div>
            <div id="vrac_paiements" class="table_container vrac_paiements" data-condition-value="1">
                <table id="table_paiements">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                            <th class="dernier"></th>
                        </tr>
                    </thead>
                    </tr>
                    <?php foreach ($form['paiements'] as $formPaiement): ?>
                        <?php include_partial('form_paiements_item', array('form' => $formPaiement)) ?>
                    <?php endforeach; ?>
                    <tfoot>
                        <tr>
                            <th colspan="2"><a class="btn_ajouter_ligne_template" data-container="#table_paiements" data-template="#template_form_paiements_item" href="#"><span>Ajouter</span></a></th>
                            <th class="dernier"></th>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
        </div>
        <h1>Livraison</h1>
        <div id="paiement">
            <div class="section_label_strong">
                <?php echo $form['vin_livre']->renderError() ?>
                <?php echo $form['vin_livre']->renderLabel() ?>
                <?php echo $form['vin_livre']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['date_limite_retiraison']->renderError() ?>
                <?php echo $form['date_limite_retiraison']->renderLabel() ?>
                <?php echo $form['date_limite_retiraison']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['clause_reserve_retiraison']->renderError() ?>
                <?php echo $form['clause_reserve_retiraison']->renderLabel() ?>
                <?php echo $form['clause_reserve_retiraison']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['commentaires_conditions']->renderError() ?>
                <?php echo $form['commentaires_conditions']->renderLabel() ?>
                <?php echo $form['commentaires_conditions']->render() ?>
            </div>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a> 
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
    </form>

<?php include_partial('form_collection_template', array('partial' => 'form_paiements_item',
    'form' => $form->getFormTemplatePaiements()));
?>
