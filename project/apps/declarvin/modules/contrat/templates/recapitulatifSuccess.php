<section id="contenu" style="padding: 30px 20px 70px;">
    <div id="creation_compte">
        <div id="principal" >
            <h1>Récapitulatif</h1>
            <div id="recapitulatif">
                <div class="recap_perso">
                    <h2>Coordonnées</h2>
                    <div id="contenu_onglet">
                        <div class="col">
                            <p><span>Nom :</span> <strong><?php echo $contrat->getNom() ?></strong></p>
                            <p><span>Prénom :</span> <strong><?php echo $contrat->getPrenom() ?></strong></p>
                            <p><span>Fonction :</span> <strong><?php echo $contrat->getFonction() ?></strong></p>
                        </div>
                        <div class="col">
                            <p><span>Email :</span> <strong><?php echo $contrat->getEmail() ?></strong></p>
                            <p><span>Téléphone :</span> <strong><?php echo $contrat->getTelephone() ?></strong></p>
                            <p><span>Fax :</span> <strong><?php echo $contrat->getFax() ?></strong></p>
                        </div>
                    </div>
                </div>

                <div class="recap_etablissement">

                    <?php
                    foreach ($contrat->etablissements as $etablissement):
                    	$zones = array();
                    	foreach ($etablissement->zones as $zone): if (!$zone->transparente) {$zones[] = $zone->libelle;} endforeach;
                        ?>
					
                        <h2>Etablissement <?php echo $etablissement->getKey() + 1; ?></h2>
                        <div class="contenu_onglet">
                            <div class="col">
                                <p><span>Raison Sociale :</span> <strong><?php echo $etablissement->raison_sociale ?></strong></p>
                                <p><span>Nom commercial :</span> <strong><?php echo $etablissement->nom ?></strong></p>
                                <p><span>N° RCS / SIRET:</span> <strong><?php echo $etablissement->siret ?></strong></p>
                                <p><span>N° CNI :</span> <strong><?php echo $etablissement->cni ?></strong></p>
                                <p><span>N° CVI :</span> <strong><?php echo $etablissement->cvi ?></strong></p>
                                <p><span>N° accises / EA :</span> <strong><?php echo $etablissement->no_accises ?></strong></p>
                                <p><span>N° TVA intracommunautaire :</span> <strong><?php echo $etablissement->no_tva_intracommunautaire ?></strong></p>
                                <p><span>N° de carte professionnelle :</span> <strong><?php echo $etablissement->no_carte_professionnelle ?></strong></p>
                                <p><span>Adresse :</span> <strong><?php echo $etablissement->adresse ?></strong></p>
                                <p><span>CP :</span> <strong><?php echo $etablissement->code_postal ?></strong></p>
                                <p><span>ville :</span> <strong><?php echo $etablissement->commune ?></strong></p>
                                <p><span>Pays :</span> <strong><?php echo $etablissement->pays ?></strong></p>
                                <p><span>tel :</span> <strong><?php echo $etablissement->telephone ?></strong></p>
                                <p><span>fax :</span> <strong><?php echo $etablissement->fax ?></strong></p>
                                <p><span>email :</span> <strong><?php echo $etablissement->email ?></strong></p>
                            </div>
                            <div class="col">
                                <p><span>Famille :</span> <strong><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?></strong></p>
                                <p><span>Sous-famille :</span> <strong><?php echo EtablissementFamilles::getSousFamilleLibelle($etablissement->famille, $etablissement->sous_famille) ?></strong></p>
                                <p><span>Zones :</span> <strong><?php echo implode(', ', $zones); ?></strong></p>
                                <p><span>J'utilise un logiciel agréé EDI Declarvins pour déclarer mes DRM et DAI/DS :</span> <strong><?php echo  ($etablissement->edi) ? "Oui" : "Non" ?></strong></p>
                                <?php if ($etablissement->comptabilite_adresse): ?>
                                    <div class="adresse_comptabilite">
                                        <p>Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<p>
                                        <p><span>Adresse :</span> <strong><?php echo $etablissement->comptabilite_adresse ?></strong></p>
                                        <p><span>CP :</span> <strong><?php echo $etablissement->comptabilite_code_postal ?></strong></p>
                                        <p><span>ville :</span> <strong><?php echo $etablissement->comptabilite_commune ?></strong></p>	
                                        <p><span>Pays :</span> <strong><?php echo $etablissement->comptabilite_pays ?></strong></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ligne_btn">
                            	<?php if ($etablissement->getKey() > 0): ?>
                                <a class="btn_supprimer" href="<?php echo url_for('contrat_etablissement_suppression', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>" >Supprimer</a>
                                <?php endif; ?>
                                <a class="btn_ajouter" href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>">Modifier</a>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
                <div id="btn_etape_dr">
                    <a href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>" class="btn_prec"><span>Précédent</span></a>
                    <a href="<?php echo url_for('contrat_etablissement_nouveau') ?>" class="btn_ajouter">Ajouter un nouvel établissement</a>
                    <a href="<?php echo url_for('contrat_etablissement_confirmation') ?>" class="btn_suiv"><span>Valider</span></a>
                </div>
            </div>
        </div>
    </div>
</section>