                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['raison_sociale']->renderError() ?>
                            <?php echo $form['raison_sociale']->renderLabel() ?>
                            <?php echo $form['raison_sociale']->render(array('value' => $etablissement->raison_sociale, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['siret']->renderError() ?>
                            <?php echo $form['siret']->renderLabel() ?>
                            <?php echo $form['siret']->render(array('value' => $etablissement->siret, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['adresse']->renderError() ?>
                            <?php echo $form['adresse']->renderLabel() ?>
                            <textarea id="<?php echo $form['adresse']->renderId() ?>" readonly="readonly" class="disabled" name="<?php echo $form['adresse']->renderName() ?>" cols="30" rows="4"><?php echo $etablissement->siege->adresse ?></textarea>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['commune']->renderError() ?>
                            <?php echo $form['commune']->renderLabel() ?>
                            <?php echo $form['commune']->render(array('value' => $etablissement->siege->commune, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['code_postal']->renderError() ?>
                            <?php echo $form['code_postal']->renderLabel() ?>
                            <?php echo $form['code_postal']->render(array('value' => $etablissement->siege->code_postal, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['pays']->renderError() ?>
                            <?php echo $form['pays']->renderLabel() ?>
                            <?php echo $form['pays']->render(array('value' => $etablissement->siege->pays, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>

                </div>
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['nom']->renderError() ?>
                            <?php echo $form['nom']->renderLabel() ?>
                            <?php echo $form['nom']->render(array('value' => $etablissement->nom, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['no_carte_professionnelle']->renderError() ?>
                            <?php echo $form['no_carte_professionnelle']->renderLabel() ?>
                            <?php echo $form['no_carte_professionnelle']->render(array('value' => $etablissement->no_carte_professionnelle, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['telephone']->renderError() ?>
                            <?php echo $form['telephone']->renderLabel() ?>
                            <?php echo $form['telephone']->render(array('value' => $etablissement->telephone, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['fax']->renderError() ?>
                            <?php echo $form['fax']->renderLabel() ?>
                            <?php echo $form['fax']->render(array('value' => $etablissement->fax, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['email']->renderError() ?>
                            <?php echo $form['email']->renderLabel() ?>
                            <?php echo $form['email']->render(array('value' => $etablissement->email, 'class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>&nbsp;</span>
                    </div>
                </div>