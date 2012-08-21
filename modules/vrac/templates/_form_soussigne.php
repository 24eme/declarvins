    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <?php if(isset($form['vous_etes'])): ?>
        <div class="contenu_onglet" data-cible="vrac_vendeur_acheteur">
            <?php echo $form['vous_etes']->renderError(); ?>
            <?php echo $form['vous_etes']->renderLabel(); ?>
            <?php echo $form['vous_etes']->render(); ?>
            <!--<label for="">Vous êtes: </label>
            <ul class="radio_list">
                <li>
                    <input name="vrac_acheteur_vendeur" type="radio" id="vrac_vendeur" value="vendeur"<?php if ($form->getObject()->acheteur_identifiant == $etablissement->identifiant): ?> checked="checked"<?php endif; ?> />&nbsp;<label for="vrac_vendeur">vendeur</label>
                </li>
                <li>
                    <input data-cible="#<?php echo $form['acheteur_identifiant']->renderId() ?>" data-value="<?php echo $etablissement->identifiant ?>" name="vrac_acheteur_vendeur" type="radio" id="vrac_acheteur" value="acheteur"<?php if ($form->getObject()->vendeur_identifiant == $etablissement->identifiant): ?> checked="checked"<?php endif; ?> />&nbsp;<label for="vrac_acheteur">acheteur</label>
                </li>
            </ul>-->
        </div>
        <?php endif; ?>
        
        <div id="acheteur" class="vrac_vendeur_acheteur">
            <h1>Acheteur</h1>
            <h2>Sélectionner un acheteur :</h2>

            <div class="section_label_strong etablissement_famille_choice">
                <?php echo $form['acheteur_type']->renderLabel() ?>
                <?php echo $form['acheteur_type']->renderError() ?>
                <?php echo $form['acheteur_type']->render(array('class' => 'famille', 'data-template' => '#template_url_etablissement', 'data-container' => '#'.$form['acheteur_identifiant']->renderId())) ?>
            </div>
            <div class="section_label_strong" id="listener_acheteur_choice">
                <?php echo $form['acheteur_identifiant']->renderError() ?>
                <label for="">Nom :</label>
                <?php echo $form['acheteur_identifiant']->render() ?>
            </div>

            <div class="bloc_form" id="etablissement_acheteur"> 
                <?php include_partial('form_etablissement', array('form' => $form['acheteur'])); ?>
            </div>

			<div class="section_label_strong">
				<span> <?php echo $form['acheteur_assujetti_tva']->renderError() ?> <?php echo $form['acheteur_assujetti_tva']->renderLabel() ?>
				<?php echo $form['acheteur_assujetti_tva']->render() ?> </span>
			</div>
			
            <div class="adresse_livraison">
                <div class="section_label_strong">
                    <label for="dif_adr_livr">Précision de l'adresse de livraison (si différente) <input type="checkbox" name="dif_adr_livr" id="dif_adr_livr"></label>
                </div>
                <div class="bloc_form"> 
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['adresse_livraison']['libelle']->renderError() ?>
                            <?php echo $form['adresse_livraison']['libelle']->renderLabel() ?>
                            <?php echo $form['adresse_livraison']['libelle']->render() ?>
                        </span>
                    </div>
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
        
        <div id="vendeur" class="vrac_vendeur_acheteur">
            <h1>Vendeur</h1>
            <h2>Sélectionner un vendeur :</h2>

            <div class="section_label_strong etablissement_famille_choice">
                <?php echo $form['vendeur_type']->renderLabel() ?>
                <?php echo $form['vendeur_type']->renderError() ?>
                <?php echo $form['vendeur_type']->render(array('class' => 'famille', 'data-template' => '#template_url_etablissement', 'data-container' => '#'.$form['vendeur_identifiant']->renderId())) ?>
            </div>
            <div class="section_label_strong" id="listener_vendeur_choice">
                <?php echo $form['vendeur_identifiant']->renderError() ?>
                <label for="">Nom :</label>
                <?php echo $form['vendeur_identifiant']->render() ?>
            </div>
            <div  class="bloc_form" id="etablissement_vendeur"> 
                <?php include_partial('form_etablissement', array('form' => $form['vendeur'])); ?>
            </div>

			<div class="section_label_strong">
				<span>
                	<?php echo $form['vendeur_assujetti_tva']->renderError() ?>
					<?php echo $form['vendeur_assujetti_tva']->renderLabel() ?>
					<?php echo $form['vendeur_assujetti_tva']->render() ?>
				</span>
			</div>
            <div class="adresse_livraison">
                <div class="section_label_strong"><label for="dif_adr_stock">Précision de l'adresse de stockage (si différente) <input type="checkbox" name="dif_adr_stock" id="dif_adr_stock"></label></div>
                <div class="bloc_form"> 
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['adresse_stockage']['libelle']->renderError() ?>
                            <?php echo $form['adresse_stockage']['libelle']->renderLabel() ?>
                            <?php echo $form['adresse_stockage']['libelle']->render() ?>
                        </span>
                    </div>
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

        
        
        <div class="contenu_onglet" data-cible="vrac_mandataire">
            <?php echo $form['mandataire_exist']->renderError() ?>
            <?php echo $form['mandataire_exist']->renderLabel() ?>
            <?php echo $form['mandataire_exist']->render() ?>
        </div>
        <div id="mandataire" class="vrac_mandataire">
            <h1>Mandataire</h1>
            <h2>Sélectionner un mandataire :</h2>
            <div class="section_label_strong" id="listener_mandataire_choice">
                <?php echo $form['mandataire_identifiant']->renderError() ?>
                <label for="">Nom :</label>
                <?php echo $form['mandataire_identifiant']->render() ?>
            </div>
            <div  class="bloc_form" id="etablissement_mandataire"> 
                <?php include_partial('form_mandataire', array('form' => $form['mandataire'])); ?>
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
                <?php echo $form['cas_particulier']->renderError() ?>
                <?php echo $form['cas_particulier']->renderLabel() ?>
                <?php echo $form['cas_particulier']->render() ?>
            </div>
        </div>

        <div class="ligne_form_btn">
            <button class="annuler_saisie" type="reset">annuler la saisie</button>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        
    </form>
    <?php include_partial('url_etablissement_template'); ?>
    <?php include_partial('url_informations_template', array('vrac' => $form->getObject(), 'etablissement' => $etablissement, 'etape' => $etape)); ?>
