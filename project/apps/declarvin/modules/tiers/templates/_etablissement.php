<div id="application_dr" class="clearfix">
	
	<h1>Votre établissement<?php if ($etablissement->statut == Etablissement::STATUT_ARCHIVE): ?> (archivé)<?php endif; ?></h1>
	<div id="compteModification">
		<div class="societe">
			<ul>
				<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
				<li>Identifiant <?php echo str_replace('INTERPRO-', '', $etablissement->interpro) ?> : <strong><?php echo $etablissement->identifiant ?></strong></li>
				<?php if ($etablissement->exist('correspondances')): foreach ($etablissement->correspondances as $interpro => $correspondance): if ($correspondance == $etablissement->identifiant) { continue; }?>
				<li>Identifiant <?php echo str_replace('INTERPRO-', '', $interpro) ?> : <strong><?php echo $correspondance ?></strong></li>
				<?php endforeach; endif;?>
				<li>&nbsp;</li>
				<?php endif; ?>
				<li>Raison Sociale : <strong><?php echo $etablissement->raison_sociale ?></strong></li>
				<li>Nom Commercial : <strong><?php echo $etablissement->nom ?></strong></li>
				<li>N° RCS / SIRET: <strong><?php echo $etablissement->siret ?></strong></li>
				<li>N° Carte Nationale d'Identité pour les exploitants individuels : <strong><?php echo $etablissement->cni ?></strong></li>
				<li>N° CVI : <strong><?php echo $etablissement->cvi ?></strong></li>
				<li>N° accises / EA : <strong><?php echo $etablissement->no_accises ?></strong></li>
				<li>N° TVA intracommunautaire : <strong><?php echo $etablissement->no_tva_intracommunautaire ?></strong></li>
				<li>N° Carte professionnelle : <strong><?php echo $etablissement->no_carte_professionnelle ?></strong></li>
				<li>Adresse : <strong><?php echo $etablissement->siege->adresse ?></strong></li>
				<li>CP : <strong><?php echo $etablissement->siege->code_postal ?></strong></li>
				<li>ville : <strong><?php echo $etablissement->siege->commune ?></strong></li>
				<li>Pays : <strong><?php echo $etablissement->siege->pays ?></strong></li>
				<li>tel : <strong><?php echo $etablissement->telephone ?></strong></li>
				<li>fax : <strong><?php echo $etablissement->fax ?></strong></li>
				<li>email : <strong><?php echo $etablissement->email ?></strong></li>
			</ul>
			<ul>
				<li>Interprofession référente : <strong><?php echo $etablissement->getInterproObject()->nom ?></strong></li>
				<?php if ($etablissement->exist('zones')): ?>
				<li>Zones : <strong><?php $zones = ''; foreach ($etablissement->zones as $zone): if (!$zone->transparente): $zones .= $zone->libelle.' - '; endif; endforeach; echo substr($zones, 0, -2); ?></strong></li>
				<?php endif; ?>
				<li>Famille : <strong><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?></strong></li>
			    <li>Sous-famille : <strong><?php echo EtablissementFamilles::getSousFamilleLibelle($etablissement->famille, $etablissement->sous_famille) ?></strong></li>
				<li>Dépend du service des douanes de : <strong><?php echo $etablissement->service_douane ?></strong></li>
            </ul>
			<?php if ($etablissement->comptabilite->adresse): ?>
			<h2>Lieu où est tenue la comptabilité matière :</h2>
			<ul>
				<?php if($etablissement->comptabilite->adresse): ?><li>Adresse : <strong><?php echo $etablissement->comptabilite->adresse ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->code_postal): ?><li>CP : <strong><?php echo $etablissement->comptabilite->code_postal ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->commune): ?><li>ville : <strong><?php echo $etablissement->comptabilite->commune ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->pays): ?><li>Pays : <strong><?php echo $etablissement->comptabilite->pays ?></strong></li><?php endif; ?>
			</ul>
            <?php endif; ?>
		</div>
	</div>
</div>