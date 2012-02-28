<div id="colonne_intitules">
	<p class="couleur">Couleur</p>
	<?php if($config_appellation->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>
	<?php if($config_appellation->hasMillesime()): ?>
	<p class="millesime">Millésime</p>
	<?php endif; ?>
	<p class="label">Labels</p>
	
	<div class="groupe" data-groupe-id="1">
		<p>Stock théorique principal début de mois</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getStocksDebut() as $key => $value): ?>
			<li><?php echo $libelle_detail_ligne->stocks_debut->get($key) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="2">
		<p>Entrées</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getEntrees() as $key => $value): ?>
			<li><?php echo $libelle_detail_ligne->entrees->get($key) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="3">
		<p>Sorties</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getSorties() as $key => $value): ?>
			<li><?php echo $libelle_detail_ligne->sorties->get($key) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<!-- <p class="stock_th_fin">Stock théorique fin de mois</p>  -->
	<div class="groupe" data-groupe-id="4">
		<p>Stock théorique fin de mois</p>
		<ul>
			<?php foreach ($config_appellation->getCertification()->detail->getStocksFin() as $key => $value): ?>
			<li><?php echo $libelle_detail_ligne->stocks_debut->get($key) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>