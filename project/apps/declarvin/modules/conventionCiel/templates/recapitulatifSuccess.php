<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>

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
                        </div>
                        <div class="col">
                            <p><span>Interprofession principale :</span> <strong><?php echo $convention->getInterprofession() ?></strong></p>
                        </div>
                        
                        <div class="tableau_ajouts_liquidations" style="padding: 20px 0 0 0;">
	                        <table class="tableau_recap">
	                        	<thead>
		                        	<tr>
		                        		<td style="border: none; padding: 0;"><h1 style="font-size: 16px; margin: 0;">Etablissements</h1></td>
		                        		<th>N° CVI</th>
		                        		<th>N° accises / EA</th>
		                        	</tr>
	                        	</thead>
	                        	<tbody>
		                        	<?php $i=1; foreach ($convention->etablissements as $etab): $i++; ?>
		                        	<tr<?php if($i%2!=0) echo ' class="alt"'; ?>>
		                        		<td><?php echo ($etab->raison_sociale)? $etab->raison_sociale : $etab->nom; ?></td>
		                        		<td><?php echo $etab->cvi ?></td>
		                        		<td><?php echo $etab->cvi ?></td>
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
                            <p><span>Fonction :</span> <strong><?php echo $convention->fonction ?></strong></p>
                        </div>
                        <div class="col">
                            <p><span>E-mail :</span> <strong><?php echo $convention->email ?></strong></p>
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
		                        		<th>Nom</th>
		                        		<th>Prénom</th>
		                        		<th>Id. Prodou@ne</th>
		                        		<th>Droit téléprocédure</th>
		                        		<th>Droit télépaiement</th>
		                        		<th>Mensualisation</th>
		                        	</tr>
	                        	</thead>
	                        	<tbody>
		                        	<?php $i=1; foreach ($convention->habilitations as $habilitation): $i++; ?>
		                        	<tr<?php if($i%2!=0) echo ' class="alt"'; ?>>
		                        		<td><?php echo ($habilitation->no_accises)? $habilitation->no_accises : ''; ?></td>
		                        		<td><?php echo ($habilitation->nom)? $habilitation->nom : ''; ?></td>
		                        		<td><?php echo ($habilitation->prenom)? $habilitation->prenom : ''; ?></td>
		                        		<td><?php echo ($habilitation->identifiant)? $habilitation->identifiant : ''; ?></td>
		                        		<td><?php echo ($habilitation->droit_teleprocedure)? $habilitation->droit_teleprocedure : 'X'; ?></td>
		                        		<td><?php echo ($habilitation->droit_telepaiement)? $habilitation->droit_telepaiement : 'X'; ?></td>
		                        		<td><?php echo ($habilitation->mensualisation)? 'oui' : ''; ?></td>
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