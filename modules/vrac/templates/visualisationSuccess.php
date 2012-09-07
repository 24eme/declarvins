<?php include_component('global', 'nav', array('active' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
                <?php if ($sf_user->hasFlash('termine')): ?>
					<h2>La saisie est terminée !</h2>
				<?php endif; ?>
                <div id="titre">
                    <span class="style_label">N° d'enregistrement du contrat : <?php echo $vrac->numero_contrat ?></span>
                </div>    
                <form action="" method="post" id="vrac_condition">  
                    <div class="legende" id="ss_titre"><span class="style_label">Etat du contrat</span>
                        <a id="solder_contrat" href="">Solder le contrat</a>
                        <div>
                            <span class="statut statut_<?php echo $vrac->getStatutCssClass() ?>"></span><span class="legende_statut_texte"><?php echo $vrac->valide->statut ?></span>
                        </div>                            
                    </div>
                    <div id="ligne_btn">
                            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" id="btn_editer_contrat"  class="modifier"> Editer le contrat</a>
                            <button type="submit" id="btn_annuler_contrat">Annuler le contrat</button>                                
                    </div>
                </form>
            
                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>
                
                <div class="ligne_form_btn">
					<?php if ($etablissement): ?>
						<a href="<?php echo url_for("vrac_etablissement", array('identifiant' => $etablissement->identifiant)) ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
					<?php else: ?>
						<a href="<?php echo url_for("vrac_admin") ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
					<?php endif; ?>
				</div>
                
            </div> 
        </section>
    </div>
</div>
