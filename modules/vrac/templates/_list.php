<?php use_helper('Vrac'); ?>
<div class="tableau_ajouts_liquidations">
	<table id="tableau_recap">    
	    <thead>
	        <tr>
	        	<th style="width: auto;">Statut</th>
	            <th class="type">Type</th>
	            <th>N° Contrat</th>
	            <th>Soussignés</th>   
	            <th>Produit</th>
	            <th>Vol. enlevé. / Vol. prop.</th>
	            <th>Prix total</th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($vracs->rows as $value): ?>
	        	<?php include_component('vrac', 'listItem', array('value' => $value->getRawValue(), 'etablissement' => $etablissement)) ?>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>
