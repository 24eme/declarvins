
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
            <?php if (isset($form['type_transaction'])): ?>
            <div id="vrac_type_transaction" class="section_label_strong bloc_condition" data-condition-cible="#bloc_poids|#bloc_libelle_unite_prix_hl|#bloc_libelle_unite_prix_kg|#bloc_libelle_unite_cotis_kg|#bloc_libelle_unite_cotis_hl|#vrac_cotisation_interpro">
                <?php echo $form['type_transaction']->renderError() ?>
                <?php echo $form['type_transaction']->renderLabel() ?>
                <?php echo $form['type_transaction']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['premiere_mise_en_marche'])): ?>
        	<div class="section_label_strong">
            	<?php echo $form['premiere_mise_en_marche']->renderError() ?>
            	<?php echo $form['premiere_mise_en_marche']->renderLabel() ?>
            	<?php echo $form['premiere_mise_en_marche']->render() ?>
        	</div>
        	<?php endif; ?>
            <div class="section_label_strong">
            	<label>Produit: </label>
                <?php echo ($form->getObject()->produit)? $form->getObject()->getLibelleProduit("%g% %a% %l% %co%", true) : null; ?>
            </div>
            <?php if (isset($form['cepages'])): ?>
            <div id="section_cepages" class="section_label_strong">
                <?php echo $form['cepages']->renderError() ?>
                <?php echo $form['cepages']->renderLabel() ?>
                <?php echo $form['cepages']->render() ?>
            </div>
            <?php endif; ?>
            <div  id="section_millesime" class="section_label_strong">
                <?php echo $form['millesime']->renderError() ?>
                <?php echo $form['millesime']->renderLabel() ?>
                <?php echo ($form->getObject()->hasVersion() && $form->getObject()->volume_enleve > 0)? $form->getObject()->millesime : $form['millesime']->render(); ?>
            </div>
            <?php if (isset($form['non_millesime'])): ?>
            <div  class="section_label_strong">
                <?php echo $form['non_millesime']->renderError() ?>
                <?php echo $form['non_millesime']->renderLabel() ?>
                <?php echo $form['non_millesime']->render() ?> Non millésimé
            </div>
            <?php endif; ?>
            <div class="section_label_strong">
                <?php echo $form['labels']->renderError() ?>
                <?php echo $form['labels']->renderLabel() ?>
                <?php echo $form['labels']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['mentions']->renderError() ?>
                <?php echo $form['mentions']->renderLabel('Mentions: <a href="" class="msg_aide" data-msg="help_popup_vrac_mentions" title="Message aide"></a>') ?>
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
            <?php if (isset($form['poids'])): ?>
            <div class="section_label_strong" id="bloc_poids" class="bloc_conditionner" data-condition-value="raisin">
                <?php echo $form['poids']->renderError() ?>
                <?php echo $form['poids']->renderLabel() ?>
                <?php echo $form['poids']->render() ?> kg
            </div>
            <?php endif; ?>
            <div class="section_label_strong">
                <?php echo $form['prix_unitaire']->renderError() ?>
                <?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?> <span id="bloc_libelle_unite_prix_hl" class="bloc_conditionner" data-condition-value="vrac|mout">€ HT/hl</span><span id="bloc_libelle_unite_prix_kg" class="bloc_conditionner" data-condition-value="raisin">€ HT/kg</span><?php if ($form->getWidget('has_cotisation_cvo')->getDefault()): ?><span id="vrac_cotisation_interpro" class="bloc_conditionner" data-condition-value="vrac" data-cotisation-value="<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>">&nbsp;+&nbsp;<?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?>&nbsp;€ HT/hl de cotisation interprofessionnelle acheteur (<?php echo (ConfigurationVrac::REPARTITION_CVO_ACHETEUR)? ConfigurationVrac::REPARTITION_CVO_ACHETEUR*100 : 0; ?>%).</span><?php endif; ?>
            </div>
            <?php if (isset($form['prix_total_unitaire'])): ?>
            <div class="section_label_strong">
                <?php echo $form['prix_total_unitaire']->renderError() ?>
                <?php echo $form['prix_total_unitaire']->renderLabel() ?>
                <?php echo $form['prix_total_unitaire']->render(array('disabled' => 'disabled')) ?> <span id="bloc_libelle_unite_prix_hl" class="bloc_conditionner" data-condition-value="vrac|mout">€ HT/hl</span><span id="bloc_libelle_unite_prix_kg" class="bloc_conditionner" data-condition-value="raisin">€ HT/kg</span>
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
                <?php echo $form['determination_prix']->renderLabel('Modalité de fixation du prix définitif (celui-ci sera communiqué à l\'interprofession par les parties au contrat)') ?>
                <?php echo $form['determination_prix']->render() ?> 
            </div>
            <?php if (isset($form['mercuriale_mois']) && isset($form['mercuriale_annee'])): ?>
            <div id="bloc_vrac_mercuriale" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo implode("|", $form->getTypePrixNeedDetermination()) ?>">
            	<label>Mercuriale de référence pour la fixation du prix définitif:</label>
            	<ul class="radio_list">
            		<li style="width: 100px !important;padding:0;">
                		<?php echo $form['mercuriale_mois']->renderError() ?>
                		<?php echo $form['mercuriale_mois']->render(array('style' => 'position:relative;')) ?>
                	</li>
            		<li style="width: 100px !important;padding:0;">
                		<?php echo $form['mercuriale_annee']->renderError() ?>
                		<?php echo $form['mercuriale_annee']->render(array('style' => 'position:relative;')) ?>
                	</li>
                </ul>
            </div>
            <?php endif; ?>
            <?php if (isset($form['variation_hausse'])): ?>
            <div id="bloc_vrac_var_hausse" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo implode("|", $form->getTypePrixNeedDetermination()) ?>">
                <?php echo $form['variation_hausse']->renderError() ?>
                <?php echo $form['variation_hausse']->renderLabel('Variation max à la hausse possible par rapport à la mercuriale:') ?>
                <?php echo $form['variation_hausse']->render() ?> %
            </div>
            <?php endif; ?>
            <?php if (isset($form['variation_baisse'])): ?>
            <div id="bloc_vrac_var_baisse" class="section_label_strong bloc_conditionner" data-condition-value="<?php echo implode("|", $form->getTypePrixNeedDetermination()) ?>">
                <?php echo $form['variation_baisse']->renderError() ?>
                <?php echo $form['variation_baisse']->renderLabel('Variation max à la baisse possible par rapport à la mercuriale:') ?>
                <?php echo $form['variation_baisse']->render() ?> %
            </div>
            <?php endif; ?>
            <?php if(isset($form['annexe'])): ?>
            <div  class="section_label_strong">
                <?php echo $form['annexe']->renderError() ?>
                <?php echo $form['annexe']->renderLabel() ?>
                <?php echo $form['annexe']->render() ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'produit', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('<?php if ($form->getObject()->hasVersion()): ?>Attention, vous êtes sur le point d\'annuler les modifications en cours<?php else: ?>Attention, ce contrat sera supprimé de la base<?php endif; ?>')"><span><?php if($form->getObject()->hasVersion()): ?>Annuler les modifications<?php else: ?>supprimer le contrat<?php endif; ?></span></a>
        </div> 
    </form>

    <?php if (isset($form['non_millesime'])): ?>
    <script type="text/javascript">
    $( document ).ready(function() {
    	if ($("#<?php echo $form['non_millesime']->renderId() ?>").is(':checked')) {
			$('#section_millesime').toggle();
			$('#section_millesime input').val(null);
    	}
    	$("#<?php echo $form['non_millesime']->renderId() ?>").change(function() {
			$('#section_millesime').toggle();
			$('#section_millesime input').val(null);
        });
    });
	</script>
	<?php endif; ?>