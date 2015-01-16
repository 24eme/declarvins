<?php $libelles = array("douane" => "Droits de circulation et de consommation",
                        "cvo" => "Cotisations interprofessionnelles"
                        ) ?>

<?php foreach ($drm->getDroits() as $typedroit => $droits) if (count($drm->droits->{$typedroit})): ?>
    <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER && $typedroit == "douane") {continue;} ?>       
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
	        	<td class="<?php echo (isVersionner($droit, 'volume_taxe') || isVersionner($droit, 'volume_reintegre')) ? rectifierCssClass() : null ?>"><?php echo $strong; echoFloat($droit->payable); echo $estrong; ?>&nbsp;<span class="unite">€</span></td>
	        	<?php if ($drm->isPaiementAnnualise()): ?>
	        	<td class="<?php echo (isVersionner($droit, 'volume_taxe') || isVersionner($droit, 'volume_reintegre')) ? rectifierCssClass() : null ?>"><?php if($droit->isTotal()): ?><strong><?php echoFloat($droit->report); ?>&nbsp;</strong><span class="unite">€</span><?php endif; ?></td>
	            <td class="<?php echo (isVersionner($droit, 'volume_taxe') || isVersionner($droit, 'volume_reintegre')) ? rectifierCssClass() : null ?>"><?php if($droit->isTotal()): ?><strong><?php echoFloat($droit->cumulable); ?></strong>&nbsp;<span class="unite">€</span><?php endif; ?>&nbsp;</td>
	            <?php endif; ?>
        	</tr>
        	<?php endforeach; ?>
            </tbody>
    	</table>
    </div>
<?php endif; ?>

<?php if ($droits_circulation && 1 == 2): ?>
<div class="tableau_ajouts_liquidations">
    <h2>Droits de circulation, de consommation et autres taxes  <a href="" class="msg_aide" data-msg="help_popup_validation_droit_douane" title="Message aide"></a></h2>
    	<table class="tableau_recap">
            <thead>
    		<tr>
    			<th><strong>Code</strong></th>
    			<th><strong>Volumes réintégrés</strong></th>
    			<th><strong>Volumes taxables</strong></th>
    			<th><strong>Taux en vigueur</strong></th>
    			<th><strong>Droits à payer</strong></th>
    		</tr>
             </thead>
             <tbody>
             <?php $i=1; foreach ($droits_circulation->getDroits() as $code => $droit) :  $i++; ?>
            <tr<?php if($i%2!=0) echo ' class="alt"'; ?>>
	            <td><?php echo $code ?></td>
	        	<td><?php echoFloat($droit[DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_REINTEGRATION]) ?>&nbsp;<span class="unite">hl</span></td>
	        	<td><?php echoFloat($droit[DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_VOLUME_TAXABLE]) ?>&nbsp;<span class="unite">hl</span></td>
	        	<td><span class="unite"><?php echoFloat($droit[DRMDroitsCirculation::CERTIFICATION_TOTAL][DRMDroitsCirculation::KEY_TAUX]) ?>€/hl</span></td>
	        	<td><span class="unite">€</span></td>
        	</tr>
        	<?php endforeach; ?>
            </tbody>
    	</table>
    </div>
<?php endif; ?>
