<?php
use_helper('Vrac');
?>
<section id="principal">
	<div class="tableau_ajouts_liquidations">
		<table class="tableau_recap">    
		    <thead>
		        <tr class="odd">
		            <th><strong>Statut</strong></th>
		            <th><strong>N° Contrat</strong></th>
		            <th><strong>Acheteur</strong></th>
		            <th><strong>Vendeur</strong></th>
		            <th><strong>Mandataire</strong></th>
		            <th><strong>Type</strong></th>
		            <th><strong>Produit</strong></th>
		            <th><strong>Vol. prop.</strong></th>
		            <th><strong>Vol. enlv.</strong></th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php 
		        	$cpt = 1;
					foreach ($vracs->rows as $value):
						$cpt *= -1;
		            	$elt = $value->getRawValue()->value;
		        ?>
		        <tr<?php if($cpt > 0) echo ' class="alt"'; ?>>
		            <td>
		                <?php 
		                      $statusImg = statusImg($elt[0]);
		                      if($elt[0]):
		                ?>
		                <img alt="<?php echo $statusImg->alt; ?>" src="<?php echo $statusImg->src; ?>" />
		                <?php endif; ?>
		            </td>
			      <td><?php $vracid = preg_replace('/VRAC-/', '', $elt[1]); echo link_to($vracid, '@vrac_termine?numero_contrat='.$vracid); ?></td>
			      <td><?php echo ($elt[2]) ? link_to($elt[3], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[2])) : ''; ?></td>
			      <td><?php echo ($elt[4]) ? link_to($elt[5], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[4])) : ''; ?></td>
			      <td><?php echo ($elt[6]) ? link_to($elt[7], 'vrac/rechercheSoussigne?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[6])) : ''; ?></td>
		          <td><?php echo ($elt[8])? typeProduit($elt[8]) : ''; ?></td>
			      <td><?php echo ($elt[9])? ConfigurationClient::getCurrent()->get($elt[9])->libelleProduit() : ''; ?></td>
			      <td><?php echo $elt[10]; ?></td>
			      <td><?php echo $elt[11]; ?></td>
		        </tr>
		        <?php endforeach; ?>
		    </tbody>
		</table>    
	</div>
</section>