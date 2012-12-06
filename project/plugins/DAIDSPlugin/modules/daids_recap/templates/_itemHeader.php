<div id="colonne_intitules">
	<p class="couleur">Couleur</p>
	
	<?php if($config_lieu->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>

	<p class="label">Labels</p>
	
	<p class="stock_th">Stock théorique au 31 Juillet - DRM <span class="unite">(hl)</span></p>

	<p>Vins de la propriété dans votre chais (<span class="unite">hl</span>)</p>
	<p>Vins logés dans votre chais pour un tiers (<span class="unite">hl</span>)</p>
	<p>Vins logés chez un tiers (<span class="unite">hl</span>)</p>
	
	<p>Total vins logés dans votre chais (<span class="unite">hl</span>)</p>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="1">
		<p>Total Stock de votre propriété (<span class="unite">hl</span>)</p>
		<ul>
			<li>Dont Réserve Bloquée</li>
			<li>Dont Vrac Vendu non retiré</li>
			<li>Dont Vrac libre à la vente</li>
			<li>Dont Conditionné</li>
		</ul>
	</div>

	<p class="total_manq_exce">Total Manquants ou Excédents (<span class="unite">hl</span>)</p>

	<p class="stock_th">Stock moyen mensuel</p>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="2">
		<p>dont Stock moyen volume vinifié et soldé dans l'année</p>
		<ul>
			<?php foreach ($configurationDAIDS->stocks_moyen->vinifie as $node): ?>
			<li><?php echo $node->libelle ?> (<?php echo $node->taux ?>%)</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="3">
		<p>dont Stock moyen volume stocké non vinifié</p>
		<ul>
			<?php foreach ($configurationDAIDS->stocks_moyen->non_vinifie as $node): ?>
			<li><?php echo $node->libelle ?> (<?php echo $node->taux ?>%)</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="groupe demarrage-ouvert bloque" data-groupe-id="4">
		<p>Stock moyen volume conditionné dans l'année</p>
		<ul>
			<?php foreach ($configurationDAIDS->stocks_moyen->conditionne as $node): ?>
			<li><?php echo $node->libelle ?> (<?php echo $node->taux ?>%)</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<p class="total_pertes">Total Pertes Autorisée (<span class="unite">hl</span>)</p>

	<p class="manquants_taxables">Manquants taxables éventuels (<span class="unite">hl</span>)</p>

	<p>Total Droits à payer (avant régulation)</p>
	<p>Régulation, correction ou avoir</p>

	<p class="total_droits_final">Total droits à payer</p>
</div>
