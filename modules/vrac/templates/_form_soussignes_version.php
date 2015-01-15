<div id="bloc_vendeur" class="vrac_vendeur_acheteur">
    <h1>Vendeur <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_informations" title="Message aide"></a></h1>

    <div id="bloc_vendeur_vous" class="soussigne_vous">
    	<?php if ($vrac->vous_etes == 'vendeur'): ?><h2>Vous êtes le vendeur</h2><?php endif; ?>
        <div class="section_label_strong etablissement_famille_choice">
            <label for="">Type :</label>
            <?php echo $vrac->vendeur->famille ?>
        </div>
        <div class="section_label_strong etablissement_choice">
            <label for="">Nom :</label>
            <?php echo $vrac->vendeur->raison_sociale ?><?php if ($vrac->vendeur->raison_sociale): ?> / <?php endif; ?><?php echo $vrac->vendeur->nom ?>
        </div>

        <div class="bloc_form etablissement_informations"> 
            <div class="col">
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['vendeur']['raison_sociale']->renderLabel() ?>
			            <?php echo $form['vendeur']['raison_sociale']->render(array('value' => $vrac->vendeur->raison_sociale, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['vendeur']['siret']->renderLabel() ?>
			            <?php echo $form['vendeur']['siret']->render(array('value' => $vrac->vendeur->siret, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['vendeur']['adresse']->renderLabel() ?>
			        	<textarea id="<?php echo $form['vendeur']['adresse']->renderId() ?>" disabled="disabled" class="disabled" name="<?php echo $form['vendeur']['adresse']->renderName() ?>" cols="30" rows="4"><?php echo $vrac->vendeur->adresse ?></textarea>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['vendeur']['commune']->renderLabel() ?>
			            <?php echo $form['vendeur']['commune']->render(array('value' => $vrac->vendeur->commune, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['vendeur']['code_postal']->renderLabel() ?>
			            <?php echo $form['vendeur']['code_postal']->render(array('value' => $vrac->vendeur->code_postal, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['vendeur']['pays']->renderLabel() ?>
			            <?php echo $form['vendeur']['pays']->render(array('value' =>$vrac->vendeur->pays, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			</div>
			<div class="col">
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['vendeur']['nom']->renderLabel() ?>
			            <?php echo $form['vendeur']['nom']->render(array('value' => $vrac->vendeur->nom, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['vendeur']['cvi']->renderLabel() ?>
			            <?php echo $form['vendeur']['cvi']->render(array('value' => $vrac->vendeur->cvi, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['vendeur']['telephone']->renderLabel() ?>
			            <?php echo $form['vendeur']['telephone']->render(array('value' => $vrac->vendeur->telephone, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['vendeur']['fax']->renderLabel() ?>
			            <?php echo $form['vendeur']['fax']->render(array('value' => $vrac->vendeur->fax, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['vendeur']['email']->renderLabel() ?>
			            <?php echo $form['vendeur']['email']->render(array('value' => $vrac->vendeur->email, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>&nbsp;</span>
			    </div>
			</div>
			<div style="clear:both"></div>
            
        </div>
    </div>

    <div class="section_label_strong">
        <?php echo $form['vendeur_tva']->renderError() ?> 
        <?php echo $form['vendeur_tva']->renderLabel() ?>
        <?php echo $form['vendeur_tva']->render(array('class' => 'disabled', 'disabled' => 'disabled')) ?>
    </div>
    
   <div class="bloc_adresse">
	    <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_adresse_stockage_form">
	        <label for="cb_adresse_differente_adresse_stockage">
	        	<input type="checkbox" disabled="disabled" class="disabled" value="differente" id="cb_adresse_differente_adresse_stockage" <?php if($vrac->adresse_stockage->adresse): echo 'checked="checked"'; endif; ?> /> 
	            Adresse de stockage différente
	        </label>
	    </div>
	    <div id="bloc_adresse_stockage_form" class="bloc_form bloc_conditionner" data-condition-value="differente"> 
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <span>
	                <?php echo $form['adresse_stockage']['libelle']->renderLabel() ?>
	                <?php echo $form['adresse_stockage']['libelle']->render(array('value' => $vrac->adresse_stockage->libelle, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form ">
	            <span>
	                <?php echo $form['adresse_stockage']['adresse']->renderLabel() ?>
	                <?php echo $form['adresse_stockage']['adresse']->render(array('value' => $vrac->adresse_stockage->adresse, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <span>
	                <?php echo $form['adresse_stockage']['code_postal']->renderLabel() ?>
	                <?php echo $form['adresse_stockage']['code_postal']->render(array('value' => $vrac->adresse_stockage->code_postal, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form ">
	            <span>
	                <?php echo $form['adresse_stockage']['commune']->renderLabel() ?>
	                <?php echo $form['adresse_stockage']['commune']->render(array('value' => $vrac->adresse_stockage->commune, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <span>
	                <?php echo $form['adresse_stockage']['pays']->renderLabel() ?>
	                <?php echo $form['adresse_stockage']['pays']->render(array('value' => $vrac->adresse_stockage->pays, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	    </div>
	</div>
	
	<!-- FIN VENDEUR -->
	
	
	<div id="bloc_acheteur_vous" class="soussigne_vous">
    	<?php if ($vrac->vous_etes == 'acheteur'): ?><h2>Vous êtes l'acheteur</h2><?php endif; ?>
        <div class="section_label_strong etablissement_famille_choice">
            <label for="">Type :</label>
            <?php echo $vrac->acheteur->famille ?>
        </div>
        <div class="section_label_strong etablissement_choice">
            <label for="">Nom :</label>
            <?php echo $vrac->acheteur->raison_sociale ?><?php if ($vrac->acheteur->raison_sociale): ?> / <?php endif; ?><?php echo $vrac->acheteur->nom ?>
        </div>

        <div class="bloc_form etablissement_informations"> 
            <div class="col">
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['acheteur']['raison_sociale']->renderLabel() ?>
			            <?php echo $form['acheteur']['raison_sociale']->render(array('value' => $vrac->acheteur->raison_sociale, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['acheteur']['siret']->renderLabel() ?>
			            <?php echo $form['acheteur']['siret']->render(array('value' => $vrac->acheteur->siret, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['acheteur']['adresse']->renderLabel() ?>
			        	<textarea id="<?php echo $form['acheteur']['adresse']->renderId() ?>" disabled="disabled" class="disabled" name="<?php echo $form['acheteur']['adresse']->renderName() ?>" cols="30" rows="4"><?php echo $vrac->acheteur->adresse ?></textarea>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['acheteur']['commune']->renderLabel() ?>
			            <?php echo $form['acheteur']['commune']->render(array('value' => $vrac->acheteur->commune, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['acheteur']['code_postal']->renderLabel() ?>
			            <?php echo $form['acheteur']['code_postal']->render(array('value' => $vrac->acheteur->code_postal, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['acheteur']['pays']->renderLabel() ?>
			            <?php echo $form['acheteur']['pays']->render(array('value' =>$vrac->acheteur->pays, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			</div>
			<div class="col">
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['acheteur']['nom']->renderLabel() ?>
			            <?php echo $form['acheteur']['nom']->render(array('value' => $vrac->acheteur->nom, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['acheteur']['cvi']->renderLabel() ?>
			            <?php echo $form['acheteur']['cvi']->render(array('value' => $vrac->acheteur->cvi, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['acheteur']['telephone']->renderLabel() ?>
			            <?php echo $form['acheteur']['telephone']->render(array('value' => $vrac->acheteur->telephone, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>
			            <?php echo $form['acheteur']['fax']->renderLabel() ?>
			            <?php echo $form['acheteur']['fax']->render(array('value' => $vrac->acheteur->fax, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form ">
			        <span>
			            <?php echo $form['acheteur']['email']->renderLabel() ?>
			            <?php echo $form['acheteur']['email']->render(array('value' => $vrac->acheteur->email, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
			        </span>
			    </div>
			    <div class="vracs_ligne_form vracs_ligne_form_alt">
			        <span>&nbsp;</span>
			    </div>
			</div>
			<div style="clear:both"></div>
            
        </div>
    </div>

    <div class="section_label_strong">
        <?php echo $form['acheteur_tva']->renderError() ?> 
        <?php echo $form['acheteur_tva']->renderLabel() ?>
        <?php echo $form['acheteur_tva']->render(array('class' => 'disabled', 'disabled' => 'disabled')) ?>
    </div>
    
   <div class="bloc_adresse">
	    <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_adresse_livraison_form">
	        <label for="cb_adresse_differente_adresse_livraison">
	        	<input type="checkbox" disabled="disabled" class="disabled" value="differente" id="cb_adresse_differente_adresse_livraison" <?php if($vrac->adresse_livraison->adresse): echo 'checked="checked"'; endif; ?> /> 
	            Adresse de livraison différente
	        </label>
	    </div>
	    <div id="bloc_adresse_livraison_form" class="bloc_form bloc_conditionner" data-condition-value="differente"> 
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <span>
	                <?php echo $form['adresse_livraison']['libelle']->renderLabel() ?>
	                <?php echo $form['adresse_livraison']['libelle']->render(array('value' => $vrac->adresse_livraison->libelle, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form ">
	            <span>
	                <?php echo $form['adresse_livraison']['adresse']->renderLabel() ?>
	                <?php echo $form['adresse_livraison']['adresse']->render(array('value' => $vrac->adresse_livraison->adresse, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <span>
	                <?php echo $form['adresse_livraison']['code_postal']->renderLabel() ?>
	                <?php echo $form['adresse_livraison']['code_postal']->render(array('value' => $vrac->adresse_livraison->code_postal, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form ">
	            <span>
	                <?php echo $form['adresse_livraison']['commune']->renderLabel() ?>
	                <?php echo $form['adresse_livraison']['commune']->render(array('value' => $vrac->adresse_livraison->commune, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <span>
	                <?php echo $form['adresse_livraison']['pays']->renderLabel() ?>
	                <?php echo $form['adresse_livraison']['pays']->render(array('value' => $vrac->adresse_livraison->pays, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
	            </span>
	        </div>
	    </div>
	</div>
	
	<!-- FIN ACHETEUR -->
	
	<?php if(isset($form['mandataire_exist'])): ?>
	<div class="contenu_onglet bloc_condition" data-condition-cible="#bloc_mandataire">
	    <?php echo $form['mandataire_exist']->renderLabel() ?>
	    <?php echo $form['mandataire_exist']->render(array('class' => 'disabled', 'disabled' => 'disabled')) ?>
	</div>
	<?php endif; ?>
	
	<div id="bloc_mandataire" class="vrac_mandataire <?php if (isset($form['mandataire_exist'])): ?>bloc_conditionner<?php endif; ?>" data-condition-value="1">
    <h1>Courtier <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_informations" title="Message aide"></a></h1>
    

    <div class="soussigne_vous">
    	<?php if ($form->etablissementIsCourtier()): ?><h2>Vous êtes le courtier</h2><?php endif; ?>
        <div class="section_label_strong" id="listener_mandataire_choice">
            <label for="">Nom :</label>
            <?php echo $vrac->mandataire->raison_sociale ?><?php if ($vrac->mandataire->raison_sociale): ?> / <?php endif; ?><?php echo $vrac->mandataire->nom ?>
        </div>
        <div  class="bloc_form etablissement_informations"> 
            <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['raison_sociale']->renderLabel() ?>
                            <?php echo $form['mandataire']['raison_sociale']->render(array('value' => $vrac->mandataire->raison_sociale, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['siret']->renderLabel() ?>
                            <?php echo $form['mandataire']['siret']->render(array('value' => $vrac->mandataire->siret, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['adresse']->renderLabel() ?>
                            <textarea id="<?php echo $form['mandataire']['adresse']->renderId() ?>" disabled="disabled" class="disabled" name="<?php echo $form['mandataire']['adresse']->renderName() ?>" cols="30" rows="4"><?php echo $vrac->mandataire->adresse ?></textarea>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['commune']->renderLabel() ?>
                            <?php echo $form['mandataire']['commune']->render(array('value' => $vrac->mandataire->commune, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['code_postal']->renderLabel() ?>
                            <?php echo $form['mandataire']['code_postal']->render(array('value' => $vrac->mandataire->code_postal, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['pays']->renderLabel() ?>
                            <?php echo $form['mandataire']['pays']->render(array('value' => $vrac->mandataire->pays, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>

                </div>
                <div class="col">
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['nom']->renderLabel() ?>
                            <?php echo $form['mandataire']['nom']->render(array('value' => $vrac->mandataire->nom, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['no_carte_professionnelle']->renderLabel() ?>
                            <?php echo $form['mandataire']['no_carte_professionnelle']->render(array('value' => $vrac->mandataire->no_carte_professionnelle, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['telephone']->renderLabel() ?>
                            <?php echo $form['mandataire']['telephone']->render(array('value' => $vrac->mandataire->telephone, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>
                            <?php echo $form['mandataire']['fax']->renderLabel() ?>
                            <?php echo $form['mandataire']['fax']->render(array('value' => $vrac->mandataire->fax, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form ">
                        <span>
                            <?php echo $form['mandataire']['email']->renderLabel() ?>
                            <?php echo $form['mandataire']['email']->render(array('value' => $vrac->mandataire->email, 'class' => 'disabled', 'disabled' => 'disabled')) ?>
                        </span>
                    </div>
                    <div class="vracs_ligne_form vracs_ligne_form_alt">
                        <span>&nbsp;</span>
                    </div>
                </div>
        </div>
    </div>
    
</div>
	
</div>