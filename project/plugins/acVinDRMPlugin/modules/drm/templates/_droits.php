<?php $libelles = array("douane" => "Droits de circulation et de consommation",
                        "cvo" => "Cotisations interprofessionnelles"
                        ) ?>

<?php foreach ($drm->getDroits() as $typedroit => $droits) if (count($drm->droits->{$typedroit})): ?>
    <?php /*if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $typedroit == "douane") {continue;}*/ ?>     
    <?php if (isset($hide_cvo) && $hide_cvo && $typedroit == "cvo") {continue;} ?>       
    <div class="tableau_ajouts_liquidations">
    <h2><?php echo $libelles[$typedroit] ?> <a href="" class="msg_aide" data-msg="help_popup_validation_droit_<?php echo $typedroit; ?>" title="Message aide"></a></h2>
    	<table class="tableau_recap">
            <thead>
    		<tr>
    			<th><strong>Code</strong></th>
    			<th><strong>Volumes "sortie" facturable</strong></th>
    			<th><strong>Volumes réintégrés</strong></th>
    			<th><strong>Taux</strong></th>
    			<th><strong>Montant à payer</strong></th>
    			<?php if ($drm->isPaiementAnnualise()): ?>
    			<th><strong>Report</strong></th>
    			<th><strong>Total cumulé</strong></th>    			
    			<?php endif; ?>
    		</tr>
             </thead>
             <tbody>
             <?php $i=1; foreach ($droits->getDroitsWithVirtual() as $code => $droit) :  $i++;
    				$strong = ($droit->isTotal()) ? '<strong>' : '';
    				$estrong = ($droit->isTotal()) ? '</strong>' : '';
			?>
            <tr<?php if($i%2!=0) echo ' class="alt"'; ?>>
	            <td><?php echo $strong.$droit->getLibelle().$estrong; ?></td>
	        	<td class="<?php echo isVersionnerCssClass($droit, 'volume_taxe') ?>"><?php echo $strong ; echoFloat($droit->volume_taxe); echo $estrong;?>&nbsp;<span class="unite">hl</span></td>
	        	<td class="<?php echo isVersionnerCssClass($droit, 'volume_reintegre') ?>"><?php echo $strong; echoFloat($droit->volume_reintegre); echo $estrong; ?>&nbsp;<span class="unite">hl</span></td>
	        	<td class=""><?php if (!$droit->isVirtual()): echo $strong; echoFloat($droit->taux); echo $estrong; ?>&nbsp;<span class="unite">€/hl</span><?php endif; ?></td>
	        	<td class="<?php echo (isVersionner($droit, 'volume_taxe') || isVersionner($droit, 'volume_reintegre')) ? versionnerCssClass() : null ?>"><?php echo $strong; echoFloat($droit->payable); echo $estrong; ?>&nbsp;<span class="unite">€</span></td>
	        	<?php if ($drm->isPaiementAnnualise()): ?>
	        	<td class="<?php echo (isVersionner($droit, 'volume_taxe') || isVersionner($droit, 'volume_reintegre')) ? versionnerCssClass() : null ?>"><?php if($droit->isTotal()): ?><strong><?php echoFloat($droit->reportable); ?>&nbsp;</strong><span class="unite">€</span><?php endif; ?></td>
	            <td class="<?php echo (isVersionner($droit, 'volume_taxe') || isVersionner($droit, 'volume_reintegre')) ? versionnerCssClass() : null ?>"><?php if($droit->isTotal()): ?><strong><?php echoFloat($droit->cumulable); ?></strong>&nbsp;<span class="unite">€</span><?php endif; ?>&nbsp;</td>
	            <?php endif; ?>
        	</tr>
        	<?php endforeach; ?>
            </tbody>
    	</table>
    </div>
    

<?php 
if ($circulation && $typedroit == "douane"): 
$droits_circulation = $circulation->getDroits();
?>
<div class="tableau_ajouts_liquidations">
    <table class="tableau_recap">
	<thead>
		<tr>
			<th class="vide"></th>
			<th class="libelle total"><strong>Total L387</strong></th>
			<th class="detail"><strong>L385 - Vins mousseux</strong></th>
			<th class="detail"><strong>L423 - AOC VDN</strong></th>
			<th class="detail"><strong>L425 - VDN non AOC</strong></th>
			<th class="detail"><strong>L440 - Alcools</strong></th>
			<th class="libelle detail"><strong>Autres taxes</strong></th>
			<th class="libelle total"><strong>Total tous produits</strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="detail" style="background: #494949 none repeat scroll 0 0; border-color: #494949; color: #fff; text-align: left;"><strong>Volume réintégré</strong></th>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L385'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L423'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L425'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L440'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="number blank">&nbsp;</td>
		</tr>	    
	    <tr class="alt">
			<th class="detail" style="background: #494949 none repeat scroll 0 0; border-color: #494949; color: #fff; text-align: left;"><strong>Volume taxé</strong></th>
			<td class="number detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L385'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L423'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L425'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail"><?php if (($val = $droits_circulation['L440'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) !== null): ?><?php echoFloat($val) ?> <span class="unite">hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="number blank">&nbsp;</td>
		</tr>	    
	    <tr>
			<th class="detail" style="background: #494949 none repeat scroll 0 0; border-color: #494949; color: #fff; text-align: left;"><strong>Taux des droits en vigueur</strong></th>
			<td class="total detail"><?php if (($val = $droits_circulation['L387'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L385'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L423'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L425'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if (($val = $droits_circulation['L440'][DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) !== null): ?><?php echoFloat($val) ?> <span class="unite">€/hl</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="number blank">&nbsp;</td>
		</tr>	    
	    <tr class="alt">
			<th class="total" style="background: #494949 none repeat scroll 0 0; border-color: #494949; color: #fff; text-align: left;"><strong>Droits à payer</strong></th>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L387', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L385', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L423', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L425', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getPayable('L440', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="total detail"><strong><?php echoFloat($circulation->getTotalPayable()) ?> <span class="unite">€</span></strong></td>
		</tr>
	    <tr>
			<th class="total" style="background: #494949 none repeat scroll 0 0; border-color: #494949; color: #fff; text-align: left;"><strong>Report du mois précédent</strong></th>
			<td class="total detail"><?php if(($val = $circulation->getReportable('L387', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getReportable('L385', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getReportable('L423', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getReportable('L425', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getReportable('L440', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="total detail"><strong><?php echoFloat($circulation->getTotalReportable()) ?> <span class="unite">€</span></strong></td>
		</tr>			
		<tr class="alt">
			<th class="total" style="background: #494949 none repeat scroll 0 0; border-color: #494949; color: #fff; text-align: left;"><strong>Total cumulé à reporter ou à solder</strong></th>
			<td class="total detail"><?php if(($val = $circulation->getCumulable('L387', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getCumulable('L385', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getCumulable('L423', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getCumulable('L425', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="total detail"><?php if(($val = $circulation->getCumulable('L440', DRMDroitsCirculation::CERTIFICATION_TOTAL)) !== null): ?><?php echoFloat($val) ?> <span class="unite">€</span><?php else: ?>&nbsp;<?php endif; ?></td>
			<td class="number detail">&nbsp;</td>
			<td class="total detail"><strong><?php echoFloat($circulation->getTotalCumulable()) ?> <span class="unite">€</span></strong></td>
		</tr>		  
	</tbody>
</table>
    </div>
<?php endif; ?>
<?php endif; ?>
