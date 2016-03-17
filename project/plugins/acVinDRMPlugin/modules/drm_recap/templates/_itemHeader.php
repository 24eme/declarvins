<div id="colonne_intitules<?php if ($acquittes): ?>_acq<?php endif; ?>">
	<p class="couleur">Couleur</p>
	<?php if($config_lieu->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>
	<p class="label">Labels</p>
	<p class="label" style="text-align: right;"><strong>T.A.V</strong>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_tav" title="Message aide"></a></p>
	
	<div class="groupe" data-groupe-id="<?php if ($acquittes): ?>5<?php else: ?>1<?php endif; ?>">
		<p>Stock théorique début de mois&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksDebut($acquittes) as $key => $item): ?>
                    <li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockdebut_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe demarrage-ouvert" data-groupe-id="<?php if ($acquittes): ?>6<?php else: ?>2<?php endif; ?>">
		<p>Entrées&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksEntree($acquittes) as $key => $item): ?>
			<li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="<?php if ($acquittes): ?>7<?php else: ?>3<?php endif; ?>">
		<p>Sorties&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php 
				$stockSorties = Configuration::getStocksSortie($acquittes); 
    			unset($stockSorties['vrac_contrat']);
				foreach ($stockSorties as $key => $item): 
			?>
                        <li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<!-- <p class="stock_th_fin">Stock théorique fin de mois</p>  -->
	<div class="groupe demarrage-ouvert bloque" data-groupe-id="<?php if ($acquittes): ?>8<?php else: ?>4<?php endif; ?>">
		<p>Stock théorique fin de mois&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksFin($acquittes) as $key => $item): ?>
			<li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockfin_<?php echo $key; ?>" title="Message aide"></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
