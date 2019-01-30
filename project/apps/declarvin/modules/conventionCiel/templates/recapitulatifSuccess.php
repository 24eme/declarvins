<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>
<style>
#principal #contenu_onglet, #principal .contenu_onglet {
	padding: 5px;
}
</style>
<section id="contenu">
    <div id="creation_compte">
        <div id="principal" >
            <h1>Récapitulatif</h1>
            <div id="recapitulatif">
            
            
                <div class="recap_perso">
                    <h2 style="font-weight: bold; width:155px; padding: 6px 10px;">Opérateur bénéficiaire</h2>
                    <div id="contenu_onglet">
                        <div class="col">
                            <p><span>Raison sociale :</span> <strong><?php echo $convention->raison_sociale ?></strong></p>
                            <p><span>N°SIREN/SIRET ou N°douane :</span> <strong><?php echo $convention->no_operateur ?></strong></p>
                            <p><span>N°SIRET payeur :</span> <strong><?php echo $convention->no_siret_payeur ?></strong></p>
                            <p><span>Adresse :</span> <strong><?php echo $convention->adresse ?></strong></p>
                            <p><span>Code Postal :</span> <strong><?php echo $convention->code_postal ?></strong></p>
                            <p><span>Commune :</span> <strong><?php echo $convention->commune ?></strong></p>
                            <p><span>Courriel :</span> <strong><?php echo $convention->email_beneficiaire ?></strong></p>
                            <p><span>Téléphone :</span> <strong><?php echo $convention->telephone_beneficiaire ?></strong></p>
                            <p><span>Date de fin d'excercice :</span> <strong><?php echo $convention->getObj('date_fin_exercice')->format('d/m/Y') ?></strong></p>
                            <p><span>Date de passage envisagé à CIEL :</span> <strong><?php echo $convention->getObj('date_ciel')->format('d/m/Y') ?></strong></p>
                        </div>
                        <div class="col tableau_ajouts_liquidations">
                            <p><span>Interprofession principale :</span> <strong><?php echo $convention->getInterprofession() ?></strong></p>
                            <table class="tableau_recap">
	                        	<thead>
		                        	<tr>
		                        		<td style="border: none; padding: 0;"></td>
		                        		<th>N° CVI</th>
		                        		<th>N° accises / EA</th>
		                        	</tr>
	                        	</thead>
	                        	<tbody>
		                        	<?php $i=1; foreach ($convention->etablissements as $etab): $i++; ?>
		                        	<tr<?php if($i%2!=0) echo ' class="alt"'; ?>>
		                        		<td><?php echo ($etab->raison_sociale)? $etab->raison_sociale : $etab->nom; ?></td>
		                        		<td><?php echo $etab->cvi ?></td>
		                        		<td><?php echo $etab->no_accises ?></td>
		                        	</tr>
		                        	<?php endforeach; ?>
	                        	</tbody>
	                        </table>
                        </div>
                        
                    </div>
                </div>
                
                <div class="recap_perso">
                    <h2 style="font-weight: bold; width:155px; padding: 6px 10px;">Signataire de la convention</h2>
                    <div id="contenu_onglet">
                        <div class="col">
                            <p><span>Nom :</span> <strong><?php echo $convention->nom ?></strong></p>
                            <p><span>Prénom :</span> <strong><?php echo $convention->prenom ?></strong></p>
                            <?php if ($convention->representant_legal): ?><p>Agissant en qualité de représentant légal</p><?php endif; ?>
                            <?php if ($convention->mandataire): ?><p><?php echo ConventionCiel::$mandataire[$convention->mandataire] ?></p><?php endif; ?>
                        </div>
                        <div class="col">
                            <p><span>Courriel :</span> <strong><?php echo $convention->email ?></strong></p>
                            <p><span>Téléphone :</span> <strong><?php echo $convention->telephone ?></strong></p>
                        </div>
                    </div>
                </div>
                
                <div class="recap_perso">
                    <h2 style="font-weight: bold; width:155px; padding: 6px 10px;">Tableau des habilitations</h2>
                    <div id="contenu_onglet">
                    	<div class="tableau_ajouts_liquidations" style="padding: 20px 0 0 0;">
	                        <table class="tableau_recap">
	                        	<thead>
		                        	<tr>
		                        		<th>N° accises / EA</th>
		                        		<th>Id. Prodou@ne</th>
		                        		<th>Droit téléprocédure</th>
		                        		<th>Droit télépaiement</th>
		                        	</tr>
	                        	</thead>
	                        	<tbody>
		                        	<?php $i=1; foreach ($convention->getHabilitationsSaisies() as $habilitation): $i++; ?>
		                        	<tr<?php if($i%2!=0) echo ' class="alt"'; ?>>
		                        		<td><?php echo ($habilitation->no_accises)? $habilitation->no_accises : ''; ?></td>
		                        		<td><?php echo ($habilitation->identifiant)? $habilitation->identifiant : ''; ?></td>
		                        		<td><?php echo ($habilitation->droit_teleprocedure)? ConventionCiel::$droits[$habilitation->droit_teleprocedure] : 'X'; ?></td>
		                        		<td><?php echo ($habilitation->droit_telepaiement)? ConventionCiel::$droits[$habilitation->droit_telepaiement] : 'X'; ?></td>
		                        	</tr>
		                        	<?php endforeach; ?>
	                        	</tbody>
	                        </table>
	                     </div>
                    </div>
                </div>
                
                
                <div id="btn_etape_dr">
                    <a href="<?php echo url_for('convention_nouveau', $etablissement) ?>" style="background: #ff9f00 url('/images/pictos/pi_modifier.png') no-repeat scroll 7px center; border: 1px solid #d68500; color: #ffffff; display: inline-block; font-weight: bold; height: 21px; line-height: 21px; padding: 0 7px 0 30px; text-transform: uppercase;"><span>Modifier</span></a>
                    <a href="<?php echo url_for('convention_confirmation', $etablissement) ?>" class="btn_suiv"><span>Valider</span></a>
                </div>
            
            </div>
        </div>
    </div>
</section>