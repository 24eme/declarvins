<div id="application_dr" class="clearfix">
	
	<h1>Votre établissement<?php if ($etablissement->statut == Etablissement::STATUT_ARCHIVE): ?> (archivé)<?php endif; ?></h1>
	<div id="compteModification">
		<div class="societe">
			<ul>
				<li>Raison Sociale : <strong><?php echo $etablissement->raison_sociale ?></strong></li>
				<li>Nom Commercial : <strong><?php echo $etablissement->nom ?></strong></li>
				<li>N° RCS / SIRET: <strong><?php echo $etablissement->siret ?></strong></li>
				<li>N° Carte Nationale d'Identité pour les exploitants individuels : <strong><?php echo $etablissement->cni ?></strong></li>
				<li>N° CVI : <strong><?php echo $etablissement->cvi ?></strong></li>
				<li>N° accises : <strong><?php echo $etablissement->no_accises ?></strong></li>
				<li>N° TVA intracommunautaire : <strong><?php echo $etablissement->no_tva_intracommunautaire ?></strong></li>
				<li>Adresse : <strong><?php echo $etablissement->siege->adresse ?></strong></li>
				<li>CP : <strong><?php echo $etablissement->siege->code_postal ?></strong></li>
				<li>ville : <strong><?php echo $etablissement->siege->commune ?></strong></li>
				<li>Pays : <strong><?php echo $etablissement->siege->pays ?></strong></li>
				<li>tel : <strong><?php echo $etablissement->telephone ?></strong></li>
				<li>fax : <strong><?php echo $etablissement->fax ?></strong></li>
				<li>email : <strong><?php echo $etablissement->email ?></strong></li>
			</ul>
			<ul>
				<li>Famille : <strong><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?></strong></li>
			    <li>Sous-famille : <strong><?php echo EtablissementFamilles::getSousFamilleLibelle($etablissement->famille, $etablissement->sous_famille) ?></strong></li>
            </ul>
			<?php if ($etablissement->comptabilite->adresse): ?>
			<h2>Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :</h2>
			<ul>
				<?php if($etablissement->comptabilite->adresse): ?><li>Adresse : <strong><?php echo $etablissement->comptabilite->adresse ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->code_postal): ?><li>CP : <strong><?php echo $etablissement->comptabilite->code_postal ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->commune): ?><li>ville : <strong><?php echo $etablissement->comptabilite->commune ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->pays): ?><li>Pays : <strong><?php echo $etablissement->comptabilite->pays ?></strong></li><?php endif; ?>
			</ul>
            <?php endif; ?>
			<ul>
				<li>Dépend du service des douanes de : <strong><?php echo $etablissement->service_douane ?></strong></li>
			</ul>
		</div>
	</div>
</div>