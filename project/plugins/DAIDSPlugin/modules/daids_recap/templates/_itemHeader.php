<div id="colonne_intitules">
	<p class="couleur">Couleur</p>
	<?php if($config_lieu->hasCepage()): ?>
	<p class="cepage">Cépage</p>
	<?php endif; ?>
	<p class="label">Labels</p>
	

	<p>Stock théorique</p>
	<p>Vins logés dans votre chais</p>
	<p>Vins logés en propriété pour un tiers</p>
	<p>Vins logés chez un tiers</p>
	<p>Stock dans votre chais</p>
	
	<div class="groupe demarrage-ouvert" data-groupe-id="1">
		<p>Stock de votre propriété</p>
		<ul>
			<li>Dont réserve bloqué</li>
			<li>Dont vrac vendu non retiré</li>
			<li>Dont vrac libre à la vente</li>
			<li>Dont conditionné</li>
		</ul>
	</div>
	
	<p>Total des manquants ou excédents</p>
	
	<div class="groupe demarrage-ouvert" data-groupe-id="2">
		<p>Stock mensuel théorique</p>
		<ul>
			<li>Dont stock moyen de volume vinifié et stocké dans l'année</li>
			<li>&nbsp;</li>
			<li>Dont stock moyen de volume stocké non vinifié dans l'année</li>
			<li>&nbsp;</li>
			<li>Précisez le stock moyen de volume conditionné dans l'année</li>
			<li>&nbsp;</li>
		</ul>
	</div>
	
	<p>Total des pertes autorisées</p>
	<p>Manquants taxables éventuels</p>
	<p>Total des droits à payer</p>
</div>
