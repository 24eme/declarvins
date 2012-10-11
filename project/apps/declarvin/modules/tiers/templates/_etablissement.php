<div id="application_dr" class="clearfix">
	<h1>Informations relatives à votre établissement</h1>
	<div id="compteModification">
		<div class="societe">
			<ul>
				<?php if($etablissement->raison_sociale): ?><li>Raison Sociale : <strong><?php echo $etablissement->raison_sociale ?></strong></li><?php endif; ?>
				<?php if($etablissement->nom): ?><li>Nom Commercial : <strong><?php echo $etablissement->nom ?></strong></li><?php endif; ?>
				<?php if($etablissement->siret): ?><li>N° RCS / SIRET: <strong><?php echo $etablissement->siret ?></strong></li><?php endif; ?>
				<?php if($etablissement->cni): ?><li>N° Carte Nationale d'Identité pour les exploitants individuels : <strong><?php echo $etablissement->cni ?></strong></li><?php endif; ?>
				<?php if($etablissement->cvi): ?><li>N° CVI : <strong><?php echo $etablissement->cvi ?></strong></li><?php endif; ?>
				<?php if($etablissement->no_accises): ?><li>N° accises : <strong><?php echo $etablissement->no_accises ?></strong></li><?php endif; ?>
				<?php if($etablissement->no_tva_intracommunautaire): ?><li>N° TVA intracommunautaire : <strong><?php echo $etablissement->no_tva_intracommunautaire ?></strong></li><?php endif; ?>
				<?php if($etablissement->siege->adresse): ?><li>Adresse : <strong><?php echo $etablissement->siege->adresse ?></strong></li><?php endif; ?>
				<?php if($etablissement->siege->code_postal): ?><li>CP : <strong><?php echo $etablissement->siege->code_postal ?></strong></li><?php endif; ?>
				<?php if($etablissement->siege->commune): ?><li>ville : <strong><?php echo $etablissement->siege->commune ?></strong></li><?php endif; ?>
				<?php if($etablissement->telephone): ?><li>tel : <strong><?php echo $etablissement->telephone ?></strong></li><?php endif; ?>
				<?php if($etablissement->fax): ?><li>fax : <strong><?php echo $etablissement->fax ?></strong></li><?php endif; ?>
				<?php if($etablissement->email): ?><li>email : <strong><?php echo $etablissement->email ?></strong></li><?php endif; ?>
			</ul>
			<ul>
				<?php if($etablissement->famille): ?><li>Famille : <strong><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?></strong></li><?php endif; ?>
			    <?php if($etablissement->sous_famille): ?><li>Sous-famille : <strong><?php echo EtablissementFamilles::getSousFamilleLibelle($etablissement->famille, $etablissement->sous_famille) ?></strong></li><?php endif; ?>
            </ul>
			<?php if ($etablissement->comptabilite->adresse): ?>
			<h2>Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :</h2>
			<ul>
				<?php if($etablissement->comptabilite->adresse): ?><li>Adresse : <strong><?php echo $etablissement->comptabilite->adresse ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->code_postal): ?><li>CP : <strong><?php echo $etablissement->comptabilite->code_postal ?></strong></li><?php endif; ?>
				<?php if($etablissement->comptabilite->commune): ?><li>ville : <strong><?php echo $etablissement->comptabilite->commune ?></strong></li><?php endif; ?>
			</ul>
            <?php endif; ?>
			<ul>
				<?php if($etablissement->service_douane): ?><li>Dépend du service des douanes de : <strong><?php echo $etablissement->service_douane ?></strong></li><?php endif; ?>
			</ul>
		</div>
	</div>
</div>