<div class="col">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>
    <div class="ligne_form">
        <?php echo $form['raison_sociale']->renderError() ?>
        <?php echo $form['raison_sociale']->renderLabel() ?>
        <?php echo $form['raison_sociale']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['nom']->renderError() ?>
        <?php echo $form['nom']->renderLabel() ?>
        <?php echo $form['nom']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['siret']->renderError() ?>
        <?php echo $form['siret']->renderLabel() ?>
        <?php echo $form['siret']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['cni']->renderError() ?>
        <?php echo $form['cni']->renderLabel() ?>
        <?php echo $form['cni']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['cvi']->renderError() ?>
        <?php echo $form['cvi']->renderLabel() ?>
        <?php echo $form['cvi']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['no_accises']->renderError() ?>
        <?php echo $form['no_accises']->renderLabel() ?>
        <?php echo $form['no_accises']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['no_tva_intracommunautaire']->renderError() ?>
        <?php echo $form['no_tva_intracommunautaire']->renderLabel() ?>
        <?php echo $form['no_tva_intracommunautaire']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['adresse']->renderError() ?>
        <?php echo $form['adresse']->renderLabel() ?>
        <?php echo $form['adresse']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['code_postal']->renderError() ?>
        <?php echo $form['code_postal']->renderLabel() ?>
        <?php echo $form['code_postal']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['commune']->renderError() ?>
        <?php echo $form['commune']->renderLabel() ?>
        <?php echo $form['commune']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['pays']->renderError() ?>
        <?php echo $form['pays']->renderLabel() ?>
        <?php echo $form['pays']->render() ?>
    </div>
</div>

<div class="col">
    <div class="ligne_form">
        <?php echo $form['telephone']->renderError() ?>
        <?php echo $form['telephone']->renderLabel() ?>
        <?php echo $form['telephone']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['fax']->renderError() ?>
        <?php echo $form['fax']->renderLabel() ?>
        <?php echo $form['fax']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['email']->renderError() ?>
        <?php echo $form['email']->renderLabel() ?>
        <?php echo $form['email']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['famille']->renderError() ?>
        <?php echo $form['famille']->renderLabel() ?>
        <?php echo $form['famille']->render() ?>
    </div>
    <div class="ligne_form">
        <?php echo $form['sous_famille']->renderError() ?>
        <?php echo $form['sous_famille']->renderLabel() ?>
        <?php echo $form['sous_famille']->render() ?>
        <script id="template_options_sous_famille" type="text/x-jquery-tmpl">
            <option value="${key}" {{if selected}}selected="selected"{{/if}} >${value}</option>
        </script>
    </div>
    <div class="ligne_form">
        <?php echo $form['service_douane']->renderError() ?>
        <?php echo $form['service_douane']->renderLabel() ?>
        <?php echo $form['service_douane']->render() ?>
    </div>
    <div class="ligne_form">

        <?php echo $form['edi']->renderError() ?>
        <?php echo $form['edi']->renderLabel() ?>
        <a class="msg_aide" title="Message aide" data-msg="help_popup_mandat_edi" href=""></a>
        <div class="champ_form champ_form_radio_cb" style="display: inline-block">
            <?php echo $form['edi']->render() ?>
        </div>
    </div>
    <div class="ligne_form ligne_form_large">
        <label for="champ_16-1">L'adresse de votre comptabilité est-elle différente de la précédente ?</label>
        <div class="champ_form champ_form_radio_cb">
            <input type="radio" value="Oui" name="adresse_comptabilite" id="champ_16-1">
            <label for="champ_16-1">Oui</label>
            <input type="radio" checked="checked" value="Non" name="adresse_comptabilite" id="champ_16-2">
            <label for="champ_16-2">Non</label>
        </div>
    </div>

    <div id="adresse_comptabilite">
        <div class="ligne_form">
            <?php echo $form['comptabilite_adresse']->renderError() ?>
            <?php echo $form['comptabilite_adresse']->renderLabel() ?>
            <?php echo $form['comptabilite_adresse']->render() ?>
        </div>
        <div class="ligne_form">
            <?php echo $form['comptabilite_code_postal']->renderError() ?>
            <?php echo $form['comptabilite_code_postal']->renderLabel() ?>
            <?php echo $form['comptabilite_code_postal']->render() ?>
        </div>
        <div class="ligne_form">
            <?php echo $form['comptabilite_commune']->renderError() ?>
            <?php echo $form['comptabilite_commune']->renderLabel() ?>
            <?php echo $form['comptabilite_commune']->render() ?>
        </div>
        <div class="ligne_form">
            <?php echo $form['comptabilite_pays']->renderError() ?>
            <?php echo $form['comptabilite_pays']->renderLabel() ?>
            <?php echo $form['comptabilite_pays']->render() ?>
        </div>
    </div>
</div>

<div class="ligne_btn">
    <button type="submit" class="btn_suiv"><span><?php echo ($recapitulatif) ? 'Modifier' : 'Valider'; ?></span></button>
    <?php if (!$new): ?>
        <button type="button" class="btn_supprimer" onclick="window.location.href='<?php echo ($recapitulatif) ? url_for('contrat_etablissement_suppression', array('indice' => $form->getObject()->getKey(), 'recapitulatif' => 1)) : url_for('contrat_etablissement_suppression', array('indice' => $form->getObject()->getKey())); ?>'">Supprimer cet établissement</button>
    <?php else: ?>
        <button type="button" class="btn_supprimer" onclick="window.location.href='<?php echo url_for("contrat_etablissement_recapitulatif"); ?>'">Annuler</button>
    <?php endif; ?>
</div>