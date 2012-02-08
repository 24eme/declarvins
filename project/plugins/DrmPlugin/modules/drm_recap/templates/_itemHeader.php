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
			<li>Dont Vin bloqué</li>
			<li>Dont Vin warranté</li>
			<li>Dont Vin en instance</li>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="2">
		<p>Entrées</p>
		<ul>
			<li>Achats / Récolte / Agrément IGP/ Retour</li>
			<li>Replis / Changement de dénomination</li>
			<li>Déclassement</li>
			<li>Transfert de chai / Embouteillage</li>
			<li>Réintégration CRD</li>
		</ul>
	</div>
	
	<div class="groupe" data-groupe-id="3">
		<p>Sorties</p>
		<ul>
			<li>Vrac DAA/DAE</li>
			<li>Conditionné Export</li>
			<li>DSA / Tickets / Factures</li>
			<li>CRD France</li>
			<li>Conso Fam. / Analyses / Dégustation</li>
			<li>Pertes</li>
			<li>Non rev. / Déclassement</li>
			<li>Changement / Repli</li>
			<li>Transfert de chai / Embouteillage</li>
			<li>Lies</li>
		</ul>
	</div>
	
	<p class="stock_th_fin">Stock théorique fin de mois</p>
</div>