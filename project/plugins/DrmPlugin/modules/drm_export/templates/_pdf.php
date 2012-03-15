<?php use_helper('Float'); ?>
<?php use_helper('Produit'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
	<title>DRM | Vins de Provence</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Actualys" />
	<meta name="Description" content="" /> 
	<meta name="Keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta name="Content-Language" content="fr-FR" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="copyright" content="Vins de Provence - 2011" />

	<style type="text/css">
	@page {
		margin: 0.5cm;
	}
	body {
		font-family: sans-serif;
		margin: 0.5cm 0;
		text-align: justify;
	}
	#header,
	#footer {
		position: fixed;
		left: 0;
		right: 0;
		color: #aaa;
		font-size: 0.9em;
	}
	#header {
		top: 0;
		border-bottom: 0.1pt solid #aaa;
	}
	#footer {
		bottom: 0;
		border-top: 0.1pt solid #aaa;
	}
	#header table,
	#footer table {
		width: 100%;
		border-collapse: collapse;
		border: none;
	}
	#header td,
	#footer td {
		padding: 0;
		width: 50%;
	}

	table.recap {
		border-collapse: collapse;
		font-size: 0.9em;
	}

	table.recap tr td, table.recap tr th {
		border: 1px solid #000;
		padding: 0;
		
	}

	table.recap tr th.detail {
		font-weight: normal;
	}

	table.recap tr td.vide {
		border: none;
	}

	table.recap tr th {
		width: 4.7cm;
		word-wrap: break-word;
		white-space: normal;
	}

	table.recap tr td {
		width: 4.8cm;
	}

	table.recap tr td.number {
		text-align: center;
	}

	.page-number {
		text-align: center;
	}
	.page-number:before {
		content: "Page " counter(page);
	}
	hr {
		page-break-after: always;
		border: 0;
	}
	</style>

</style>


</head>

<body>
	<?php include_partial('drm_export/pdfHeader'); ?>
	<?php include_partial('drm_export/pdfFooter'); ?>

	<?php $i=0; ?>
	<?php foreach($colonnes as $certification_key => $certification): ?>
		<?php $certification_config = $drm->declaration->certifications->get($certification_key)->getConfig() ?>
		<h2><?php echo $certification_config->libelle ?></h2>
		<?php foreach($certification as $colonne): ?>
		<table class="recap">

			<?php include_partial('drm_export/pdfLine', array('libelle' => 'Produit',
															  'colonnes' => $colonne,
															  'partial' => 'drm_export/pdfLineItemProduitLibelle')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total début de mois',
															  'colonnes' => $colonne,
															  'hash' => 'total_debut_mois')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'stocks_debut')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total entrées', 
																   'colonnes' => $colonne, 
																   'hash' => 'total_entrees')) ?>


			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'entrees')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total sorties',
															  'colonnes' => $colonne,
															  'hash' => 'total_sorties')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'sorties')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total fin de mois',
															  'colonnes' => $colonne,
															  'hash' => 'total')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'stocks_fin')) ?>

		</table>
		<hr />
		<?php endforeach; ?>
	<?php endforeach; ?>

</body>
</html>