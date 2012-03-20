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

	<?php include_partial('drm_export/pdfCss') ?>
</head>

<body>
	<?php include_partial('drm_export/pdfHeader', array('drm' => $drm)); ?>
	<?php include_partial('drm_export/pdfFooter'); ?>

	<?php $i=0; ?>
	<?php foreach($colonnes as $certification_key => $certification): ?>
		<?php $certification_config = $drm->declaration->certifications->get($certification_key)->getConfig() ?>
		<?php foreach($certification as $colonne): ?>
		<h2>Suivi des vins - <?php echo $certification_config->libelle ?></h2>
		<table class="recap">
			<?php include_partial('drm_export/pdfLine', array('libelle' => 'Produits',
															  'colonnes' => $colonne,
															  'cssclass_value' => 'libelle',
															  'partial' => 'drm_export/pdfLineItemProduitLibelle')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total début de mois',
																   'cssclass_libelle' => 'total',
															       'cssclass_value' => 'total',
															       'colonnes' => $colonne,
															       'hash' => 'total_debut_mois')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'stocks_debut')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total entrées', 
																   'cssclass_libelle' => 'total',
															  	   'cssclass_value' => 'total',
																   'colonnes' => $colonne,
																   'hash' => 'total_entrees')) ?>


			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'entrees')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total sorties',
															  'colonnes' => $colonne,
															  'cssclass_libelle' => 'total',
															  'cssclass_value' => 'total',
															  'hash' => 'total_sorties')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification_config,
																	'colonnes' => $colonne,
															  		'hash' => 'sorties')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total fin de mois',
																   'cssclass_libelle' => 'total',
															  	   'cssclass_value' => 'total',
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