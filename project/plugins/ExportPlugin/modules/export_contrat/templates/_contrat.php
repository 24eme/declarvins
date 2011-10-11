<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
  </head>
  <body>
  <div>
	Déclaration Web N : <?php echo $contrat->no_contrat ?> (1)
	<br /><br />
	à un système de Déclarations Informatisé (DRM, DAI/DS, DR, Contrats...)<br />
	ET aux autres systèmes des Interprofessions : Intranet d'échange d'information...
	<br /><br />
	 (1) Numéro Interprofessionnel d'enregistrement de l'inscription
	<br /><br />
	Toutes les informations du présent formulaire sont obligatoires.
	<br /><br />
	DECLARANT<br />
	Je soussigné, <?php echo $contrat->nom ?>, <?php echo $contrat->prenom ?><br />
	Représentant les entreprises suivantes<br />
	Qualité (fonction) : <?php echo $contrat->fonction ?>
	<br /><br />
	Adresse des sociétés concernés<br />
	<?php foreach ($contrat->etablissements as $i => $etablissement): ?>
	Société <?php echo $i+1 ?><br />
	N RCS / SIRET: <?php echo $etablissement->siret ?><br />
	N Carte Nationale d'Identité pour les exploitants individuels : <?php echo $etablissement->cni ?><br />
	N CVI : <?php echo $etablissement->cvi ?><br />
	N accises : FR.<?php echo $etablissement->no_accises ?><br />
	Nom/Raison Sociale : <?php echo $etablissement->raison_sociale ?><br />
	Adresse : <?php echo $etablissement->adresse ?><br />
	CP : <?php echo $etablissement->code_postal ?><br />
	ville : <?php echo $etablissement->commune ?><br />
	tel : <?php echo $etablissement->telephone ?><br />
	fax : <?php echo $etablissement->fax ?><br />
	email : <?php echo $etablissement->email ?>
	<br /><br />
	Famille : <?php echo $etablissement->famille ?>
	<br /><br />
	Sous famille : <?php echo $etablissement->sous_famille ?>
	<br /><br />
	Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<br />
	Adresse : <?php echo $etablissement->comptabilite_adresse ?><br />
	CP : <?php echo $etablissement->comptabilite_code_postal ?><br />
	ville : <?php echo $etablissement->comptabilite_commune ?>
	<br /><br />
	Dépend du service des douanes de : <?php echo $etablissement->service_douane ?>
	<br /><br />
	<?php endforeach; ?>
	<br />
	Le cas échéant, logiciel utilisé pour la gestion de cave et transmission (DTI) des informations à "Déclarations Web":<br />
	(lister les logiciels testés et validés)<br />
	Société 3, 4... si besoin. A priori on ne devrait pas dépasser 3 sociétés dans la majorité des cas.
	<br /><br />
	Demande à bénéficier d'une connexion au système informatique "Déclaration Web", et donne mandat pour dépôt définitif aux Interprofessions (InterRhône, CIVP, InterVins SE) de me représenter en la personne de ses salariés habilités dans le cadre de la télédéclaration des informations relatives à mes déclarations auprès des douanes : DRM, DAI/DS, DR, DAA.
	<br /><br />
	Le présent mandat prend effet à compter de la date de signature, et demeure valable jusqu'à dénonciation par l'une ou l'autre des parties.
	<br /><br />
	INTERPROFESSIONS : InterRhône, CIVP, InterVins SE, en partenariat avec les douanes.<br />
	Interlocuteur en gestion :<br />
	-pour les opérateurs de Provence (départements 13, 83, 04, 06) ressortissants d'InterVins SE et du CIVP : CIVP.<br />
	-pour les opérateurs de Vallée du Rhône (départements 04, 05, 07, 26, 84) ressortissants d'InterVins SE et d'InterRhône : InterRhône.<br />
	-pour les opérateurs ressortissants d'InterVins SE uniquement (tous départements + 30): InterVins SE.<br />
	En cas de changement de la production, ou de l'adhésion d'organismes représentatifs de la production aux Interprofessions, les interlocuteurs de gestion peuvent évoluer.
	<br /><br />
	<?php echo include_partial('export_contrat/contrat_conditions') ?>
	<br /><br />
	Fait à, Le
	<br /><br />
	Le déclarant, 								
	<br /><br />
	Le représentant de "déclaration web" pour les 3 Interprofessions,
	<br /><br />
  </div>
  </body>
</html>