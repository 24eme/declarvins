<div id="application_dr" class="clearfix">
	<h1>Informations relatives à votre établissement</h1>
	<div id="compteModification">
		<div class="societe">
			<p>
				Raison Sociale : <strong><?php echo $etablissement->raison_sociale ?></strong><br />
				Nom Commercial : <strong><?php echo $etablissement->nom ?></strong><br />
				N° RCS / SIRET: <strong><?php echo $etablissement->siret ?></strong><br />
				N° Carte Nationale d'Identité pour les exploitants individuels : <strong><?php echo $etablissement->cni ?></strong><br />
				N° CVI : <strong><?php echo $etablissement->cvi ?></strong><br />
				N° accises : <strong><?php echo $etablissement->no_accises ?></strong><br />
				N° TVA intracommunautaire : <strong><?php echo $etablissement->no_tva_intracommunautaire ?></strong><br />
				Adresse : <strong><?php echo $etablissement->siege->adresse ?></strong><br />
				CP : <strong><?php echo $etablissement->siege->code_postal ?></strong><br />
				ville : <strong><?php echo $etablissement->siege->commune ?></strong><br />
				tel : <strong><?php echo $etablissement->telephone ?></strong><br />
				fax : <strong><?php echo $etablissement->fax ?></strong><br />
				email : <strong><?php echo $etablissement->email ?></strong>
			</p>
			<p>
				Famille : <strong><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?></strong><br />
			    Sous-famille : <strong><?php echo EtablissementFamilles::getSousFamilleLibelle($etablissement->famille, $etablissement->sous_famille) ?></strong>
            </p>
			<?php if ($etablissement->comptabilite->adresse): ?>
			<p>
				Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<br />
				Adresse : <strong><?php echo $etablissement->comptabilite->adresse ?></strong><br />
				CP : <strong><?php echo $etablissement->comptabilite->code_postal ?></strong><br />
				ville : <strong><?php echo $etablissement->comptabilite->commune ?></strong>
			</p>
            <?php endif; ?>
			<p>
				Dépend du service des douanes de : <strong><?php echo $etablissement->service_douane ?></strong>
			</p>
		</div>
	</div>
</div>