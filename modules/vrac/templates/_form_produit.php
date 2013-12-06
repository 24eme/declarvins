
    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
            <div  id="listener_product" class="section_label_strong">
                <?php echo $form['produit']->renderError() ?>
                <?php echo $form['produit']->renderLabel() ?>
                <?php echo $form['produit']->render() ?>
            </div>
            <div  id="section_millesime" class="section_label_strong">
                <?php echo $form['millesime']->renderError() ?>
                <?php echo $form['millesime']->renderLabel() ?>
                <?php echo $form['millesime']->render() ?>
            </div>
            <div  class="section_label_strong">
                <?php echo $form['non_millesime']->renderError() ?>
                <?php echo $form['non_millesime']->renderLabel() ?>
                <?php echo $form['non_millesime']->render() ?> Non millésimé
            </div>
        </div>

        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie"><span>annuler la saisie</span></a>
        </div> 
    </form>
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
