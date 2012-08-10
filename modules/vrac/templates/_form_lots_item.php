<hr />
<div class="lot">
    <div class="section_label_strong">
        <?php echo $form['numero']->renderError() ?>
        <?php echo $form['numero']->renderLabel() ?>
        <?php echo $form['numero']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['volume']->renderError() ?>
        <?php echo $form['volume']->renderLabel() ?>
        <?php echo $form['volume']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['cuve']->renderError() ?>
        <?php echo $form['cuve']->renderLabel() ?>
        <?php echo $form['cuve']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['assemblage']->renderError() ?>
        <?php echo $form['assemblage']->renderLabel() ?>
        <?php echo $form['assemblage']->render() ?>
    </div>
    <div class="section_label_strong">
    	<label>Millésimes: </label>
	    <?php foreach ($form['millesimes'] as $formMillesime): ?>
		<table id="table_lot_millesimes_<?php echo $form->getName() ?>">
			<?php include_partial('form_lot_millesimes_item', array('form' => $formMillesime)) ?>
		</table>
		<?php endforeach; ?>
		<a class="btn_ajouter_ligne_template_sub" data-container="#table_lot_millesimes_<?php echo $form->getName() ?>" data-template="#template_form_lot_millesimes_item_<?php echo $form->getName() ?>" href="#"><span>Ajouter un millésime</span></a>
	</div>
    <div class="section_label_strong">
        <?php echo $form['degre']->renderError() ?>
        <?php echo $form['degre']->renderLabel() ?>
        <?php echo $form['degre']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['presence_allergenes']->renderError() ?>
        <?php echo $form['presence_allergenes']->renderLabel() ?>
        <?php echo $form['presence_allergenes']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['metayage']->renderError() ?>
        <?php echo $form['metayage']->renderLabel() ?>
        <?php echo $form['metayage']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['bailleur']->renderError() ?>
        <?php echo $form['bailleur']->renderLabel() ?>
        <?php echo $form['bailleur']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['date_retiraison']->renderError() ?>
        <?php echo $form['date_retiraison']->renderLabel() ?>
        <?php echo $form['date_retiraison']->render() ?>
    </div>
    <div class="section_label_strong">
        <?php echo $form['montant']->renderError() ?>
        <?php echo $form['montant']->renderLabel() ?>
        <?php echo $form['montant']->render() ?>
    </div>
    <div class="section_label_strong">
        <a class="btn_supprimer_ligne_template" href="#">X</a>
    </div>
</div>

<script id="template_form_lot_millesimes_item_<?php echo $form->getName() ?>" class="template_form" type="text/x-jquery-tmpl">
    <?php echo include_partial('form_lot_millesimes_item' , array('form' => $form_parent->getFormTemplateLotMillesimes($form->getName()))); ?>
</script>
<hr />