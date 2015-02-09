<?php foreach ($prestations as $interpro => $produits): ?>
<h1>Prestations <?php echo $interpro ?></h1>
<div class="tableau_ajouts_liquidations">
    	<table class="tableau_recap">
            <thead>
    			<tr>
					<th style="text-align: center;"><strong>Produits</strong></th>
				</tr>
            </thead>
            <tbody>
	    	<?php $i = 0; foreach($produits as $produit): ?>
    		<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
				<td><?php echo $produit ?></td>
			</tr>
    		<?php $i++; endforeach; ?>
	    	</tbody>
    	</table>
</div>
<?php endforeach; ?>