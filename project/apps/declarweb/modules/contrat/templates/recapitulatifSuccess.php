<h1>Récapitulatif</h1>
<?php 
foreach ($contrat->etablissements as $etablissement): 
?>
	Raison sociale: <?php echo $etablissement->raison_sociale ?>
		<br />
		SIRET: <?php echo $etablissement->siret ?>
		<?php if ($etablissement->adresse): ?>
		<br />
		CNI: <?php echo $etablissement->cni ?>
		<br />
		CVI: <?php echo $etablissement->cvi ?>
		<br />
		Numéro accises: <?php echo $etablissement->no_accises ?>
		<br />
		Numéro TVA intracommunautaire: <?php echo $etablissement->no_tva_intracommunautaire ?>
		<br />
		Adresse: <?php echo $etablissement->adresse ?>
		<br />
		Code postal: <?php echo $etablissement->code_postal ?>
		<br />
		Commune: <?php echo $etablissement->commune ?>
		<br />
		Téléphone: <?php echo $etablissement->telephone ?>
		<br />
		Fax: <?php echo $etablissement->fax ?>
		<br />
		Email: <?php echo $etablissement->email ?>
		<br />
		Famille: <?php echo $etablissement->famille ?>
		<br />
		Sous famille: <?php echo $etablissement->sous_famille ?>
		<br />
		Service douane: <?php echo $etablissement->service_douane ?>
		<br />
		<?php if ($etablissement->comptabilite_adresse || $etablissement->comptabilite_code_postal || $etablissement->comptabilite_commune): ?>
		Comptabilité:
		<br />
		Adresse: <?php echo $etablissement->comptabilite_adresse ?>
		<br />
		Code postal: <?php echo $etablissement->comptabilite_code_postal ?>
		<br />
		Commune: <?php echo $etablissement->comptabilite_commune ?>
		<?php endif; ?>
		<?php endif; ?>
	<a href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) ?>">Modifier</a>
	<hr />
<?php endforeach; ?>
<a href="<?php echo url_for('contrat_etablissement_confirmation') ?>">Valider</a>