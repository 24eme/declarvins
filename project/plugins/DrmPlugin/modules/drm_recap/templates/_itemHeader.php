<div id="colonne_intitules">
	<p class="couleur">Couleur</p>
	<?php if($config_appellation->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>
	<p class="label">Labels</p>
	
	<div class="groupe" data-groupe-id="1">
		<p>Stock théorique principal début de mois</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getStocksDebut() as $key => $item): ?>
			<li><?php echo $item->getLibelle() ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockdebut_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe demarrage-ouvert" data-groupe-id="2">
		<p>Entrées</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getEntrees() as $key => $item): ?>
			<li><?php echo $item->getLibelle() ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="3">
		<p>Sorties</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getSorties() as $key => $item): ?>
                        <li><?php echo $item->getLibelle() ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_sorties_<?php echo $key; ?>" title="Message aide"></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<!-- <p class="stock_th_fin">Stock théorique fin de mois</p>  -->
	<div class="groupe demarrage-ouvert bloque" data-groupe-id="4">
		<p>Stock théorique fin de mois</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getStocksFin() as $key => $item): ?>
			<li><?php echo $item->getLibelle() ?>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_stockfin_<?php echo $key; ?>" title="Message aide"></a></
li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
