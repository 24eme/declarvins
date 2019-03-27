<div class="tableau_ajouts_liquidations">
    	<table class="tableau_recap">
            <thead>
    			<tr>
					<th colspan="6" style="text-align: center;"><strong>Produit</strong></th>
			<th colspan="6"><strong>Configuration</strong></th>
		</tr>
		<tr>
			<th style="width:35px;"><strong>Cat.</strong></th>
			<th><strong>Genre</strong></th>
			<th><strong>Dénom.</strong></th>
			<th><strong>Lieu</strong></th>
			<th><strong>Couleur</strong></th>
			<th><strong>Cépage</strong></th>
			<th style="width:15px;" class="center"><strong>Labels</strong></th>
			<!--<th style="width:15px;" class="center"><strong>Dép.</strong></th> -->
			<th style="width:15px;" class="center"><strong>Douane</strong></th> 
			<th style="width:15px;" class="center"><strong>CVO</strong></th>
			<th style="width:15px;" class="center"><strong>Vrac</strong></th>    
			<!-- <th style="width:15px;" class="center"><strong>OIOC</strong></th>  -->
			<th style="width:15px;" class="center"><strong>Rep/Dec</strong></th>
			<th style="width:15px;" class="center"><strong>Presta.</strong></th>
				</tr>
            </thead>
            <tbody>
	    	<?php 
    		$i = 0; 
    		foreach($produits as $produit): 
    			$cvo = $produit->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO);
    			$douane = $produit->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_DOUANE);
    			//$departements = $produit->getCurrentDepartements();
    			$prestations = $produit->getCurrentPrestations();
    			$drmVrac = $produit->getCurrentDrmVrac();
    			//$organisme = $produit->getCurrentOrganisme();
    			$labels = $produit->getCurrentLabels();
    			$drmConf = $produit->getCurrentDefinitionDrm();
    			if ($cvo) {
    				$cvo = $cvo->getRawValue();
    				$cvoNoeud = key($cvo);
    				$cvo = current($cvo);
    			}
    			if ($douane) {
    				$douane = $douane->getRawValue();
    				$douaneNoeud = key($douane);
    				$douane = current($douane);
    			}
    			/*if ($departements) {
    				$departements = $departements->getRawValue();
    				$departementsNoeud = key($departements);
    				$departements = current($departements);
    			}*/
    			if ($prestations) {
    				$prestations = $prestations->getRawValue();
    				$prestationsNoeud = key($prestations);
    				$prestations = current($prestations);
    			}
    			if ($drmVrac) {
    				$drmVrac = $drmVrac->getRawValue();
    				$drmVracNoeud = key($drmVrac);
    				$drmVrac = current($drmVrac);
    			}
    			/*if ($organisme) {
    				$organisme = $organisme->getRawValue();
    				$organismeNoeud = key($organisme);
    				$organisme = current($organisme);
    			}*/
    			if ($labels) {
    				$labels = $labels->getRawValue();
    				$labelsNoeud = key($labels);
    				$labels = array_keys(current($labels));
    			}
    			if ($drmConf) {
    				$drmConf = $drmConf->getRawValue();
    				$drmConfNoeud = key($drmConf);
    				$drmConf = current($drmConf);
    			}
    	?>
    		<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
			<td>
				<a href="<?php echo url_for('configuration_produit_suppression', array('anivin' => $sf_request->getParameter('anivin', 0), 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression du produit?')">Supprimer</a>
				<a href="<?php echo url_for('configuration_produit_modification', array('anivin' => $sf_request->getParameter('anivin', 0), 'noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->getCertificationLibelle(); ?></a>
			</td>
			<td><a href="<?php echo url_for('configuration_produit_modification', array('anivin' => $sf_request->getParameter('anivin', 0), 'noeud' => 'genre', 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->getGenreLibelle(); ?></a></td>
			<td><a href="<?php echo url_for('configuration_produit_modification', array('anivin' => $sf_request->getParameter('anivin', 0), 'noeud' => 'appellation', 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->getAppellationLibelle(); ?></a></td>
			<td><a href="<?php echo url_for('configuration_produit_modification', array('anivin' => $sf_request->getParameter('anivin', 0), 'noeud' => 'lieu', 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->getLieuLibelle(); ?></a></td>
			<td><a href="<?php echo url_for('configuration_produit_modification', array('anivin' => $sf_request->getParameter('anivin', 0), 'noeud' => 'couleur', 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->getCouleurLibelle(); ?></a></td>
			<td><a href="<?php echo url_for('configuration_produit_modification', array('anivin' => $sf_request->getParameter('anivin', 0), 'noeud' => 'cepage', 'hash' => str_replace('/', '-', $produit->getHash()))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->getCepageLibelle(); ?></a><br /><p style="font-size: 85%; text-align:center;"><?php echo $produit->inao ?><?php if (!$produit->inao): ?><?php echo $produit->libelle_fiscal ?><?php endif; ?></p></td>
			
			<td class="center" title="<?php if (isset($labelsNoeud)): ?>Valeur définie au noeud <?php echo $labelsNoeud ?><?php endif; ?>">
				<?php if ($labels): ?><?php echo implode(', ', $labels); ?><?php endif; ?>
			</td>
			<td class="center" title="<?php if (isset($douaneNoeud)): ?>Valeur définie au noeud <?php echo $douaneNoeud ?><?php endif; ?>">
				<?php if ($douane): ?><?php echo $douane->taux; ?><br />(<?php echo $douane->code; ?>)<?php endif; ?>
			</td>
			<td class="center" title="<?php if (isset($cvoNoeud)): ?>Valeur définie au noeud <?php echo $cvoNoeud ?><?php endif; ?>">
				<?php if ($cvo): ?><?php echo $cvo->taux; ?><br />(<?php echo $cvo->code; ?>)<?php endif; ?>
			</td>
			<td class="center" title="<?php if (isset($drmVracNoeud)): ?>Valeur définie au noeud <?php echo $drmVracNoeud ?><?php endif; ?>">
				<?php if ($drmVrac): ?>oui<?php else: ?>non<?php endif; ?>
			</td>	
			<td class="center" title="<?php if (isset($drmConfNoeud)): ?>Valeur définie au noeud <?php echo $drmConfNoeud ?><?php endif; ?>">
				<?php if ($drmConf): ?>
				R : <?php echo ($drmConf->entree->repli)? 'E' : ''; ?><?php echo ($drmConf->sortie->repli)? 'S' : ''; ?><br />
				D : <?php echo ($drmConf->entree->declassement)? 'E' : ''; ?><?php echo ($drmConf->sortie->declassement)? 'S' : ''; ?>
				<?php endif; ?>
				</td>
			
			<td class="center" title="<?php if (isset($prestationsNoeud)): ?>Valeur définie au noeud <?php echo $prestationsNoeud ?><?php endif; ?>">
				<?php if ($prestations): ?><?php echo str_replace('INTERPRO-', '', implode(', ', $prestations)); ?><?php endif; ?>
			</td>
			</tr>
    	<?php $i++; endforeach; ?>
	    	</tbody>
    	</table>
    </div>