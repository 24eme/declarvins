<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
	<title>DAI/DS | Vins de Provence</title>

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
	<?php include_partial('daids_export/pdfHeader', array('daids' => $daids)); ?>
	<?php include_partial('daids_export/pdfFooter'); ?>

	<?php $i=0; ?>
	<?php foreach($daids->declaration->certifications as $certification_key => $certification): ?>
		<?php while($pagers_volume[$certification_key]->getPage() <= $pagers_volume[$certification_key]->getLastPage()): ?>

		<?php $colonnes = $pagers_volume[$certification_key]->getResults(); ?>
		<?php if(count($colonnes) > 0): ?>
			<h2>Suivi des vins - <?php echo $certification->getConfig()->libelle ?></h2>
			<table class="recap volumes bloc_bottom">
				<?php include_partial('daids_export/pdfLine', array('libelle' => 'Code produit',
	    						  								  'counter' => 1,
																  'colonnes' => $colonnes,
																  'cssclass_value' => 'libelle',
																  'partial' => 'daids_export/pdfLineVolumeItemProduitLibelle')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Stock théorique au 31 Juillet',
																	   'unite' => 'hl',
	    						  								       'counter' => 2,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'stock_theorique')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Total vins logés dans votre chais',
																	   'unite' => 'hl',
	    						  								       'counter' => 3,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'stock_chais')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Total Stock de votre propriété',
																	   'unite' => 'hl',
	    						  								       'counter' => 4,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'stock_propriete')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Total Manquants ou Excédents',
																	   'unite' => 'hl',
	    						  								       'counter' => 5,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'total_manquants_excedents')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Total Pertes Autorisée',
																	   'unite' => 'hl',
	    						  								       'counter' => 6,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'total_pertes_autorisees')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Manquants taxables éventuels',
																	   'unite' => 'hl',
	    						  								       'counter' => 7,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'total_manquants_taxables')) ?>

				<?php include_partial('daids_export/pdfLineFloat', array('libelle' => 'Total droits de circulation à payer',
																	   'unite' => '€',
	    						  								       'counter' => 8,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'total_douane')) ?>

			</table>
		<?php endif; ?>

		<?php $pagers_volume[$certification_key]->gotoNextPage(); ?>
		<hr />
		<?php endwhile; ?>
	<?php endforeach; ?>
	<h2>Vins de la propriété</h2>
	<table id="vins_propriete" class="triple_col bloc_bottom">
		<tr>
			<th><h2><?php echo $daids->entrepots->entrepot_a->libelle ?><?php if ($daids->entrepots->entrepot_a->principal):?> (principal)<?php endif; ?></h2></th>
			<th><h2><?php echo $daids->entrepots->entrepot_b->libelle ?><?php if ($daids->entrepots->entrepot_b->principal):?> (principal)<?php endif; ?></h2></th>
			<th><h2><?php echo $daids->entrepots->entrepot_c->libelle ?><?php if ($daids->entrepots->entrepot_c->principal):?> (principal)<?php endif; ?></h2></th>
		</tr>
		<tr>
			<td class="col_left">
				<p><?php echo $daids->entrepots->entrepot_a->commentaires ?></p>
			</td>
			<td class="col_center">
				<p><?php echo $daids->entrepots->entrepot_b->commentaires ?></p>
			</td>
			<td class="col_right">
				<p><?php echo $daids->entrepots->entrepot_c->commentaires ?></p>
			</td>
		</tr>
	</table>
	<hr />
	<?php if ($daids->valide->date_saisie): ?>
	<?php while($pager_droits_douane->getPage() <= $pager_droits_douane->getLastPage()): ?>
		<?php $colonnes = $pager_droits_douane->getResults(); ?>
		<h2>Droits de circulation et de consommation</h2>
		<table class="recap droits_douane bloc_bottom">
	    <?php include_partial('drm_export/pdfLine', array('libelle' => '',
	    						  'counter' => '&nbsp;',
	    						  'cssclass_counter' => 'counterNone',
							      'colonnes' => $colonnes,
							      'cssclass_libelle' => 'vide',
							      'cssclass_value' => 'libelle',
							      'partial_cssclass_value' => 'daids_export/pdfLineDroitsDouaneItemIsTotalCss',
							      'method' => 'getLibelle')) ?>
	    
	    <?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Volume taxé',
	    						   'counter' => 'b',
								   'colonnes' => $colonnes,
								   'unite' => 'hl',
								   'cssclass_libelle' => 'detail',
								   'partial_cssclass_value' => 'daids_export/pdfLineDroitsDouaneItemIsTotalCss',
								   'hash' => 'volume_taxe')) ?>
	    
	    <?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Taux des droits en vigueur',
	    						   'counter' => 'c',
								   'colonnes' => $colonnes,
								   'unite' => '€/hl',
								   'cssclass_libelle' => 'detail',
								   'partial_cssclass_value' => 'daids_export/pdfLineDroitsDouaneItemIsTotalCss',
								   'hash' => 'taux')) ?>
	    
	    <?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Droits à payer',
	    						   'counter' => 'd',
								   'colonnes' => $colonnes,
								   'unite' => '€',
								   'cssclass_libelle' => 'total',
								   'cssclass_value' => 'total',
								   'hash' => 'payable')) ?>
  
		</table>
		<?php $pager_droits_douane->gotoNextPage(); ?>
	<?php endwhile; ?>

	<table class="double_col bloc_bottom">
		<tr>
			<td class="col_left">
				<h2>Cadre reservé à l'administration des douanes</h2>
				<p>Date de récéption / Cachet dateur : <br /><br/><br/>
				<p>N° de déclaration GILDA : <br /><br/><br/></p>
			</td>
			<td class="col_right">
				<h2>Déclaration établie </h2>
				<p><strong>le : <?php echo $daids->getEuValideDate(); ?></strong></p>
				<p>via l'application Déclarvin</p>
			</td>
		</tr>
	</table>

	<?php endif; ?>
	<hr />
	<div class="legende">
	<?php foreach($daids->declaration->certifications as $certification_key => $certification): ?>
		<?php $i = 1; while($pagers_code[$certification_key]->getPage() <= $pagers_code[$certification_key]->getLastPage()): ?>
		<?php $colonnes = $pagers_code[$certification_key]->getResults(); ?>
		<?php if(count($colonnes) > 0): ?>
			<h2>Codes produit - <?php echo $certification->getConfig()->libelle ?></h2>
			<table>
				<?php $counter=0; foreach($colonnes as $key => $item): ?>
					<?php if($item): ?>
					<?php if ($counter == 0): ?>
					<tr>
					<?php elseif ($counter == ExportDAIDS::NB_COL_CODES): $counter = 0;?>
					</tr>
					<tr>
					<?php endif; ?>
					<td>
						<strong><?php echo strtoupper($item->getCode()) ?></strong>
			   			<span><?php echo $item->getConfig()->getLibelleFormat(array(), "%g% %a% %l% %co% %ce%") ?></span>
					</td>
					<?php endif; ?>
				<?php $counter++; endforeach; ?>
				</tr>
			</table>
			<?php if ($pagers_code[$certification_key]->getPage() != $pagers_code[$certification_key]->getLastPage()): ?>
			<hr />
			<?php endif; ?>
		<?php endif; $i++; ?>
		<?php $pagers_code[$certification_key]->gotoNextPage(); ?>
		<?php endwhile; ?>
	<?php endforeach; ?>
	</div>
</body>
</html>
