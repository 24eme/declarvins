<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_recap_es_detail', $detail) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['volume']->renderError() ?></span>
		<?php echo $form['volume']->renderLabel() ?>
		<?php echo $form['volume']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['mois']->renderError() ?></span>
		<?php echo $form['mois']->renderLabel() ?>
		<?php echo $form['mois']->render() ?>
		
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['annee']->renderError() ?></span>
		<?php echo $form['annee']->renderLabel() ?>
		<?php echo $form['annee']->render() ?>
		
	</div>
	<table id="table_es_detail_crd">
    	<thead>
        	<tr>
        		<th>Volume</th>
        		<th>Période</th>
                <th class="dernier"></th>
        	</tr>
        </thead>
        <tbody>
        <?php foreach ($form['millesimes'] as $formMillesime): ?>
            <?php include_partial('form_lot_millesimes_item', array('form' => $formMillesime)) ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><a class="btn_ajouter_ligne_template" data-container="#table_es_detail_crd  tbody" data-template="#template_form_lot_millesimes_item" data-template-params='<?php echo json_encode(array('var---nbItemLot---' => $form->getName())) ?>' href="#"><span>Ajouter un millésime</span></a></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
