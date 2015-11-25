<div class="bloc_adresse">
    <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_<?php echo $field ?>_form">
        <label for="cb_adresse_differente_<?php echo $field ?>">
        	<input type="checkbox" value="differente" id="cb_adresse_differente_<?php echo $field ?>" <?php if($form[$field]['siret']->getValue() || $form[$field]['adresse']->getValue() || $form[$field]['libelle']->getValue() || $form[$field]['code_postal']->getValue() || $form[$field]['commune']->getValue() || $form[$field]['pays']->getValue()): echo 'checked="checked"'; endif; ?> /> 
            <?php echo $label_adresse ?>
        </label>
    </div>
    <div id="bloc_<?php echo $field ?>_form" class="bloc_form bloc_conditionner" data-condition-value="differente"> 
		<?php if ($field == 'adresse_stockage'): ?>
        		<div class="vigilance_list" style="margin-top: 10px;">Attention, tout lieu de stockage doit être préalablement déclaré à votre Organisme de Contrôle</div>
		<?php endif; ?>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <?php echo $form[$field]['siret']->renderError() ?>
                <?php echo $form[$field]['siret']->renderLabel() ?>
                <?php echo $form[$field]['siret']->render() ?>
            </span>
        </div>
        <div class="vracs_ligne_form">
            <span>
                <?php echo $form[$field]['libelle']->renderError() ?>
                <?php echo $form[$field]['libelle']->renderLabel() ?>
                <?php echo $form[$field]['libelle']->render() ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <?php echo $form[$field]['adresse']->renderError() ?>
                <?php echo $form[$field]['adresse']->renderLabel() ?>
                <?php echo $form[$field]['adresse']->render() ?>
            </span>
        </div>
        <div class="vracs_ligne_form ">
            <span>
                <?php echo $form[$field]['code_postal']->renderError() ?>
                <?php echo $form[$field]['code_postal']->renderLabel() ?>
                <?php echo $form[$field]['code_postal']->render() ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <?php echo $form[$field]['commune']->renderError() ?>
                <?php echo $form[$field]['commune']->renderLabel() ?>
                <?php echo $form[$field]['commune']->render() ?>
            </span>
        </div>
        <div class="vracs_ligne_form ">
            <span>
                <?php echo $form[$field]['pays']->renderError() ?>
                <?php echo $form[$field]['pays']->renderLabel() ?>
                <?php echo $form[$field]['pays']->render() ?>
            </span>
        </div>
    </div>
</div>