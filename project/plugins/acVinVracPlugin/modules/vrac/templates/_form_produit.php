
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
            <div  id="listener_product" class="section_label_strong">
                <?php echo $form['produit']->renderError() ?>
                <?php echo $form['produit']->renderLabel() ?>
                <?php echo ($form->getObject()->hasVersion() && $form->getObject()->volume_enleve > 0)? $form->getObject()->produit_libelle : $form['produit']->render(); ?>
            </div>
            <?php if (isset($form['millesime'])): ?>
            <div  id="section_millesime" class="section_label_strong">
                <?php echo $form['millesime']->renderError() ?>
                <?php if(isset($form['non_millesime'])): ?>
                <?php echo $form['non_millesime']->renderError() ?>
                <?php endif; ?>
                <?php echo $form['millesime']->renderLabel() ?>
                <?php echo $form['millesime']->render(); ?>
                <?php if(isset($form['non_millesime'])): ?>
                <?php echo $form['non_millesime']->render(array('style' => 'margin-top: 0px;vertical-align: middle;')) ?>&nbsp;<label for="vrac_produit_non_millesime" style="font-weight: normal;width:auto;float:none;">Non millésimé</label>
                <?php endif; ?>
            </div>
        <?php endif; ?>
            <div id="vrac_labels" class="section_label_strong bloc_condition" data-condition-cible="#bloc_labels_libelle_autre">
                <?php echo $form['labels_arr']->renderError() ?>
                <?php echo $form['labels_arr']->renderLabel() ?>
                <?php echo $form['labels_arr']->render() ?>
            </div>
            <div class="section_label_strong bloc_conditionner" id="bloc_labels_libelle_autre" data-condition-value="autre">
                <?php echo $form['labels_libelle_autre']->renderError() ?>
                <?php echo $form['labels_libelle_autre']->renderLabel() ?>
                <?php echo $form['labels_libelle_autre']->render() ?>
            </div>
            <div id="vrac_mentions" class="section_label_strong bloc_condition" data-condition-cible="#bloc_mentions_libelle_autre|#bloc_mentions_libelle_chdo|#bloc_mentions_libelle_marque">
                <?php echo $form['mentions']->renderError() ?>
                <?php echo $form['mentions']->renderLabel('Mentions: <a href="" class="msg_aide" data-msg="help_popup_vrac_mentions" title="Message aide"></a>') ?>
                <?php echo $form['mentions']->render() ?>
            </div>
            <div class="section_label_strong bloc_conditionner" id="bloc_mentions_libelle_chdo" data-condition-value="chdo">
                <?php echo $form['mentions_libelle_chdo']->renderError() ?>
                <?php echo $form['mentions_libelle_chdo']->renderLabel() ?>
                <?php echo $form['mentions_libelle_chdo']->render() ?>
                <p style="padding-left: 212px;"><em>Le vendeur autorise expressément l'Acheteur à utiliser son nom d'exploitation. Ce dernier devra être indiqué sur la facture et le document d'accompagnement. L'Acheteur devra respecter les exigences du décret n° 2012-655 du 4 mai 2012.</em></p>
            </div>
            <div class="section_label_strong bloc_conditionner" id="bloc_mentions_libelle_autre" data-condition-value="autre">
                <?php echo $form['mentions_libelle_autre']->renderError() ?>
                <?php echo $form['mentions_libelle_autre']->renderLabel() ?>
                <?php echo $form['mentions_libelle_autre']->render() ?>
            </div>
            <div class="section_label_strong bloc_conditionner" id="bloc_mentions_libelle_marque" data-condition-value="marque">
                <?php echo $form['mentions_libelle_marque']->renderError() ?>
                <?php echo $form['mentions_libelle_marque']->renderLabel() ?>
                <?php echo $form['mentions_libelle_marque']->render() ?>
                <p style="padding-left: 212px;"><em>Le vendeur autorise expressément l'Acheteur à utiliser sa marque.</em></p>
            </div>
        </div>

        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
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
    		$('#section_millesime input:text').attr("disabled", "disabled");
			$('#section_millesime input:text').val(null);
    	}
    	$("#<?php echo $form['non_millesime']->renderId() ?>").change(function() {
			$('#section_millesime input').val(null);
    		if ($("#<?php echo $form['non_millesime']->renderId() ?>").is(':checked')) {
    			$('#section_millesime input:text').attr("disabled", "disabled");
    		} else {
    			$('#section_millesime input:text').removeAttr("disabled");
    		}
        });
    });
	</script>
	<?php endif; ?>