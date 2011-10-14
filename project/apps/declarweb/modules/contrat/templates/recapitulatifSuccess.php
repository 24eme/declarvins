<section id="contenu">
<div id="creation_compte">
<div class="col">
	<h2>Récapitulatif</h2>
	<p>Nom : <strong><?php echo $contrat->getNom() ?></strong></p>
	<p>Prénom : <strong><?php echo $contrat->getPrenom() ?></strong></p>
	<p>Fonction : <strong><?php echo $contrat->getFonction() ?></strong></p>
	<p>Téléphone : <strong><?php echo $contrat->getTelephone() ?></strong></p>
	<p>Fax : <strong><?php echo $contrat->getFax() ?></strong></p>
</div>
<div class="col">
	<h2>Etablissements :</h2>
	<?php 
	foreach ($contrat->etablissements as $etablissement): 
	?>
	<?php echo $etablissement->raison_sociale ?> (<?php echo $etablissement->siret ?><?php if ($etablissement->siret && $etablissement->cni): ?>/<?php endif; ?><?php echo $etablissement->cni ?>)
			<?php if ($etablissement->adresse): ?>
			<br />
			<?php echo $etablissement->adresse ?>
			<br />
			<?php echo $etablissement->code_postal ?> <?php echo $etablissement->commune ?>
			<br /><br />
			 <?php echo $etablissement->famille ?> <?php echo $etablissement->service_douane ?>
			<?php if ($etablissement->comptabilite_adresse || $etablissement->comptabilite_code_postal || $etablissement->comptabilite_commune): ?>
			<br /><br />
			Adresse comptabilité:
			<br />
			<?php echo $etablissement->comptabilite_adresse ?>
			<br />
			<?php echo $etablissement->comptabilite_code_postal ?> <?php echo $etablissement->comptabilite_commune ?>
			<?php endif; ?>
			<?php endif; ?>
		<div class="ligne_btn">
			<a href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>" class="button btn_ajouter" style="margin-left: 248px;">Modifier</a>
		</div>
		<hr />
	<?php endforeach; ?>
	<div class="ligne_btn">
		<a href="<?php echo url_for('contrat_etablissement_nouveau') ?>" class="button btn_ajouter">Nouveau</a>
		<a href="<?php echo url_for('contrat_etablissement_confirmation') ?>" class="button btn_valider">Valider</a>
	</div>
</div>
</div>
</section>