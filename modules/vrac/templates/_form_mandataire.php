                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['raison_sociale']->renderError() ?>
                            <?php echo $form['raison_sociale']->renderLabel() ?>
                            <?php echo $form['raison_sociale']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['siret']->renderError() ?>
                            <?php echo $form['siret']->renderLabel() ?>
                            <?php echo $form['siret']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['adresse']->renderError() ?>
                            <?php echo $form['adresse']->renderLabel() ?>
                            <?php echo $form['adresse']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['commune']->renderError() ?>
                            <?php echo $form['commune']->renderLabel() ?>
                            <?php echo $form['commune']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['code_postal']->renderError() ?>
                            <?php echo $form['code_postal']->renderLabel() ?>
                            <?php echo $form['code_postal']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>

                </div>
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['nom']->renderError() ?>
                            <?php echo $form['nom']->renderLabel() ?>
                            <?php echo $form['nom']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['carte_pro']->renderError() ?>
                            <?php echo $form['carte_pro']->renderLabel() ?>
                            <?php echo $form['carte_pro']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['telephone']->renderError() ?>
                            <?php echo $form['telephone']->renderLabel() ?>
                            <?php echo $form['telephone']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['fax']->renderError() ?>
                            <?php echo $form['fax']->renderLabel() ?>
                            <?php echo $form['fax']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['email']->renderError() ?>
                            <?php echo $form['email']->renderLabel() ?>
                            <?php echo $form['email']->render(array('class' => 'disabled', 'readonly' => 'readonly')) ?>
                        </span>
                    </div>
                </div>