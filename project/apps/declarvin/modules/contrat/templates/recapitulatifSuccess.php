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
	<p>
		N° RCS / SIRET: <strong><?php echo $etablissement->siret ?></strong><br />
		N° CNI : <strong><?php echo $etablissement->cni ?></strong><br />
		N° CVI : <strong><?php echo $etablissement->cvi ?></strong><br />
		N° accises : <strong><?php echo $etablissement->no_accises ?></strong><br />
		Nom/Raison Sociale : <strong><?php echo $etablissement->raison_sociale ?></strong><br />
		Adresse : <strong><?php echo $etablissement->adresse ?></strong><br />
		CP : <strong><?php echo $etablissement->code_postal ?></strong><br />
		ville : <strong><?php echo $etablissement->commune ?></strong><br />
		tel : <strong><?php echo $etablissement->telephone ?></strong><br />
		fax : <strong><?php echo $etablissement->fax ?></strong><br />
		email : <strong><?php echo $etablissement->email ?></strong>
	</p>
	<p>Famille : <strong><?php echo $etablissement->famille ?></strong></p>
	<p>Sous-famille : <strong><?php echo $etablissement->sous_famille ?></strong></p>
	<?php if ($etablissement->comptabilite_adresse): ?>
	<p>
		Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<br />
		Adresse : <strong><?php echo $etablissement->comptabilite_adresse ?></strong><br />
		CP : <strong><?php echo $etablissement->comptabilite_code_postal ?></strong><br />
		ville : <strong><?php echo $etablissement->comptabilite_commune ?></strong>
	</p>
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