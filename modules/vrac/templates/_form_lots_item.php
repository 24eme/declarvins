<div class="lot bloc_form">
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
        <?php echo $form['numero']->renderError() ?>
        <?php echo $form['numero']->renderLabel() ?>
        <?php echo $form['numero']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form">
        <span>
            <?php echo $form['cuve']->renderError() ?>
            <?php echo $form['cuve']->renderLabel() ?>
            <?php echo $form['cuve']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['volume']->renderError() ?>
            <?php echo $form['volume']->renderLabel() ?>
            <?php echo $form['volume']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form">
        <span>
        <?php echo $form['date_retiraison']->renderError() ?>
        <?php echo $form['date_retiraison']->renderLabel() ?>
        <?php echo $form['date_retiraison']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['assemblage']->renderError() ?>
            <?php echo $form['assemblage']->renderLabel() ?>
            <?php echo $form['assemblage']->render() ?>
        </span>
    </div>
    <div id="millesime" class="vracs_ligne_form ">
        <span>
            <label>Millésimes: </label>
            <table id="table_lot_millesimes_<?php echo $form->getName() ?>">
            <?php foreach ($form['millesimes'] as $formMillesime): ?>
                <?php include_partial('form_lot_millesimes_item', array('form' => $formMillesime)) ?>
            <?php endforeach; ?>
            </table>
            <a class="btn_ajouter_ligne_template" data-container="#table_lot_millesimes_<?php echo $form->getName() ?>" data-template="#template_form_lot_millesimes_item_<?php echo $form->getName() ?>" href="#"><span>Ajouter un millésime</span></a>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['degre']->renderError() ?>
            <?php echo $form['degre']->renderLabel() ?>
            <?php echo $form['degre']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form">
        <span>
        <?php echo $form['presence_allergenes']->renderError() ?>
        <?php echo $form['presence_allergenes']->renderLabel() ?>
        <?php echo $form['presence_allergenes']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
        <?php echo $form['metayage']->renderError() ?>
        <?php echo $form['metayage']->renderLabel() ?>
        <?php echo $form['metayage']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form">
        <span>
        <?php echo $form['bailleur']->renderError() ?>
        <?php echo $form['bailleur']->renderLabel() ?>
        <?php echo $form['bailleur']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
        <?php echo $form['montant']->renderError() ?>
        <?php echo $form['montant']->renderLabel() ?>
        <?php echo $form['montant']->render() ?>
        </span>
    </div>
    <div class="vracs_ligne_form">
         <span>
            <a class="btn_supprimer_ligne_template" data-container=".lot" href="#">Supprimer ce lot</a>
         </span>
    </div>
</div>

<script id="template_form_lot_millesimes_item_<?php echo $form->getName() ?>" class="template_form" type="text/x-jquery-tmpl">
    <?php echo include_partial('form_lot_millesimes_item', array('form' => $form_parent->getFormTemplateLotMillesimes($form->getName()))); ?>
</script>
