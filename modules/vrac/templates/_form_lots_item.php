<div class="lot bloc_form">
	<?php echo $form->renderError() ?>
    <div class="vracs_ligne_form">
        <span>
        <?php echo $form['numero']->renderError() ?>
        <?php echo $form['numero']->renderLabel() ?>
        <?php echo $form['numero']->render() ?>
        </span>
    </div>
    <div id="cuve" class="vracs_ligne_form">
        <label>Détail des cuves / contenants: </label>
        <table id="table_lot_cuves_<?php echo $form->getName() ?>">
        	<thead>
            	<tr>
            		<th>Numéro(s)</th>
            		<th>Volume (HL)</th>
            		<th>Date retiraison (jj/mm/aaaa)</th>
            	</tr>
            </thead>
            <tbody>
                <?php foreach ($form['cuves'] as $formCuve): ?>
                    <?php include_partial('form_lot_cuves_item', array('form' => $formCuve)) ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><a class="btn_ajouter_ligne_template" data-container="#table_lot_cuves_<?php echo $form->getName() ?> tbody" data-template="#template_form_lot_cuves_item_<?php echo $form->getName() ?>" href="#"><span>Ajouter une cuve / un contenant</span></a></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        
    </div>
    <div class="vracs_ligne_form bloc_condition" data-condition-cible=".millesime_<?php echo $form->getName() ?>">
        <?php echo $form['assemblage']->renderError() ?>
        <?php echo $form['assemblage']->renderLabel() ?>
        <?php echo $form['assemblage']->render() ?>
    </div>
    <div id="millesime" class="vracs_ligne_form bloc_conditionner millesime_<?php echo $form->getName() ?>" data-condition-value="1">
        <table id="table_lot_millesimes_<?php echo $form->getName() ?>">
        	<thead>
            	<tr>
            		<th>Millésime</th>
            		<th>Pourcentage (%)</th>
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
                    <td colspan="2"><a class="btn_ajouter_ligne_template" data-container="#table_lot_millesimes_<?php echo $form->getName() ?>  tbody" data-template="#template_form_lot_millesimes_item_<?php echo $form->getName() ?>" href="#"><span>Ajouter un millésime</span></a></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        
    </div>
    <?php if(isset($form['degre'])): ?>
    <div class="vracs_ligne_form">
        <span>
            <?php echo $form['degre']->renderError() ?>
            <?php echo $form['degre']->renderLabel() ?>
            <?php echo $form['degre']->render() ?>
        </span>
    </div>
    <?php endif; ?>
    <?php if(isset($form['presence_allergenes'])): ?>
    <div class="vracs_ligne_form bloc_condition" data-condition-cible="#bloc_lot_allergenes_<?php echo $form->getName() ?>">
        <span>
        <?php echo $form['presence_allergenes']->renderError() ?>
        <?php echo $form['presence_allergenes']->renderLabel() ?>
        <?php echo $form['presence_allergenes']->render() ?>
        </span>
    </div>
    <?php if(isset($form['allergenes'])): ?>
    <div id="bloc_lot_allergenes_<?php echo $form->getName() ?>" class="vracs_ligne_form bloc_conditionner" data-condition-value="1">
        <span>
        <?php echo $form['allergenes']->renderError() ?>
        <?php echo $form['allergenes']->renderLabel() ?>
        <?php echo $form['allergenes']->render() ?>
        </span>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php if(isset($form['metayage'])): ?>
    <div class="vracs_ligne_form bloc_condition" data-condition-cible="#bloc_lot_bailleur_<?php echo $form->getName() ?>">
        <?php echo $form['metayage']->renderError() ?>
        <?php echo $form['metayage']->renderLabel() ?>
        <?php echo $form['metayage']->render() ?>
    </div>
    <?php if(isset($form['bailleur'])): ?>
    <div id="bloc_lot_bailleur_<?php echo $form->getName() ?>" class="vracs_ligne_form bloc_conditionner" data-condition-value="1">
        <span>
        <?php echo $form['bailleur']->renderError() ?>
        <?php echo $form['bailleur']->renderLabel() ?>
        <?php echo $form['bailleur']->render() ?>
        </span>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
         <span>
            <a class="btn_supprimer_ligne_template" data-container=".lot" href="#">Supprimer ce lot</a>
         </span>
    </div>
</div>

