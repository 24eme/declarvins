<section id="contenu">
<h2>Récapitulatif</h2>
<p>
	Nom : <?php echo $contrat->getNom() ?><br />
	Prénom : <?php echo $contrat->getPrenom() ?><br /> 
	Fonction : <?php echo $contrat->getFonction() ?><br />
	Téléphone : <?php echo $contrat->getTelephone() ?><br />
	Fax : <?php echo $contrat->getFax() ?><br />
</p>
<br />
<strong>Etablissements :</strong>
<br />
<br />
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
	<br /><br />
	<a href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>">Modifier</a>
	<br /><br />
	<hr />
	<br />
<?php endforeach; ?>
<a href="<?php echo url_for('contrat_etablissement_confirmation') ?>">Valider</a> | <a href="<?php echo url_for('contrat_etablissement_nouveau') ?>">Nouveau</a>

</section>