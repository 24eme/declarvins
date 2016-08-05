<section id="contenu" style="padding: 30px 20px 70px;">
    <div id="creation_compte">
        <div id="principal" >
            <h1>Récapitulatif</h1>
            <div id="recapitulatif">
            
            
                <div class="recap_perso">
                    <h2>Opérateur bénéficiaire</h2>
                    <div id="contenu_onglet">
                        <div class="col">
                            <p><span>Raison sociale :</span> <strong><?php echo $convention->raison_sociale ?></strong></p>
                            <p><span>N°SIREN/SIRET ou N°douane :</span> <strong><?php echo $convention->no_operateur ?></strong></p>
                        </div>
                        <div class="col">
                            <p><span>Interprofession principale :</span> <strong><?php echo $convention->getInterprofession() ?></strong></p>
                        </div>
                        <h1>Etablissements</h1>
                    </div>
                </div>
                
                <div class="recap_etablissement">

                    <?php
                    foreach ($convention->etablissements as $etablissement):
                        ?>
					
                        <h2>Etablissement <?php echo ($etablissement->raison_sociale)? $etablissement->raison_sociale : $etablissement->nom; ?></h2>
                        <div class="contenu_onglet">
                            <div class="col">
                                <p><span>N° CVI :</span> <strong><?php echo $etablissement->cvi ?></strong></p>
                                <p><span>N° accises / EA :</span> <strong><?php echo $etablissement->no_accises ?></strong></p>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
                
                <div class="recap_perso">
                    <h2>Signataire de la convention</h2>
                    <div id="contenu_onglet">
                        <div class="col">
                            <p><span>Nom :</span> <strong><?php echo $convention->nom ?></strong></p>
                            <p><span>Prénom :</span> <strong><?php echo $convention->prenom ?></strong></p>
                            <p><span>Fonction :</span> <strong><?php echo $convention->fonction ?></strong></p>
                        </div>
                        <div class="col">
                            <p><span>E-mail :</span> <strong><?php echo $convention->email ?></strong></p>
                            <p><span>Téléphone :</span> <strong><?php echo $convention->telephone ?></strong></p>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</section>