<div style="background: none repeat scroll 0 0 #ECFEEA;border: 1px solid #359B02;color: #1E5204;font-weight: bold;text-align: center;margin: 0 0 20px 0;padding: 5px 10px;">
	<ul>
		<li>
			Votre déclaration de transaction est désormais envoyée automatiquement à votre OIOC par e-mail (vous serez copie).<br />Si vous n’êtes pas en copie de ce mail, ou si vous ne recevez pas d'accusé de réception de votre OIOC dans les 24 heures pour AVPI, 48 heures ouvrées pour QUALISUD, vous devrez impérativement prendre contact avec votre OIOC afin de transmettre vous-même votre déclaration de transaction (« PDF Transaction »).<br />Votre interprofession ne pourra être tenu responsable de la non réception de votre déclaration de transaction par votre OIOC.
		</li>
	</ul>
</div>
<form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
    <div>
        <div> 
            <?php if ($form->getObject()->date_debut_retiraison || $form->getObject()->date_limite_retiraison): ?>
            <div class="clearfix">
                <h3>Rappel du volume total proposé:</h3>
                <p><?php echo $form->getObject()->volume_propose ?>&nbsp;hl</p>
                <br />
                <h3>Rappel des dates de retiraisons:</h3>
                <?php if ($form->getObject()->date_debut_retiraison): ?><p>Date de début de retiraison : <?php echo Date::francizeDate($form->getObject()->date_debut_retiraison) ?></p><?php endif; ?>
                <?php if ($form->getObject()->date_limite_retiraison): ?><p>Date limite de retiraison : <?php echo Date::francizeDate($form->getObject()->date_limite_retiraison) ?></p><?php endif; ?>
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
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('Attention, ce contrat sera supprimé de la base')"><span>supprimer le contrat</span></a>
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