<div id="colonne_intitules">
	<p class="couleur">Couleur</p>
	<?php if($config_lieu->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>
	<p class="label">Labels</p>
	<p class="groupe" style="text-align: right;"><strong>T.A.V</strong>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_tav" title="Message aide"></a></p>
	<p class="groupe" style="text-align: right;"><strong>Premix</strong>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_premix" title="Message aide"></a></p>
	<?php if($acquittes): ?>
	<h1>Droits suspendus</h1>
	<?php endif; ?>
	<div class="groupe" data-groupe-id="1">
		<p>Stock théorique début de mois&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksDebut(false) as $key => $item): ?>
                    <li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockdebut_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe demarrage-ouvert" data-groupe-id="2">
		<p>Entrées&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksEntree(false) as $key => $item): ?>
			<li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="3">
		<p>Sorties&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php 
				$stockSorties = Configuration::getStocksSortie(false); 
    			unset($stockSorties['vrac_contrat']);
				foreach ($stockSorties as $key => $item): 
			?>
                        <li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<!-- <p class="stock_th_fin">Stock théorique fin de mois</p>  -->
	<div class="groupe demarrage-ouvert bloque" data-groupe-id="4">
		<p>Stock théorique fin de mois&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksFin(false) as $key => $item): ?>
			<li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockfin_<?php echo $key; ?>" title="Message aide"></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php if ($acquittes): ?>
	<h1>Droits acquittés</h1>
	<div class="groupe" data-groupe-id="5">
		<p>Stock théorique début de mois&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksDebut(true) as $key => $item): ?>
                    <li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockdebut_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="6">
		<p>Entrées&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksEntree(true) as $key => $item): ?>
			<li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="7">
		<p>Sorties&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php 
				$stockSorties = Configuration::getStocksSortie(true); 
				foreach ($stockSorties as $key => $item): 
			?>
                        <li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<!-- <p class="stock_th_fin">Stock théorique fin de mois</p>  -->
	<div class="groupe demarrage-ouvert bloque" data-groupe-id="8">
		<p>Stock théorique fin de mois&nbsp;(<span class="unite">hl</span>)</p>
		<ul>
			<?php foreach (Configuration::getStocksFin(true) as $key => $item): ?>
			<li><?php echo $item ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockfin_<?php echo $key; ?>" title="Message aide"></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>
