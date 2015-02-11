<?php use_helper('Vrac'); ?>
<div class="tableau_ajouts_liquidations">
	<table id="tableau_recap" class="visualisation_contrat">    
	    <thead>
	        <tr>
	        	<th style="width: auto;">Statut<br /><a href="" class="msg_aide" data-msg="help_popup_vrac_statut" title="Message aide"></a></th>
	        	<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <th>Mode de saisie</th>
                <?php endif; ?>
	            <th class="type">Type<br /><a href="" class="msg_aide" data-msg="help_popup_vrac_type" title="Message aide"></a></th>
	            <th>N° de Visa<br /><a href="" class="msg_aide" data-msg="help_popup_vrac_visa" title="Message aide"></a></th>
	            <th>Acheteur</th>   
	            <th>Vendeur</th>   
	            <th>Courtier</th>
	            <th>Produit</th>
	            <th>Vol. enlevé. / Vol. prop.</th>
	            <th>Prix (HT)</th>
	        </tr>
	    </thead>
	    <tbody>
	    	<?php 
	        	$libelles = Vrac::getModeDeSaisieLibelles();
	        	$isOperateur = $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR);
	        	foreach ($vracs_attente as $value):
	        		$elt = $value->value;
	        		if ($isOperateur && !$configurationProduit->exist($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])) {
	        			continue;
	        		}
					$acteur = null;
					$validated = false;
					$isProprietaire = false;
					$statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
					$vracid = $elt[VracHistoryView::VRAC_VIEW_NUM];
					$vraclibelle = $elt[VracHistoryView::VRAC_VIEW_NUM];
					if ($elt[VracHistoryView::VRAC_VERSION]) {
						$vracid .= '-'.$elt[VracHistoryView::VRAC_VERSION];
						if ($isOperateur) {
							$vraclibelle .= '-'.$elt[VracHistoryView::VRAC_VERSION];
						}
					}
					if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_ACHETEURID]) {
						$acteur = VracClient::VRAC_TYPE_ACHETEUR;
						$const = VracHistoryView::VRAC_VIEW_ACHETEURVAL;
						if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == $acteur) {
							$isProprietaire = true;
						}
					}
					if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_MANDATAIREID]) {
						$acteur = VracClient::VRAC_TYPE_COURTIER;
						$const = VracHistoryView::VRAC_VIEW_MANDATAIREVAL;
						if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == $acteur) {
							$isProprietaire = true;
						}
					}
					if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_VENDEURID]) {
						$acteur = VracClient::VRAC_TYPE_VENDEUR;
						$const = VracHistoryView::VRAC_VIEW_VENDEURVAL;
						if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == $acteur) {
							$isProprietaire = true;
						}
					}
					if (in_array($elt[VracHistoryView::VRAC_VIEW_STATUT], array(VracClient::STATUS_CONTRAT_NONSOLDE, VracClient::STATUS_CONTRAT_SOLDE, VracClient::STATUS_CONTRAT_ANNULE))) {
						$validated = true;
					}
			?>
			<?php if($elt[VracHistoryView::VRAC_VIEW_STATUT] || $isProprietaire || $isOperateur): ?>
			<tr class="<?php echo $statusColor ?>" >
			  <td>
			  	<?php if (!$validated && $isOperateur): ?>
			  	<a class="supprimer" onclick="return confirm('Confirmez-vous la suppression du contrat?')" style="left: 5px;" href="<?php echo url_for('vrac_supprimer', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Supprimer</a>
			  	<?php endif; ?>
				<span class="statut <?php echo $statusColor ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_STATUT]; ?>"></span>
			  </td>
			  <?php if ($isOperateur):  ?>
			  <td><?php echo ($elt[VracHistoryView::VRAC_VIEW_MODEDESAISIE])? $libelles[$elt[VracHistoryView::VRAC_VIEW_MODEDESAISIE]] : ''; ?></td>
			  <?php endif; ?>
			  <td class="type" ><?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?></td>
			  <td id="num_contrat">
			    <?php if($elt[VracHistoryView::VRAC_VIEW_STATUT]): ?>
			    	<?php if ($validated): ?>
			    		<strong><?php echo $vraclibelle ?></strong><br />
			      		<a class="highlight_link" href="<?php echo url_for("vrac_visualisation", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Visualiser le contrat</a>
			    	<?php else: ?>
			    		<?php if ($elt[VracHistoryView::VRAC_VERSION]): ?><strong><?php echo $vraclibelle ?></strong><?php else: ?>En attente<?php endif; ?><br />
						<?php if ($etablissement && ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $isOperateur)): ?>
			    		<a class="highlight_link" href="<?php echo url_for('vrac_validation', array('contrat' => $vracid, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>">Accéder au contrat</a>
						<?php endif; ?>
			    	<?php endif; ?>
			    <?php else: ?>
			    	<?php if ($elt[VracHistoryView::VRAC_VERSION]): ?><strong><?php echo $vraclibelle ?></strong><br /><?php endif; ?>
			    	<?php if ($etablissement && ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $isOperateur)): ?>
			      	<a class="highlight_link" href="<?php echo url_for("vrac_edition", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Accéder au contrat</a>
			      	<?php endif; ?>
			    <?php endif; ?>
			      
			  </td>
			 <td>
			  <?php if($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID]): ?>
			      <span class="<?php if ($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL]): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
			      <?php if($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM]): ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM] ?>
			      <?php elseif($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE]): ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE] ?>
			      <?php else: ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID]; ?>    
			      <?php endif; ?>
			      </span>
				  <?php if ($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL]): ?><br />Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL])) ?><?php endif; ?>
			<?php endif; ?>
		    </td>   
		  <td>
			  <?php if($elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID]): ?>
				      <span class="<?php if ($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
				      <?php if($elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM] ?>
				      <?php elseif($elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE] ?>
				      <?php else: ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID]; ?>    
				      <?php endif; ?>
				      </span>
					  <?php if ($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]): ?><br />Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL])) ?><?php endif; ?>
				<?php endif; ?>
		    </td> 
		  <td>
		  		  <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]): ?>
				      <span class="<?php if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
				      <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM] ?>
				      <?php elseif($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE] ?>
				      <?php else: ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]; ?>    
				      <?php endif; ?>
				      </span>
					  <?php if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]): ?><br />Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL])) ?><?php endif; ?>
				<?php endif; ?>
		    </td>            
			    <td><?php echo $elt[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE] ?><br /><?php echo $elt[VracHistoryView::VRAC_VIEW_MILLESIME] ?> <?php echo $elt[VracHistoryView::VRAC_VIEW_LABELS] ?></td>
			    <td><?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0'; ?> hl / <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?> hl</td>
			    <td><?php echo (isset($elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]))? $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE] : '0'; ?>&nbsp;€&nbsp;HT/hl</td>
			</tr>
			<?php endif; ?>
	        <?php endforeach; ?>
	        <?php 
	        	foreach ($vracs as $value):
	        		$elt = $value->value;
	        		if ($isOperateur && !$configurationProduit->exist($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])) {
	        			continue;
	        		}
					$acteur = null;
					$validated = false;
					$isProprietaire = false;
					$statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
					$vracid = $elt[VracHistoryView::VRAC_VIEW_NUM];
					$vraclibelle = $elt[VracHistoryView::VRAC_VIEW_NUM];
					if ($elt[VracHistoryView::VRAC_VERSION]) {
						$vracid .= '-'.$elt[VracHistoryView::VRAC_VERSION];
						if ($isOperateur) {
							$vraclibelle .= '-'.$elt[VracHistoryView::VRAC_VERSION];
						}
					}
					if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_ACHETEURID]) {
						$acteur = VracClient::VRAC_TYPE_ACHETEUR;
						$const = VracHistoryView::VRAC_VIEW_ACHETEURVAL;
						if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == $acteur) {
							$isProprietaire = true;
						}
					}
					if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_MANDATAIREID]) {
						$acteur = VracClient::VRAC_TYPE_COURTIER;
						$const = VracHistoryView::VRAC_VIEW_MANDATAIREVAL;
						if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == $acteur) {
							$isProprietaire = true;
						}
					}
					if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_VENDEURID]) {
						$acteur = VracClient::VRAC_TYPE_VENDEUR;
						$const = VracHistoryView::VRAC_VIEW_VENDEURVAL;
						if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == $acteur) {
							$isProprietaire = true;
						}
					}
					if (in_array($elt[VracHistoryView::VRAC_VIEW_STATUT], array(VracClient::STATUS_CONTRAT_NONSOLDE, VracClient::STATUS_CONTRAT_SOLDE, VracClient::STATUS_CONTRAT_ANNULE))) {
						$validated = true;
					}
			?>
			<?php if($elt[VracHistoryView::VRAC_VIEW_STATUT] || $isProprietaire || $isOperateur): ?>
			<tr class="<?php echo $statusColor ?>" >
			  <td>
			  	<?php if (!$validated && $isOperateur): ?>
			  	<a class="supprimer" onclick="return confirm('Confirmez-vous la suppression du contrat?')" style="left: 5px;" href="<?php echo url_for('vrac_supprimer', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Supprimer</a>
			  	<?php endif; ?>
				<span class="statut <?php echo $statusColor ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_STATUT]; ?>"></span>
			  </td>
			  <?php if ($isOperateur):  ?>
			  <td><?php echo ($elt[VracHistoryView::VRAC_VIEW_MODEDESAISIE])? $libelles[$elt[VracHistoryView::VRAC_VIEW_MODEDESAISIE]] : ''; ?></td>
			  <?php endif; ?>
			  <td class="type" ><?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?></td>
			  <td id="num_contrat">
			    <?php if($elt[VracHistoryView::VRAC_VIEW_STATUT]): ?>
			    	<?php if ($validated): ?>
			    		<strong><?php echo $vraclibelle ?></strong><br />
			      		<a class="highlight_link" href="<?php echo url_for("vrac_visualisation", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Visualiser le contrat</a>
			    	<?php else: ?>
			    		<?php if ($elt[VracHistoryView::VRAC_VERSION]): ?><strong><?php echo $vraclibelle ?></strong><?php else: ?>En attente<?php endif; ?><br />
						<?php if ($etablissement && ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $isOperateur)): ?>
			    		<a class="highlight_link" href="<?php echo url_for('vrac_validation', array('contrat' => $vracid, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>">Accéder au contrat</a>
						<?php endif; ?>
			    	<?php endif; ?>
			    <?php else: ?>
			    	<?php if ($elt[VracHistoryView::VRAC_VERSION] || $isOperateur): ?><strong><?php echo $vraclibelle ?></strong><br /><?php endif; ?>
			    	<?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE) || $isOperateur): ?>
			      	<a class="highlight_link" href="<?php echo url_for("vrac_edition", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Accéder au contrat</a>
			      	<?php endif; ?>
			    <?php endif; ?>
			      
			  </td>
			 <td>
			  <?php if($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID]): ?>
			      <span class="<?php if ($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL]): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
			      <?php if($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM]): ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM] ?>
			      <?php elseif($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE]): ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE] ?>
			      <?php else: ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID]; ?>    
			      <?php endif; ?>
			      </span>
				  <?php if ($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL]): ?><br />Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL])) ?><?php endif; ?>
			<?php endif; ?>
		    </td>   
		  <td>
			  <?php if($elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID]): ?>
				      <span class="<?php if ($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
				      <?php if($elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM] ?>
				      <?php elseif($elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE] ?>
				      <?php else: ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID]; ?>    
				      <?php endif; ?>
				      </span>
					  <?php if ($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]): ?><br />Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL])) ?><?php endif; ?>
				<?php endif; ?>
		    </td> 
		  <td>
		  		  <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]): ?>
				      <span class="<?php if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
				      <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM] ?>
				      <?php elseif($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE]): ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE] ?>
				      <?php else: ?>
				          <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]; ?>    
				      <?php endif; ?>
				      </span>
					  <?php if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]): ?><br />Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL])) ?><?php endif; ?>
				<?php endif; ?>
		    </td>            
			    <td><?php echo $elt[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE] ?><br /><?php echo $elt[VracHistoryView::VRAC_VIEW_MILLESIME] ?></td>
			    <td><?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? round($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE],4) : '0'; ?> hl / <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? round($elt[VracHistoryView::VRAC_VIEW_VOLPROP],4) : '0'; ?> hl</td>
			    <td><?php echo (isset($elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]))? round($elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE],2) : '0'; ?>&nbsp;€&nbsp;HT/hl</td>
			</tr>
			<?php endif; ?>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>