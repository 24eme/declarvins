    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
    <div>
        <div> 
            <?php if ($form->getObject()->date_debut_retiraison || $form->getObject()->date_limite_retiraison): ?>
            <div class="clearfix">
                <h3>Rappel du volume total proposé:</h3>
                <p><?php echo $form->getObject()->volume_propose ?>&nbsp;HL</p>
                <br />
                <h3>Rappel des dates de retiraisons:</h3>
                <?php if ($form->getObject()->date_debut_retiraison): ?><p>Date de début de retiraison : <?php echo $form->getObject()->date_debut_retiraison ?></p><?php endif; ?>
                <?php if ($form->getObject()->date_limite_retiraison): ?><p>Date limite de retiraison : <?php echo $form->getObject()->date_limite_retiraison ?></p><?php endif; ?>
                <br /><br />
            </div>
            <?php endif; ?>
            <div class="clearfix">
                <div id="table_lots">
                    <?php foreach ($form['lots'] as $formLot): ?>
                        <?php include_partial('form_lots_item', array('form' => $formLot, 'form_parent' => $form)) ?>
                    <?php endforeach; ?>
                </div>
                </table>
                <div class="btn_ajouter_transaction"> 
                    <a id="btn_ajouter_ligne_template_lot" class="btn_ajouter_ligne_template" data-container="#table_lots" data-template="#template_form_lots_item" href="#"><span>Ajouter un lot</span></a>
                </div>
            </div>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a> 
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
    </div>  
</form>

<script id="template_form_lots_item" class="template_form" type="text/x-jquery-tmpl">
    <?php echo include_partial('form_lots_item', array('form' => $form->getFormTemplateLots())); ?>
</script>

<script id="template_form_lot_millesimes_item" class="template_form" type="text/x-jquery-tmpl">
    <?php echo include_partial('form_lot_millesimes_item', array('form' => $form->getFormTemplateLotMillesimes('var---nbItemLot---'))); ?>
</script>

<script id="template_form_lot_cuves_item" class="template_form" type="text/x-jquery-tmpl">
    <?php echo include_partial('form_lot_cuves_item', array('form' => $form->getFormTemplateLotCuves('var---nbItemLot---'))); ?>
</script>