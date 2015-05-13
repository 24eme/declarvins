<div class="col">
    <div class="vracs_ligne_form ">
        <span>
            <?php echo $form['raison_sociale']->renderError() ?>
            <?php echo $form['raison_sociale']->renderLabel() ?>
            <?php echo $form['raison_sociale']->render(array('value' => $etablissement->raison_sociale, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['siret']->renderError() ?>
            <?php echo $form['siret']->renderLabel() ?>
            <?php echo $form['siret']->render(array('value' => $etablissement->siret, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form ">
        <span>
            <?php echo $form['adresse']->renderError() ?>
            <?php echo $form['adresse']->renderLabel() ?>
        	<textarea id="<?php echo $form['adresse']->renderId() ?>" disabled="disabled" class="disabled" name="<?php echo $form['adresse']->renderName() ?>" cols="30" rows="4"><?php echo $etablissement->siege->adresse ?></textarea>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['commune']->renderError() ?>
            <?php echo $form['commune']->renderLabel() ?>
            <?php echo $form['commune']->render(array('value' => $etablissement->siege->commune, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form ">
        <span>
            <?php echo $form['code_postal']->renderError() ?>
            <?php echo $form['code_postal']->renderLabel() ?>
            <?php echo $form['code_postal']->render(array('value' => $etablissement->siege->code_postal, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['pays']->renderError() ?>
            <?php echo $form['pays']->renderLabel() ?>
            <?php echo $form['pays']->render(array('value' => $etablissement->siege->pays, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
</div>
<div class="col">
    <div class="vracs_ligne_form ">
        <span>
            <?php echo $form['nom']->renderError() ?>
            <?php echo $form['nom']->renderLabel() ?>
            <?php echo $form['nom']->render(array('value' => $etablissement->nom, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['cvi']->renderError() ?>
            <?php echo $form['cvi']->renderLabel() ?>
            <?php echo $form['cvi']->render(array('value' => $etablissement->cvi, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form ">
        <span>
            <?php echo $form['telephone']->renderError() ?>
            <?php echo $form['telephone']->renderLabel() ?>
            <?php echo $form['telephone']->render(array('value' => $etablissement->telephone, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>
            <?php echo $form['fax']->renderError() ?>
            <?php echo $form['fax']->renderLabel() ?>
            <?php echo $form['fax']->render(array('value' => $etablissement->fax, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form ">
        <span>
            <?php echo $form['email']->renderError() ?>
            <?php echo $form['email']->renderLabel() ?>
            <?php echo $form['email']->render(array('value' => $etablissement->email, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
        </span>
    </div>
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <span>&nbsp;</span>
    </div>
</div>
<div style="clear:both"></div>