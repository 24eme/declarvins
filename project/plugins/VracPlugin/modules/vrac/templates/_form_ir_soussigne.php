<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs vrac_soussigne">
    <?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>

    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div class="contenu_onglet" data-cible="vrac_vendeur_acheteur">
            <label for="">Vous êtes: </label>
            <ul class="radio_list">
                <li>
                    <input name="vrac_acheteur_vendeur" type="radio" id="vrac_vendeur">&nbsp;<label for="vrac_vendeur">vendeur</label>
                </li>
                <li>
                    <input name="vrac_acheteur_vendeur" type="radio" id="vrac_acheteur">&nbsp;<label for="vrac_acheteur">acheteur</label>
                </li>
            </ul>
        </div>
        <div id="vendeur" class="vrac_vendeur_acheteur">
            <h1>Vendeur</h1>
            <h2>Sélectionner un vendeur :</h2>
            <div id="type" class="section_label_strong">
                <?php echo $form['vendeur_type']->renderLabel() ?>
                <?php echo $form['vendeur_type']->renderError() ?>
                <?php echo $form['vendeur_type']->render(array('class' => 'famille')) ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['vendeur_identifiant']->renderError() ?>
                <label for="">Nom :</label>
                <?php echo $form['vendeur_identifiant']->render() ?>
            </div>
            <div  class="bloc_form"> 
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['vendeur']['raison_sociale']->renderError() ?>
                            <?php echo $form['vendeur']['raison_sociale']->renderLabel() ?>
                            <?php echo $form['vendeur']['raison_sociale']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['vendeur']['siret']->renderError() ?>
                            <?php echo $form['vendeur']['siret']->renderLabel() ?>
                            <?php echo $form['vendeur']['siret']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['vendeur']['adresse']->renderError() ?>
                            <?php echo $form['vendeur']['adresse']->renderLabel() ?>
                            <?php echo $form['vendeur']['adresse']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['vendeur']['commune']->renderError() ?>
                            <?php echo $form['vendeur']['commune']->renderLabel() ?>
                            <?php echo $form['vendeur']['commune']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['vendeur']['code_postal']->renderError() ?>
                            <?php echo $form['vendeur']['code_postal']->renderLabel() ?>
                            <?php echo $form['vendeur']['code_postal']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['vendeur']['telephone']->renderError() ?>
                            <?php echo $form['vendeur']['telephone']->renderLabel() ?>
                            <?php echo $form['vendeur']['telephone']->render() ?>
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['vendeur']['nom']->renderError() ?>
                            <?php echo $form['vendeur']['nom']->renderLabel() ?>
                            <?php echo $form['vendeur']['nom']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['vendeur']['cvi']->renderError() ?>
                            <?php echo $form['vendeur']['cvi']->renderLabel() ?>
                            <?php echo $form['vendeur']['cvi']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['vendeur']['telephone']->renderError() ?>
                            <?php echo $form['vendeur']['telephone']->renderLabel() ?>
                            <?php echo $form['vendeur']['telephone']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['vendeur']['fax']->renderError() ?>
                            <?php echo $form['vendeur']['fax']->renderLabel() ?>
                            <?php echo $form['vendeur']['fax']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['vendeur']['email']->renderError() ?>
                            <?php echo $form['vendeur']['email']->renderLabel() ?>
                            <?php echo $form['vendeur']['email']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['vendeur_assujetti_tva']->renderError() ?>
                            <?php echo $form['vendeur_assujetti_tva']->renderLabel() ?>
                            <?php echo $form['vendeur_assujetti_tva']->render() ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="adresse_livraison">
                <div id="type" class="section_label_strong"><label for="dif_adr_stock">Précision de l'adresse de stockage (si différente) <input type="checkbox" name="dif_adr_stock" id="dif_adr_stock"></label></div>
                <div class="bloc_form"> 
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['adresse_stockage']['adresse']->renderError() ?>
                            <?php echo $form['adresse_stockage']['adresse']->renderLabel() ?>
                            <?php echo $form['adresse_stockage']['adresse']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['adresse_stockage']['code_postal']->renderError() ?>
                            <?php echo $form['adresse_stockage']['code_postal']->renderLabel() ?>
                            <?php echo $form['adresse_stockage']['code_postal']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['adresse_stockage']['commune']->renderError() ?>
                            <?php echo $form['adresse_stockage']['commune']->renderLabel() ?>
                            <?php echo $form['adresse_stockage']['commune']->render() ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div id="acheteur" class="vrac_vendeur_acheteur">
            <h1>Acheteur</h1>
            <h2>Sélectionner un acheteur :</h2>

            <div id="type" class="section_label_strong">
                <?php echo $form['acheteur_type']->renderLabel() ?>
                <?php echo $form['acheteur_type']->renderError() ?>
                <?php echo $form['acheteur_type']->render(array('class' => 'famille')) ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['acheteur_identifiant']->renderError() ?>
                <label for="">Nom :</label>
                <?php echo $form['acheteur_identifiant']->render() ?>
            </div>

            <div  class="bloc_form"> 
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['acheteur']['raison_sociale']->renderError() ?>
                            <?php echo $form['acheteur']['raison_sociale']->renderLabel() ?>
                            <?php echo $form['acheteur']['raison_sociale']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['acheteur']['siret']->renderError() ?>
                            <?php echo $form['acheteur']['siret']->renderLabel() ?>
                            <?php echo $form['acheteur']['siret']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['acheteur']['adresse']->renderError() ?>
                            <?php echo $form['acheteur']['adresse']->renderLabel() ?>
                            <?php echo $form['acheteur']['adresse']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['acheteur']['commune']->renderError() ?>
                            <?php echo $form['acheteur']['commune']->renderLabel() ?>
                            <?php echo $form['acheteur']['commune']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['acheteur']['code_postal']->renderError() ?>
                            <?php echo $form['acheteur']['code_postal']->renderLabel() ?>
                            <?php echo $form['acheteur']['code_postal']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['acheteur']['telephone']->renderError() ?>
                            <?php echo $form['acheteur']['telephone']->renderLabel() ?>
                            <?php echo $form['acheteur']['telephone']->render() ?>
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['acheteur']['nom']->renderError() ?>
                            <?php echo $form['acheteur']['nom']->renderLabel() ?>
                            <?php echo $form['acheteur']['nom']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['acheteur']['cvi']->renderError() ?>
                            <?php echo $form['acheteur']['cvi']->renderLabel() ?>
                            <?php echo $form['acheteur']['cvi']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['acheteur']['telephone']->renderError() ?>
                            <?php echo $form['acheteur']['telephone']->renderLabel() ?>
                            <?php echo $form['acheteur']['telephone']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['acheteur']['fax']->renderError() ?>
                            <?php echo $form['acheteur']['fax']->renderLabel() ?>
                            <?php echo $form['acheteur']['fax']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['acheteur']['email']->renderError() ?>
                            <?php echo $form['acheteur']['email']->renderLabel() ?>
                            <?php echo $form['acheteur']['email']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['acheteur_assujetti_tva']->renderError() ?>
                            <?php echo $form['acheteur_assujetti_tva']->renderLabel() ?>
                            <?php echo $form['acheteur_assujetti_tva']->render() ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="adresse_livraison">
                <div id="type" class="section_label_strong">
                    <label for="dif_adr_livr">Précision de l'adresse de livraison (si différente) <input type="checkbox" name="dif_adr_livr" id="dif_adr_livr"></label>
                </div>
                <div class="bloc_form"> 
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['adresse_livraison']['adresse']->renderError() ?>
                            <?php echo $form['adresse_livraison']['adresse']->renderLabel() ?>
                            <?php echo $form['adresse_livraison']['adresse']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['adresse_livraison']['code_postal']->renderError() ?>
                            <?php echo $form['adresse_livraison']['code_postal']->renderLabel() ?>
                            <?php echo $form['adresse_livraison']['code_postal']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['adresse_livraison']['commune']->renderError() ?>
                            <?php echo $form['adresse_livraison']['commune']->renderLabel() ?>
                            <?php echo $form['adresse_livraison']['commune']->render() ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="contenu_onglet" data-cible="vrac_mandataire">
            <?php echo $form['mandataire_exist']->renderError() ?>
            <?php echo $form['mandataire_exist']->renderLabel() ?>
            <?php echo $form['mandataire_exist']->render() ?>
        </div>
        <div id="mandataire" class="vrac_mandataire">
            <h1>Mandataire</h1>
            <h2>Sélectionner un mandataire :</h2>
            <div class="section_label_strong">
                <?php echo $form['mandataire_identifiant']->renderError() ?>
                <label for="">Nom :</label>
                <?php echo $form['mandataire_identifiant']->render() ?>
            </div>
            <div  class="bloc_form"> 
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['raison_sociale']->renderError() ?>
                            <?php echo $form['mandataire']['raison_sociale']->renderLabel() ?>
                            <?php echo $form['mandataire']['raison_sociale']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['siret']->renderError() ?>
                            <?php echo $form['mandataire']['siret']->renderLabel() ?>
                            <?php echo $form['mandataire']['siret']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['adresse']->renderError() ?>
                            <?php echo $form['mandataire']['adresse']->renderLabel() ?>
                            <?php echo $form['mandataire']['adresse']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['commune']->renderError() ?>
                            <?php echo $form['mandataire']['commune']->renderLabel() ?>
                            <?php echo $form['mandataire']['commune']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['code_postal']->renderError() ?>
                            <?php echo $form['mandataire']['code_postal']->renderLabel() ?>
                            <?php echo $form['mandataire']['code_postal']->render() ?>
                        </span>
                    </div>

                </div>
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['nom']->renderError() ?>
                            <?php echo $form['mandataire']['nom']->renderLabel() ?>
                            <?php echo $form['mandataire']['nom']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['carte_pro']->renderError() ?>
                            <?php echo $form['mandataire']['carte_pro']->renderLabel() ?>
                            <?php echo $form['mandataire']['carte_pro']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['telephone']->renderError() ?>
                            <?php echo $form['mandataire']['telephone']->renderLabel() ?>
                            <?php echo $form['mandataire']['telephone']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['fax']->renderError() ?>
                            <?php echo $form['mandataire']['fax']->renderLabel() ?>
                            <?php echo $form['mandataire']['fax']->render() ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['email']->renderError() ?>
                            <?php echo $form['mandataire']['email']->renderLabel() ?>
                            <?php echo $form['mandataire']['email']->render() ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <div id="contrat">
            <h1>Type de contrat</h1>
            <div class="section_label_strong">
                <?php echo $form['premiere_mise_en_marche']->renderError() ?>
                <?php echo $form['premiere_mise_en_marche']->renderLabel() ?>
                <?php echo $form['premiere_mise_en_marche']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['production_otna']->renderError() ?>
                <?php echo $form['production_otna']->renderLabel() ?>
                <?php echo $form['production_otna']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['apport_union']->renderError() ?>
                <?php echo $form['apport_union']->renderLabel() ?>
                <?php echo $form['apport_union']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['cession_interne']->renderError() ?>
                <?php echo $form['cession_interne']->renderLabel() ?>
                <?php echo $form['cession_interne']->render() ?>
            </div>
        </div>

        <div class="ligne_form_btn">
            <button class="annuler_saisie" type="reset">annuler la saisie</button>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        
    </form>
</section>