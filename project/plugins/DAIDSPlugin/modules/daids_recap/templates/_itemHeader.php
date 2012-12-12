<div id="colonne_intitules">
	<p class="couleur">Couleur</p>
	
	<?php if($config_lieu->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>

	<p class="label">Labels</p>
	
	<p class="stock_th">Stock théorique au 31 Juillet - DRM <span class="unite">(hl)</span></p>
	
	<div class="groupe demarrage-ouvert bloque" data-groupe-id="1">
		<p>Inventaire des vins logés dans vos chais (<span class="unite">hl</span>)</p>
		<ul>
			<li>Vins de la propriété (<span class="unite">hl</span>)</li>
			<li>Vins logés pour un tiers (<span class="unite">hl</span>)</li>
		</ul>
	</div>
	
	<div class="groupe demarrage-ouvert bloque" data-groupe-id="2">
		<p>Stocks physiques de la propriété (<span class="unite">hl</span>)</p>
		<ul>
			<li>Vins logés dans vos chais (<span class="unite">hl</span>)</li>
			<li>Vins logés chez un tiers (<span class="unite">hl</span>)</li>
		</ul>
	</div>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="3">
		<p>Répartition des stocks physiques de la propriété (<span class="unite">hl</span>)</p>
		<ul>
			<li>Dont Réserve Bloquée</li>
			<li>Dont Vrac Vendu non retiré</li>
			<li>Dont Conditionné</li>
			<li>Dont Vrac libre à la vente</li>
		</ul>
	</div>

	<p class="total_manq_exce">Total Manquants ou Excédents (<span class="unite">hl</span>)</p>

	<p class="stock_th">Stock moyen mensuel</p>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="4">
		<p>dont Stock moyen volume vinifié et stocké dans l'année</p>
		<ul>
			<?php foreach ($configurationDAIDS->stocks_moyen->vinifie as $node): ?>
			<li><?php echo $node->libelle ?><?php if(count($configurationDAIDS->stocks_moyen->vinifie) <= 1): ?> (taux <?php echo $node->taux ?>%)<?php endif; ?></li>
			<?php endforeach; ?>
			<?php if(count($configurationDAIDS->stocks_moyen->vinifie) > 1): ?>
			<li>Volume forfaitaire effectif</li>
			<?php endif; ?>
		</ul>
	</div>

	<p>dont Stock moyen volume stocké non vinifié (CGI)</p>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="5">
		<p>Volume conditionné dans l'année</p>
		<ul>
			<?php foreach ($configurationDAIDS->stocks_moyen->conditionne as $node): ?>
			<li><?php echo $node->libelle ?><?php if(count($configurationDAIDS->stocks_moyen->conditionne) <= 1): ?> (taux <?php echo $node->taux ?>%)<?php endif; ?></li>
			<?php endforeach; ?>
			<?php if(count($configurationDAIDS->stocks_moyen->conditionne) > 1): ?>
			<li>Volume forfaitaire effectif</li>
			<?php endif; ?>
		</ul>
	</div>

	<p class="total_pertes">Total Pertes Autorisée (<span class="unite">hl</span>)</p>

	<p class="manquants_taxables">Volume Manquants taxables (<span class="unite">hl</span>)</p>

	<p>Total Droits à payer</p>
</div>
