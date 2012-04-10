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
	<script type="text/php">
		if (isset($pdf)) {
			$w = $pdf->get_width();
			$h = $pdf->get_height();
			$font = Font_Metrics::get_font("helvetica");
			$pdf->page_text($w / 2 - 4, $h - 13, "f {PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(0,0,0));
		}
	</script>
	<?php include_partial('drm_export/pdfHeader', array('drm' => $drm)); ?>
	<?php include_partial('drm_export/pdfFooter'); ?>

	<?php $i=0; ?>
	<?php foreach($drm->declaration->certifications as $certification_key => $certification): ?>
		<?php while($pagers_volume[$certification_key]->getPage() <= $pagers_volume[$certification_key]->getLastPage() || 
					$pagers_vrac[$certification_key]->getPage() <= $pagers_vrac[$certification_key]->getLastPage()): ?>

		<?php $colonnes = $pagers_volume[$certification_key]->getResults(); ?>
		<?php if(count($colonnes) > 0): ?>
			<h2>Suivi des vins - <?php echo $certification->getConfig()->libelle ?></h2>
			<table class="recap volumes bloc_bottom">
				<?php include_partial('drm_export/pdfLine', array('libelle' => 'Code produit',
																  'colonnes' => $colonnes,
																  'cssclass_value' => 'libelle',
																  'partial' => 'drm_export/pdfLineVolumeItemProduitLibelle')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total début de mois',
																	   'unite' => 'hl',
																	   'cssclass_libelle' => 'total',
																       'cssclass_value' => 'total',
																       'colonnes' => $colonnes,
																       'hash' => 'total_debut_mois')) ?>

				<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																		'colonnes' => $colonnes,
																  		'hash' => 'stocks_debut')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total entrées',
																	   'unite' => 'hl',
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'total_entrees')) ?>


				<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																		'colonnes' => $colonnes,
																  		'hash' => 'entrees')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total sorties',
																	   'unite' => 'hl',
																	   'colonnes' => $colonnes,
																	   'cssclass_libelle' => 'total',
																	   'cssclass_value' => 'total',
																	   'hash' => 'total_sorties')) ?>

				<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																		'colonnes' => $colonnes,
																  		'hash' => 'sorties')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total fin de mois',
																	   'unite' => 'hl',
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																  	   'colonnes' => $colonnes,
																  	   'hash' => 'total')) ?>

				<?php include_partial('drm_export/pdfLineDetail', array('certification_config' => $certification->getConfig(),
																		'colonnes' => $colonnes,
																  		'hash' => 'stocks_fin')) ?>

			</table>
		<?php endif; ?>

		<?php $colonnes = $pagers_vrac[$certification_key]->getResults(); ?>
		<?php if(count($colonnes) > 0): ?>
			<h2>Contrats vrac - <?php echo $certification->getConfig()->libelle ?></h2>
			<table class="recap volumes bloc_bottom">
				<?php include_partial('drm_export/pdfLine', array('libelle' => 'Code produit',
																  'colonnes' => $colonnes,
																  'cssclass_value' => 'libelle',
																  'partial' => 'drm_export/pdfLineVracItemProduitLibelle')) ?>

				<?php include_partial('drm_export/pdfLine', array('libelle' => 'N° de contrat', 
																	   'colonnes' => $colonnes,
																	   'cssclass_libelle' => 'detail',
																  	   'cssclass_value' => 'detail',
																  	   'method' => 'getKey')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Volume',
																  	   'colonnes' => $colonnes,
																  	   'unite' => 'hl',
																  	   'cssclass_libelle' => 'detail',
																  	   'cssclass_value' => 'detail',
																  	   'hash' => 'volume')) ?>


				
			</table>
		<?php endif; ?>

		<?php $pagers_volume[$certification_key]->gotoNextPage(); ?>
		<?php $pagers_vrac[$certification_key]->gotoNextPage(); ?>
		<hr />
		<?php endwhile; ?>
	<?php endforeach; ?>

	<table class="double_col bloc_bottom">
		<tr>
			<td class="col_left">
				<h2>Mouvements au cours du mois</h2>
				<p><strong>Documents prévalidés ou N° empreinte utilisés au cours du mois</strong></p>
				<p><strong>DAA</strong> du 1258 au 1260</p>
				<p><strong>DSA</strong> : Aucun</p>
				<p>
					<strong>A adhérer</strong> à EMCS / GAMMA (n° non nécessaires)
				</p>
			</td>
			<td class="col_right">
				<h2>Défaut d'apurement</h2>
				<p class="bloc_bottom">
					Défaut d'apurement à déclarer (Joindre relevé de non apurement et copie du DAA)
				</p>
				<h2>Caution</h2>
				<p>
					Oui, Organisme : CIVP<br />
				</p>
			</td>
		</tr>
	</table>

	<?php while($pager_droits_douane->getPage() <= $pager_droits_douane->getLastPage()): ?>
	<?php $colonnes = $pager_droits_douane->getResults(); ?>

	<h2>Droits de circulation et de consommation</h2>
	<table class="recap droits_douane bloc_bottom">
		<?php include_partial('drm_export/pdfLine', array('libelle' => '',
														  'colonnes' => $colonnes,
														  'cssclass_libelle' => 'vide',
														  'cssclass_value' => 'libelle',
														  'partial_cssclass_value' => 'drm_export/pdfLineDroitsDouaneItemIsTotalCss',
														  'method' => 'getLibelle')) ?>

	    <?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Volume réintégré',
															   'colonnes' => $colonnes,
															   'unite' => 'hl',
															   'cssclass_libelle' => 'detail',
															   'partial_cssclass_value' => 'drm_export/pdfLineDroitsDouaneItemIsTotalCss',
															   'hash' => 'volume_reintegre')) ?>

		<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Volume taxé',
															   'colonnes' => $colonnes,
															   'unite' => 'hl',
															   'cssclass_libelle' => 'detail',
															   'partial_cssclass_value' => 'drm_export/pdfLineDroitsDouaneItemIsTotalCss',
															   'hash' => 'volume_taxe')) ?>

		<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Taux des droits en vigueur',
															   'colonnes' => $colonnes,
															   'unite' => '€/hl',
															   'cssclass_libelle' => 'detail',
															   'partial_cssclass_value' => 'drm_export/pdfLineDroitsDouaneItemIsTotalCss',
															   'hash' => 'taux')) ?>

		<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Droits à payer',
															   'colonnes' => $colonnes,
															   'unite' => '€',
															   'cssclass_libelle' => 'total',
															   'cssclass_value' => 'total',
															   'hash' => 'payable')) ?>
		<?php if ($drm->isPaiementAnnualise()): ?>

		<?php include_partial('drm_export/pdfLine', array('libelle' => 'Report du mois précédent',
															   'colonnes' => $colonnes,
															   'cssclass_libelle' => 'total',
															   'cssclass_value' => 'total',
															   'partial' => 'drm_export/pdfLineReport')) ?>

		<?php include_partial('drm_export/pdfLine', array('libelle' => 'Total cumulé à reporter ou à solder',
															   'colonnes' => $colonnes,
															   'cssclass_libelle' => 'total',
															   'cssclass_value' => 'total',
															   'partial' => 'drm_export/pdfLineCumul')) ?>
		<?php endif; ?>  
	</table>
	<?php $pager_droits_douane->gotoNextPage(); ?>
	<?php endwhile; ?>

	<div class="bloc_bottom">
	<h2>Paiement des droits de circulation</h2>
	<p><strong>Echéance de paiement</strong> : Mensuel</p>
	<p><strong>Mode de paiement</strong> : Chèque</p>
	</div>

	<table class="double_col bloc_bottom">
		<tr>
			<td class="col_left">
				<h2>Cadre reservé à l'administration des douanes</h2>
				<p>Date de récéption / Cachet dateur : <br /><br/><br/>
				<p>N° de déclaration GILDA : <br /><br/><br/></p>
			</td>
			<td class="col_right">
				<h2>Déclaration établie </h2>
				<p><strong>le : 01/04/2012</strong></p>
				<p>via l'application Déclarvin</p>
			</td>
		</tr>
	</table>

	<hr />
	<div class="legende">
	<?php foreach($drm->declaration->certifications as $certification_key => $certification): ?>
		<h2>Codes produit - <?php echo $certification->getConfig()->libelle ?></h2>
		<table class="codes_produit">
		<?php while($pagers_code[$certification_key]->getPage() <= $pagers_code[$certification_key]->getLastPage()): ?>
		<?php $colonnes = $pagers_code[$certification_key]->getResults(); ?>
		<?php if(count($colonnes) > 0): ?>
			<tr>
				<?php foreach($colonnes as $item): ?>
				<?php if($item): ?>
				<td>
					<strong><?php echo strtoupper($item->getConfig()->getCodes()) ?></strong>
		   			<span><?php echo produitLibelle($item->getConfig()->getLibelles(), array(), "%a% %l% %co% %ce% %m%") ?></span>
				</td>
				<?php endif; ?>
				<?php endforeach; ?>
			</tr>
		<?php endif; ?>
		<?php $pagers_code[$certification_key]->gotoNextPage(); ?>
		<?php endwhile; ?>
		</table>
	<?php endforeach; ?>
	</div>
</body>
</html>