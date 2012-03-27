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
	<?php foreach($drm->declaration->certifications as $certification_key => $certification): ?>
		<?php while($pagers_volume[$certification_key]->getPage() <= $pagers_volume[$certification_key]->getLastPage() || 
					$pagers_vrac[$certification_key]->getPage() <= $pagers_vrac[$certification_key]->getLastPage()): ?>

		<?php $colonnes = $pagers_volume[$certification_key]->getResults(); ?>
		<h2>Suivi des vins - <?php echo $certification->getConfig()->libelle ?></h2>
		<table class="recap">
			<?php include_partial('drm_export/pdfLine', array('libelle' => 'Code produit',
															  'colonnes' => $colonnes,
															  'cssclass_value' => 'libelle',
															  'partial' => 'drm_export/pdfLineVolumeItemProduitLibelle')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total début de mois',
																   'cssclass_libelle' => 'total',
															       'cssclass_value' => 'total',
															       'colonnes' => $colonnes,
															       'hash' => 'total_debut_mois')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																	'colonnes' => $colonnes,
															  		'hash' => 'stocks_debut')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total entrées', 
																   'cssclass_libelle' => 'total',
															  	   'cssclass_value' => 'total',
																   'colonnes' => $colonnes,
																   'hash' => 'total_entrees')) ?>


			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																	'colonnes' => $colonnes,
															  		'hash' => 'entrees')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total sorties',
															  'colonnes' => $colonnes,
															  'cssclass_libelle' => 'total',
															  'cssclass_value' => 'total',
															  'hash' => 'total_sorties')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																	'colonnes' => $colonnes,
															  		'hash' => 'sorties')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total fin de mois',
																   'cssclass_libelle' => 'total',
															  	   'cssclass_value' => 'total',
															  	   'colonnes' => $colonnes,
															  	   'hash' => 'total')) ?>

			<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																	'colonnes' => $colonnes,
															  		'hash' => 'stocks_fin')) ?>

		</table>

		<?php $colonnes = $pagers_vrac[$certification_key]->getResults(); ?>
		<h2>Contrats vrac - <?php echo $certification->getConfig()->libelle ?></h2>
		<table class="recap">
			<?php include_partial('drm_export/pdfLine', array('libelle' => 'Code produit',
															  'colonnes' => $colonnes,
															  'cssclass_value' => 'libelle',
															  'partial' => 'drm_export/pdfLineVracItemProduitLibelle')) ?>

			<?php include_partial('drm_export/pdfLine', array('libelle' => 'N° de contrat', 
																   'colonnes' => $colonnes,
																   'cssclass_libelle' => 'detail',
															  	   'cssclass_value' => 'detail',
																   'partial' => 'drm_export/pdfLineVracItemNoContrat')) ?>

			<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Volume',
															  	   'colonnes' => $colonnes,
															  	   'cssclass_libelle' => 'detail',
															  	   'cssclass_value' => 'detail',
															  	   'hash' => 'volume')) ?>


			
		</table>

		<?php $pagers_volume[$certification_key]->gotoNextPage(); ?>
		<?php $pagers_vrac[$certification_key]->gotoNextPage(); ?>
		<hr />
		<?php endwhile; ?>
	<?php endforeach; ?>

	<?php foreach($drm->declaration->certifications as $certification_key => $certification): ?>
	<h2>Code produits - <?php echo $certification->getConfig()->libelle ?></h2>
	<table class="legende">
	<?php foreach($details[$certification_key] as $detail): ?>
	<tr>
		<th><?php echo produitCodeFromDetail($detail) ?></th>
		<td><?php echo produitLibelleFromDetail($detail) ?></td>
	</tr>
	<?php endforeach; ?>
	</table>
	<?php endforeach; ?>



</body>
</html>