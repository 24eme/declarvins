<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
	<div id="principal" class="produit">
	<h1>
	Contrat Vrac &nbsp;
	<a class="btn_ajouter" href="<?php echo url_for('vrac_nouveau') ?>"></a>
	</h1>
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
	            if(!is_null($elt[VracHistoryView::VRAC_VIEW_STATUT])):
	                $statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
	                $vracid = preg_replace('/VRAC-/', '', $elt[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
	        ?>
	        <tr class="<?php echo $statusColor; ?>" >
	            <td class="type" ><span class="type_<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]; ?>"><?php echo ($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT])? typeProduit($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) : ''; ?></span></td>
		    	<td id="num_contrat"><?php echo substr($vracid,0,8)."&nbsp;".substr($vracid,8,  strlen($vracid)-1); ?></td>
	            <td>
	                  <ul>  
	                    <li>
	                      <?php echo ($elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID])? 
	                                    'Vendeur : '.$elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM]
	                                  : ''; ?>
	                    </li>
	                    <li>
	                      <?php echo ($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID])?
	                                    'Acheteur : '.$elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM]
	                                : ''; ?>
	                    </li>
	                    <li>
	                      <?php echo ($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]) ? 
	                                    'Mandataire : '.$elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM]
	                                 : ''; ?>
	                    </li>
	                 </ul>
	              </td>              
	              <td><?php echo ($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])? ConfigurationClient::getCurrent()->get($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat() : ''; ?></td>
	              <td>           
	                  <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0';
	                        echo ' / ';
	                        echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0';
	                   ?>
	              </td>
	        </tr>
	        <?php
	            endif;
	        endforeach;
	        ?>
	    </tbody>
	</table>
	</div>
	</div>
</section>