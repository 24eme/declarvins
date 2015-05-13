<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
                <div id="titre">
                    <span class="style_label">N° de Visa du contrat : <?php echo ($vrac->isValide())? $vrac->numero_contrat : 'En attente'; ?></span>
                </div>
            </div>
            <form class="popup_form" action="" method="post" style="padding-top: 20px;">
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                    <div>
                        <div id="contrat">
                            <div class="section_label_strong">
                                <?php echo $form['premiere_mise_en_marche']->renderError() ?>
                                <?php echo $form['premiere_mise_en_marche']->renderLabel() ?>
                                <?php echo $form['premiere_mise_en_marche']->render() ?>
                            </div>
                            <div class="section_label_strong_bloc">
                                <?php echo $form['cas_particulier']->renderError() ?>
                                <?php echo $form['cas_particulier']->renderLabel() ?>
                                <?php echo $form['cas_particulier']->render() ?>
                            </div>
                        </div>
                        <div class="section_label_strong">
                            <?php echo $form['volume_propose']->renderError() ?>
                            <?php echo $form['volume_propose']->renderLabel() ?>
                            <?php echo $form['volume_propose']->render() ?> hl
                        </div>
                        <div class="section_label_strong">
                            <label>Volume enlevé :</label>
                            <?php echo $vrac->volume_enleve ?> hl
                        </div>
                        <div class="section_label_strong">
                            <?php echo $form['date_signature']->renderError() ?>
                            <?php echo $form['date_signature']->renderLabel() ?>
                            <?php echo $form['date_signature']->render(array('class' => 'datepicker')) ?> (jj/mm/aaaa)
                        </div>
                    </div>
                    <div class="ligne_form_btn">
                        <a href="<?php echo url_for('vrac_visualisation', array('sf_subject' => $vrac, 'etablissement' => $etablissement)); ?>" class="etape_prec"><span>Annuler</span></a>
                        <button class="valider_etape" type="submit"><span>Modifier</span></button>
                    </div>
            </form>
        </section>
    </div>
</div>