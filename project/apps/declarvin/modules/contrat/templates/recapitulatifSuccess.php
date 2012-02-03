<section id="contenu">
	<div id="creation_compte">
		<h1>Récapitulatif</h1>
		<div id="recapitulatif">
			<div class="recap_perso">
				<p><span>Nom :</span> <strong><?php echo $contrat->getNom() ?></strong></p>
				<p><span>Prénom :</span> <strong><?php echo $contrat->getPrenom() ?></strong></p>
				<p><span>Fonction :</span> <strong><?php echo $contrat->getFonction() ?></strong></p>
				<p><span>Email :</span> <strong><?php echo $contrat->getCompteObject()->getEmail() ?></strong></p>
				<p><span>Téléphone :</span> <strong><?php echo $contrat->getTelephone() ?></strong></p>
				<p><span>Fax :</span> <strong><?php echo $contrat->getFax() ?></strong></p>
			</div>

			<?php 
			foreach ($contrat->etablissements as $etablissement): 
			?>
			<div class="recap_etablissement">
				<h2>Etablissement <?php echo $etablissement->getKey() + 1; ?> :</h2>
				<div class="col">
					<p><span>N° RCS / SIRET:</span> <strong><?php echo $etablissement->siret ?></strong></p>
					<p><span>N° CNI :</span> <strong><?php echo $etablissement->cni ?></strong></p>
					<p><span>N° CVI :</span> <strong><?php echo $etablissement->cvi ?></strong></p>
					<p><span>N° accises :</span> <strong><?php echo $etablissement->no_accises ?></strong></p>
					<p><span>Nom/Raison Sociale :</span> <strong><?php echo $etablissement->raison_sociale ?></strong></p>
					<p><span>Adresse :</span> <strong><?php echo $etablissement->adresse ?></strong></p>
					<p><span>CP :</span> <strong><?php echo $etablissement->code_postal ?></strong></p>
					<p><span>ville :</span> <strong><?php echo $etablissement->commune ?></strong></p>
					<p><span>tel :</span> <strong><?php echo $etablissement->telephone ?></strong></p>
					<p><span>fax :</span> <strong><?php echo $etablissement->fax ?></strong></p>
					<p><span>email :</span> <strong><?php echo $etablissement->email ?></strong></p>
				</div>
				<div class="col">
					<p><span>Famille :</span> <strong><?php echo $etablissement->famille ?></strong></p>
					<p><span>Sous-famille :</span> <strong><?php echo $etablissement->sous_famille ?></strong></p>
					<?php if ($etablissement->comptabilite_adresse): ?>
					<div class="adresse_comptabilite">
						<p>Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<p>
						<p><span>Adresse :</span> <strong><?php echo $etablissement->comptabilite_adresse ?></strong></p>
						<p><span>CP :</span> <strong><?php echo $etablissement->comptabilite_code_postal ?></strong></p>
						<p><span>ville :</span> <strong><?php echo $etablissement->comptabilite_commune ?></strong></p>	
					</div>
					<?php endif; ?>
				</div>
				<div class="ligne_btn">
					<a class="btn_ajouter" href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>">Modifier</a>
					<a class="btn_supprimer" href="<?php echo url_for('contrat_etablissement_suppression', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>" >Supprimer</a>
				</div>
			</div>
			<?php endforeach; ?>
			<div class="ligne_btn">
				<a href="<?php echo url_for('contrat_etablissement_nouveau') ?>" class="btn_ajouter">Nouveau</a>
				<a href="<?php echo url_for('contrat_etablissement_confirmation') ?>" class="btn_valider">Valider</a>
			</div>
		</div>
	</div>
</section>