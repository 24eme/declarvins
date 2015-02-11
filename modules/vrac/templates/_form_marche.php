
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
            <div class="section_label_strong">
            	<label>Produit: </label>
            	<?php $form->getObject()->getProduitInterpro() ?>
                <?php echo ($form->getObject()->produit)? $form->getObject()->getLibelleProduit() : null; ?> <?php echo ($form->getObject()->millesime)? $form->getObject()->millesime : 'Non millésimé'; ?>
            </div>
            <div  id="section_millesime" class="section_label_strong">
                <?php echo $form['millesime']->renderError() ?>
                <?php echo $form['millesime']->renderLabel() ?>
                <?php echo $form['millesime']->render() ?>
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
            <div class="section_label_strong">
                <?php echo $form['prix_unitaire']->renderError() ?>
                <?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?> € HT/hl<?php if ($form->getWidget('has_cotisation_cvo')->getDefault()): ?>&nbsp;+&nbsp;<span id="vrac_cotisation_interpro"><?php echo ($form->getObject()->getPartCvo())? round($form->getObject()->getPartCvo() * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2) : 0;?></span>&nbsp;€ HT/hl de cotisation interprofessionnelle acheteur (<?php echo (ConfigurationVrac::REPARTITION_CVO_ACHETEUR)? ConfigurationVrac::REPARTITION_CVO_ACHETEUR*100 : 0; ?>%).<?php endif; ?>
            </div>
            <?php if (isset($form['prix_total_unitaire'])): ?>
            <div class="section_label_strong">
                <?php echo $form['prix_total_unitaire']->renderError() ?>
                <?php echo $form['prix_total_unitaire']->renderLabel() ?>
                <?php echo $form['prix_total_unitaire']->render(array('disabled' => 'disabled')) ?> € HT/hl
            </div>
            <?php endif; ?>
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
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'produit', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('Attention, ce contrat sera supprimé de la base')"><span>supprimer le contrat</span></a>
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