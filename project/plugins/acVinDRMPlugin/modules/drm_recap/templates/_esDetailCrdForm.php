<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_recap_es_detail', $detail) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<table id="table_es_detail_crd">
    	<thead>
        	<tr class="detail">
        		<th align="left">Volume (hl)</th>
        		<th align="left">Période (mois/année)</th>
                <th class="dernier"></th>
        	</tr>
        </thead>
        <tbody>
        <?php foreach ($form['details'] as $formDetail): ?>
            <tr class="detail">
            	<td>
            		<span class="error"><?php echo $formDetail['volume']->renderError() ?></span>
            		<?php echo $formDetail['volume']->render() ?>
            	</td>
            	<td>
            		<span class="error"><?php echo $formDetail['mois']->renderError() ?></span>
            		<span class="error"><?php echo $formDetail['annee']->renderError() ?></span>
            		<?php echo $formDetail['mois']->render() ?> / <?php echo $formDetail['annee']->render() ?>
            	</td>
            	<td>
            		 <a class="btn_supprimer_ligne_template" data-container=".detail" href="#" style="width: 14px; display: block; background: url(/images/pictos/pi_supprimer.png) 0 center no-repeat;">&nbsp;</a>
            	</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><a class="btn_ajouter_ligne_template" data-container="#table_es_detail_crd tbody" data-template="#template_form_details_item" data-template-params='<?php echo json_encode(array('var---nbItemLot---' => $form->getName())) ?>' href="#"><span>Ajouter un replacement</span></a></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>

<script id="template_form_details_item" class="template_form" type="text/x-jquery-tmpl">
    <?php $f = $form->getFormTemplateDetails(); ?>
    <tr class="detail">
            	<td>
            		<span class="error"><?php echo $f['volume']->renderError() ?></span>
            		<?php echo $f['volume']->render() ?>
            	</td>
            	<td>
            		<span class="error"><?php echo $f['mois']->renderError() ?></span>
            		<span class="error"><?php echo $f['annee']->renderError() ?></span>
            		<?php echo $f['mois']->render() ?> / <?php echo $f['annee']->render() ?>
            	</td>
            	<td>
            		 <a class="btn_supprimer_ligne_template" data-container=".detail" href="#" style="width: 14px; display: block; background: url(/images/pictos/pi_supprimer.png) 0 center no-repeat;">&nbsp;</a>
            	</td>
            </tr>
</script>
