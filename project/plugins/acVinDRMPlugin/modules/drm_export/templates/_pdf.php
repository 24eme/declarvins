<?php use_helper('Float'); ?>
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
			$pdf->page_text($w / 2 - 4, $h - 13, "{PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(0,0,0));
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
	    						  								  'counter' => 1,
																  'colonnes' => $colonnes,
																  'cssclass_value' => 'libelle',
																  'partial' => 'drm_export/pdfLineVolumeItemProduitLibelle')) ?>
																  
				<?php include_partial('drm_export/pdfLine', array('libelle' => 'Labels',
	    						  								  'counter' => '',
																  'colonnes' => $colonnes,
																  'cssclass_value' => 'labels',
																  'partial' => 'drm_export/pdfLineProduitLabels')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Stock début de mois',
																	   'unite' => 'hl',
	    						  								       'counter' => 2,
																	   'cssclass_libelle' => 'total',
																       'cssclass_value' => 'total',
																       'colonnes' => $colonnes,
																       'hash' => 'total_debut_mois')) ?>

				<?php include_partial('drm_export/pdfLineDetail', array('stocks' => Configuration::getStocksDebut(),
	    						  								        'counter' => 2,
																		'colonnes' => $colonnes,
																  		'hash' => 'stocks_debut'))?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total entrées',
																	   'unite' => 'hl',
	    						  								       'counter' => 3,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																	   'colonnes' => $colonnes,
																	   'hash' => 'total_entrees')) ?>


				<?php include_partial('drm_export/pdfLineDetail', array('stocks' => Configuration::getStocksEntree(),
	    						  								        'counter' => 3,
																		'colonnes' => $colonnes,
																  		'hash' => 'entrees')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Total sorties',
																	   'unite' => 'hl',
	    						  								       'counter' => 4,
																	   'colonnes' => $colonnes,
																	   'cssclass_libelle' => 'total',
																	   'cssclass_value' => 'total',
																	   'hash' => 'total_sorties')) ?>

				<?php $stockSorties = Configuration::getStocksSortie(); unset($stockSorties['vrac_contrat']); include_partial('drm_export/pdfLineDetail', array('stocks' => $stockSorties,
	    						  								        'counter' => 4,
																		'colonnes' => $colonnes,
																  		'hash' => 'sorties')) ?>

				<?php include_partial('drm_export/pdfLineFloat', array('libelle' => 'Stock fin de mois',
																	   'unite' => 'hl',
	    						  								       'counter' => 5,
																	   'cssclass_libelle' => 'total',
																  	   'cssclass_value' => 'total',
																  	   'colonnes' => $colonnes,
																  	   'hash' => 'total')) ?>

				<?php include_partial('drm_export/pdfLineDetail', array('stocks' => Configuration::getStocksFin(),
	    						  								        'counter' => 5,
																		'colonnes' => $colonnes,
																  		'hash' => 'stocks_fin')) ?>

			</table>
		<?php endif; ?>
		<?php $colonnes = $pagers_vrac[$certification_key]->getResults(); ?>
		<?php if(count($colonnes) > 0): ?>
			<h2>Contrats vrac - <?php echo $certification->getConfig()->libelle ?></h2>
			<table class="recap volumes bloc_bottom" id="recap_contrat">
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
		<hr />
		<?php $pagers_volume[$certification_key]->gotoNextPage(); ?>
		<?php $pagers_vrac[$certification_key]->gotoNextPage(); ?>
		<?php endwhile; ?>
	<?php endforeach; ?>
	
	
	<?php if (!$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>	
	<?php if($drm->declaration->hasMouvement() && !$drm->declaration->hasStockEpuise()): ?>
	<?php else: ?>
		<div class="bloc_bottom">
			<h2>Suivi des vins</h2>
			<?php if($drm->declaration->hasMouvement()): ?>
	            <p>Pas de mouvement pour l'ensemble des produits</p>
	        <?php endif; ?>
	        <?php if($drm->declaration->hasStockEpuise()): ?>
	            <p>Stock épuisé pour l'ensemble des produits</p>
	        <?php endif; ?>
    	</div>
	<?php endif; ?>

	<table class="double_col bloc_bottom">
		<tr>
			<td class="col_left">
				<h2>Mouvements au cours du mois</h2>
				<p><strong>Documents prévalidés ou N° empreinte utilisés au cours du mois</strong></p>
				<p>
					<strong>DAA</strong> 
					<?php if($drm->declaratif->daa->debut > 0 || $drm->declaratif->daa->debut > 0): ?>
					du <?php echo $drm->declaratif->daa->debut ?> au <?php echo $drm->declaratif->daa->fin ?>
					<?php else: ?>
					Aucun
					<?php endif; ?>
				</p>
				<p>
					<strong>DSA</strong>
					<?php if($drm->declaratif->dsa->debut > 0 || $drm->declaratif->dsa->debut > 0): ?>
					du <?php echo $drm->declaratif->dsa->debut ?> au <?php echo $drm->declaratif->dsa->fin ?>
					<?php else: ?>
					Aucun
					<?php endif; ?>
				</p>
				<p>
					Adhésion à <strong>EMCS / GAMMA</strong> : <?php if($drm->declaratif->adhesion_emcs_gamma): ?>Oui<?php else: ?>Non<?php endif; ?>
				</p>
			</td>
			<td class="col_right">
				<h2>Défaut d'apurement</h2>
				<p class="bloc_bottom">
					<?php if($drm->declaratif->defaut_apurement): ?>
						Défaut d'apurement à déclarer (joindre le relevé de non apurement ou copie du DAA)
					<?php else: ?>
						Pas de défaut d'apurement
					<?php endif; ?>		
					
				</p>
				<h2>Caution</h2>
				<p>
					<?php if($drm->declaratif->caution->dispense): ?>
						Dispensé
					<?php else: ?>
						Oui, Organisme : <?php echo $drm->declaratif->caution->organisme ?>
					<?php endif; ?>
				</p>
			</td>
		</tr>
	</table>

	
<?php if ($drm->valide->date_saisie): ?>

		<h2>Droits de circulation, de consommation et autres taxes</h2>

	
	
	
	
	
	
	
	
	
<?php $circulation = new DRMDroitsCirculation($drm->getRawValue()); $droits_circulation = $circulation->getDroits(); ?>

<table class="recap droits_douane bloc_bottom">
	<tbody>
		<tr>
			<td class="counter counterNone">&nbsp;</td>
			<th class="vide"></th>
			<td class="detail">L387 - AOP (AOC)</td>
			<td class="detail">L387 - IGP (VdP)</td>
			<td class="detail">L387 - Sans IG (VdT)</td>
		</tr>	    
	    <tr>
			<td class="counter">6a</td>
			<th class="detail">Volume réintégré</th>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_AOP][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_IGP][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_VINSSANSIG][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
		</tr>	    
	    <tr>
			<td class="counter">6b</td>
			<th class="detail">Volume taxé</th>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_AOP][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_IGP][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_VINSSANSIG][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
		</tr>	  
	</tbody>
</table>
<table class="recap droits_douane bloc_bottom">
	<tbody>
		<tr>
			<td class="counter counterNone">&nbsp;</td>
			<th class="vide"></th>
			<td class="libelle total">Total L387</td>
			<td class="detail">L385 - Vins mousseux</td>
			<td class="detail">L423 - AOC VDN</td>
			<td class="detail">L425 - VDN non AOC</td>
			<td class="detail">L440 - Alcools</td>
			<td class="libelle detail">Autres taxes</td>
			<td class="libelle total">Total tous produits</td>
		</tr>
		<tr>
			<td class="counter">6a</td>
			<th class="detail">Volume réintégré</th>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L385'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L423'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L425'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L440'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="number blank">&nbsp;</td>
		</tr>	    
	    <tr>
			<td class="counter">6b</td>
			<th class="detail">Volume taxé</th>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L385'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L423'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L425'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L440'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="number blank">&nbsp;</td>
		</tr>	    
	    <tr>
			<td class="counter">6c</td>
			<th class="detail">Taux des droits en vigueur</th>
			<td class="total detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L385'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L423'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L425'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L440'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="number blank">&nbsp;</td>
		</tr>	    
	    <tr>
			<td class="counter">6d</td>
			<th class="total">Droits à payer</th>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L387', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L385', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L423', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L425', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L440', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoLongFloatFr($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="total detail"><?php echoLongFloatFr($circulation->getTotalPayable()) ?> <span class="unite">€</span></td>
		</tr>
	    <tr>
			<td class="counter">6e</td>
			<th class="total">Report du mois précédent</th>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
		</tr>			
		<tr>
			<td class="counter">6f</td>
			<th class="total">Total cumulé à reporter ou à solder</th>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
			<td class="number detail">&nbsp;</td>
		</tr>		  
	</tbody>
</table>
	
	
	
	
	
	
	
	
	
	
	<div class="bloc_bottom">
		<h2>Paiement des droits de circulation</h2>
		<p><strong>Echéance de paiement</strong> : <?php echo $drm->declaratif->paiement->douane->frequence ?></p>
		<p><strong>Mode de paiement</strong> : <?php echo $drm->declaratif->paiement->douane->moyen ?></p>
	</div>

	<table class="double_col bloc_bottom">
		<tr>
			<td class="col_left">
				<h2>Cadre reservé à l'administration des douanes</h2>
				<p>Date de récéption / Cachet dateur : <br /><br/><br/><br/></p>
				<p>N° de déclaration GILDA : <br /><br/><br/><br/></p>
			</td>
			<td class="col_right">
				<h2>Déclaration établie </h2>
				<p>le <strong><?php echo $drm->getEuValideDate(); ?></strong>, via l'application Déclarvin<br /></p>
				<p><strong>Signature et cachet du déclarant</strong> :</p>
				<p id="signature"><br /><br/><br/><br /><br /><br /></p>
			</td>
		</tr>
	</table>
<hr />
<?php endif; //si pas validée ?>
	<?php endif; ?>

</body>
</html>
