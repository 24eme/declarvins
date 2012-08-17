<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
                
                <div id="titre">
                    <span class="style_label">N° d'enregistrement du contrat : <?php echo $vrac->numero_contrat ?></span>
                </div>    
                <form action="" method="post" id="vrac_condition">  
                    <div class="legende" id="ss_titre"><span class="style_label">Etat du contrat</span>
                        <a id="solder_contrat" href="">Solder le contrat</a>
                        <div>
                            <span class="statut statut_non-solde"></span><span class="legende_statut_texte">NONSOLDE</span>
                        </div>                            
                    </div>
                    <div id="ligne_btn">
                            <a href="" id="btn_editer_contrat"  class="modifier"> Editer le contrat</a>
                            <button type="submit" id="btn_annuler_contrat">Annuler le contrat</button>                                
                    </div>
                </form>
            
                <?php include_partial('showContrat', array('vrac' => $vrac, 'configurationVrac' => $configurationVrac)); ?>
                
                <div class="ligne_form_btn">
			<a class="etape_prec" href="<?php echo url_for('vrac') ?>"><span>Retour à la liste des contrats</span></a>	
		</div>
                
            </div> 
        </section>
    </div>
</div>
