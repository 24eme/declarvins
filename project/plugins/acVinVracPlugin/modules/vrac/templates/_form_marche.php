
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

        	<h1><?php if($form->getObject()->type_transaction == 'raisin'): ?>Quantité<?php else: ?>Volume<?php endif; ?> / Prix</h1>
            <div class="section_label_strong">
                <?php echo $form['volume_propose']->renderError() ?>
                <?php echo $form['volume_propose']->renderLabel() ?>
                <?php echo $form['volume_propose']->render() ?> <strong><?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
            </div>
            <div class="section_label_strong">
                <?php echo $form['prix_unitaire']->renderError() ?>
                <?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong><?php if($form->getObject()->type_transaction == 'vrac' && $form->getObject()->premiere_mise_en_marche): ?><span id="vrac_cotisation_interpro" data-cotisation-value="<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>">&nbsp;+&nbsp;<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>&nbsp;€ HT / HL de cotisation interprofessionnelle acheteur (<?php echo (ConfigurationVrac::REPARTITION_CVO_ACHETEUR)? ConfigurationVrac::REPARTITION_CVO_ACHETEUR*100 : 0; ?>%).</span><?php endif; ?>
            </div>
            <?php if (isset($form['prix_total_unitaire'])): ?>
            <div class="section_label_strong">
                <?php echo $form['prix_total_unitaire']->renderError() ?>
                <?php echo $form['prix_total_unitaire']->renderLabel() ?>
                <?php echo $form['prix_total_unitaire']->render(array('disabled' => 'disabled')) ?> <strong>€ HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></strong>
            </div>
            <?php endif; ?>
            <div class="section_label_strong">
            	<label>Prix total HT:</label>
            	<strong><span id="prix_total_contrat">0.0</span> € HT</strong>
            </div>
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
            <h1>Paiement</h1>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_vrac_paiements|#bloc_vrac_delai">
                <?php echo $form['conditions_paiement']->renderError() ?>
                <?php echo $form['conditions_paiement']->renderLabel() ?>
                <?php echo $form['conditions_paiement']->render() ?>
            </div>
            <div id="bloc_vrac_paiements" class="table_container bloc_conditionner" data-condition-value="<?php echo $form->getCgpEcheancierNeedDetermination() ?>">
            	<p>Nombre d'échéances prévues : <input type="text" name="echeances" id="echeances" /> <input id="generateur" type="button" value="générer" /></p>

                <?php
                $today = date('Y-m-d');
                $limite = (($today >= date('Y').'-10-01' && $today <= date('Y').'-12-31') || $form->getObject()->type_transaction != 'vrac')? (date('Y')+1).'-09-30' : date('Y').'-09-30';
                $date1 = new DateTime();
                $date2 = new DateTime($limite);
                $nbJour = ceil($date2->diff($date1)->format("%a") / 2);
                $date1->modify("+$nbJour day");
                if ($form->getObject()->contrat_pluriannuel) {
                    $limite = (($today >= date('Y').'-10-01' && $today <= date('Y').'-12-31') || $form->getObject()->type_transaction != 'vrac')? '30/06/'.(date('Y')+1) : '30/06/'.date('Y');
                    $moitie = $limite;
                    $fin = $limite;
                } else {
                    $moitie = $date1->format('d/m/Y');
                    $fin = $date2->format('d/m/Y');
                }
                ?>
                <p>&nbsp;</p>
                <?php if(!$form->getObject()->isConditionneIvse()): ?>
                <p>Les accord interprofessionnels impliquent que la moitié du montant de la transaction soit réglée entre le <?php echo date('d/m/Y') ?> et le <?php echo $fin ?>  soit <span id="prix_moitie_contrat">0.0</span> € HT / <?php if($form->getObject()->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?> avant le <?php echo $moitie ?></p>
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
            <?php if ($form->isConditionneDelaiPaiement()): ?>
            <div id="bloc_vrac_delai" class="section_label_strong bloc_conditionner bloc_condition" data-condition-value="<?php echo $form->getCgpDelaiNeedDetermination() ?>" data-condition-cible="#bloc_vrac_delai_autre">
            <?php else: ?>
            <div class="section_label_strong">
            <?php endif; ?>
            <?php if(isset($form['delai_paiement'])): ?>
                <?php echo $form['delai_paiement']->renderError() ?>
                <?php echo $form['delai_paiement']->renderLabel() ?>
                <?php echo $form['delai_paiement']->render() ?>
                <?php if ($form->hasAcompteInfo()): ?>
                <p style="padding: 10px 0 0 210px;"><em><strong>Acompte obligatoire de 15%</strong> dans les 10 jours suivants la signature du contrat<br />Si la facture est établie par l'acheteur, le délai commence à courir à compter de la date de livraison.</em></p>
                <?php endif; ?>
            <?php endif; ?>
            <?php if(isset($form['delai_paiement_autre'])): ?>
            <div id="bloc_vrac_delai_autre" class="section_label_strong bloc_conditionner" data-condition-value="autre">
                <?php echo $form['delai_paiement_autre']->renderError() ?>
                <?php echo $form['delai_paiement_autre']->renderLabel() ?>
                <?php echo $form['delai_paiement_autre']->render() ?>
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


});
</script>
