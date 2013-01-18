<?php $libelles = array("douane" => "Droits de circulation et de consommation",
                        "cvo" => "Cotisations interprofessionnelles"
                        ) ?>

<?php foreach ($daids->getDroits() as $typedroit => $droits) if (count($daids->droits->{$typedroit})): if ($typedroit == DAIDSDroits::DROIT_CVO && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {continue;}?>    
    <div class="tableau_ajouts_liquidations">
    <h2><?php echo $libelles[$typedroit] ?> <a href="" class="msg_aide" data-msg="help_popup_daids_validation_droit_<?php echo $typedroit; ?>" title="Message aide"></a></h2>
    <table class="tableau_recap">
    	<thead>
    		<tr>
    			<th><strong>Code</strong></th>
    			<th><strong>Volumes facturable</strong></th>
    			<th><strong>Taux</strong></th>
    			<th><strong>Droits à payer</strong></th>
    		</tr>
        </thead>
        <tbody>
             <?php 
             	$i=1; 
             	foreach ($droits->getDroitsWithVirtual() as $code => $droit):  
             		$i++;
    				$strong = ($droit->isTotal()) ? '<strong>' : '';
    				$estrong = ($droit->isTotal()) ? '</strong>' : '';
			?>
            <tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
            	<td><?php echo $strong.$droit->libelle.$estrong; ?></td>
        		<td class="<?php echo isVersionnerCssClass($droit, 'volume_taxe') ?>"><?php echo $strong ; echoFloat($droit->volume_taxe); echo $estrong;?>&nbsp;<span class="unite">hl</span></td>
        		<td class=""><?php if (!$droit->isVirtual()): echo $strong;echoFloat($droit->taux);echo $estrong; ?>&nbsp;<span class="unite">€/hl</span><?php endif; ?></td>
        	    <td class="<?php echo (isVersionner($droit, 'volume_taxe')) ? rectifierCssClass() : null ?>"><strong><?php echoFloat($droit->payable); ?>&nbsp;</strong>&nbsp;<span class="unite">€</span></td>
            </tr>
        	<?php endforeach; ?>
            </tbody>
    	</table>
    </div>
<?php endif; ?>
