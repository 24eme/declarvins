<?php foreach ($drm->getDroits() as $typedroit => $droits) : ?>
    <div class="tableau_ajouts_liquidations">
    <h2>Droits <?php echo $typedroit; ?> <a href="" class="msg_aide" data-msg="help_popup_validation_droit_<?php echo $typedroit; ?>" title="Message aide"></a></h2>
    	<table class="tableau_recap">
            <thead>
    		<tr>
    			<th><strong>Code</strong></th>
    			<th><strong>Volume taxe</strong></th>
    			<th><strong>Volume réintégré</strong></th>
    			<th><strong>Taux</strong></th>
    			<th><strong>Droits à payer</strong></th>
    			<?php if ($drm->isPaiementAnnualise()): ?>
    			<th><strong>Report</strong></th>
    			<th><strong>Total cumulé</strong></th>    			
    			<?php endif; ?>
    		</tr>
             </thead>
             <tbody>
             <?php foreach ($droits->getDroitsWithVirtual() as $code => $droit) :  
    $strong = ($droit->isTotal()) ? '<strong>' : '';
    $estrong = ($droit->isTotal()) ? '</strong>' : '';
?>
            <tr class="alt">
                    <td><?php echo $strong.$droit->getLibelle().$estrong; ?></td>
        	<td class="<?php echo isRectifierCssClass($droit, 'volume_taxe') ?>"><?php echo $strong ; echoFloat($droit->volume_taxe); echo $estrong;?></td>
        	<td class="<?php echo isRectifierCssClass($droit, 'volume_reintegre') ?>"><?php echo $strong; echoFloat($droit->volume_reintegre); echo $estrong; ?></td>
        	<td class=""><?php echo $strong; if (!$droit->isVirtual()) echoFloat($droit->taux); echo $estrong; ?></td>
        	<td class="<?php echo (isRectifier($droit, 'volume_taxe') || isRectifier($droit, 'volume_reintegre')) ? rectifierCssClass() : null ?>"><strong><?php echoFloat($droit->payable); ?>&nbsp;€</strong></td>
        			<?php if ($drm->isPaiementAnnualise()): ?>
        	<td class="<?php echo (isRectifier($droit, 'volume_taxe') || isRectifier($droit, 'volume_reintegre')) ? rectifierCssClass() : null ?>"><strong><?php if($droit->isTotal()): ?><?php echoFloat($droit->report); ?>&nbsp;€<?php endif; ?></strong></td>
        	<td class="<?php echo (isRectifier($droit, 'volume_taxe') || isRectifier($droit, 'volume_reintegre')) ? rectifierCssClass() : null ?>"><strong><?php if($droit->isTotal()): ?><?php echoFloat($droit->cumulable); ?>&nbsp;€<?php endif; ?></strong></td>
        			<?php endif; ?>
        		</tr>
        		<?php endforeach; ?>
            </tbody>
    	</table>
    </div>
<?php endforeach; ?>