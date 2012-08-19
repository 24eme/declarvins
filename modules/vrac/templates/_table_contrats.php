<?php use_helper('Vrac'); ?>
<div class="tableau_ajouts_liquidations">
	<table id="tableau_recap">    
	    <thead>
	        <tr>
	            <th class="type">Type</th>
	            <th>N° Contrat</th>
	            <th>Soussignés</th>   
	            <th>Produit</th>
	            <th>Vol. enlevé. / Vol. prop.</th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php 
	        foreach ($vracs->rows as $value):   
	            $elt = $value->getRawValue()->value;
                $statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
                $vracid = preg_replace('/VRAC-/', '', $elt[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
	        ?>
	        <tr class="<?php echo $statusColor; ?>" >
	            <td class="type" >
	            	<span class="type_<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]; ?>">
	            		<?php echo ($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT])? typeProduit($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) : ''; ?>
	            	</span>
	            </td>
		    	<td id="num_contrat">
		    		<a href="<?php echo url_for("vrac_visualisation", array('numero_contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo substr($vracid,0,8)."&nbsp;".substr($vracid,8,  strlen($vracid)-1); ?></a>
		    	</td>
	            <td>
	                  <ul>  
	                    <li>
	                      <?php if($elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID]): ?>
	                      Vendeur :
		                      <a href="<?php echo url_for('vrac_etablissement', array('identifiant' => $elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID])) ?>"><?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM] ?></a>
		                  <?php endif; ?>
	                    </li>
	                    <li>
	                      <?php if($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID]): ?>
	                      Acheteur :
		                      <a href="<?php echo url_for('vrac_etablissement', array('identifiant' => $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID])) ?>"><?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM] ?></a>
		                  <?php endif; ?>
	                    </li>
	                    <li>
	                      <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]): ?>
	                      Acheteur :
		                      <a href="<?php echo url_for('vrac_etablissement', array('identifiant' => $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID])) ?>"><?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM] ?></a>
		                  <?php endif; ?>
	                    </li>
	                 </ul>
	              </td>              
	              <td>
	              	<?php echo ($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])? ConfigurationClient::getCurrent()->get($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat() : ''; ?>
	              </td>
	              <td>           
	                  <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0';
	                        echo ' / ';
	                        echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0';
	                   ?>
	              </td>
	        </tr>
	        <?php
	        endforeach;
	        ?>
	    </tbody>
	</table>
</div>
