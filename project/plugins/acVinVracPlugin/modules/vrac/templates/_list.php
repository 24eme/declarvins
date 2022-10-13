<?php use_helper('Vrac'); ?>
<?php use_helper('Date') ?>
<div class="tableau_ajouts_liquidations">
	<table id="tableau_recap" class="visualisation_contrat">
	    <thead>
	        <tr>
                <th>Statut</th>
	            <th width="125px;">Contrat <a href="" class="msg_aide" data-msg="help_popup_vrac_visa" title="Message aide"></a></th>
	            <th>Soussignés</th>
	            <th>Produit</th>
                <?php if (!$pluriannuel): ?>
	            <th>Volume<br />enlevé&nbsp;/&nbsp;proposé</th>
	            <th>Prix (HT)</th>
                <?php else: ?>
	            <th>Quantité</th>
                <?php endif; ?>
	        </tr>
	    </thead>
	    <tbody>
            <?php
	        	$isOperateur = $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR);
	        	foreach ($vracs as $value):
	        		$elt = $value->value;
	        		if ($isOperateur && !$configurationProduit->exist($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])) {
	        			continue;
	        		}
					$acteur = null;
					$validated = false;
					$isProprietaire = false;
                    $isAdossePluriannuel = false;
					$statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
					$vracid = $elt[VracHistoryView::VRAC_VIEW_NUM];
					$vraclibelle = $elt[VracHistoryView::VRAC_VIEW_NUM];
                    if (($pos = strpos($vraclibelle, '-')) !== false)
                        $vraclibelle = substr($vraclibelle, 0, $pos);
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
					if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID] && $elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL] && $elt[VracHistoryView::VRAC_VIEW_VENDEURVAL] && $elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]) {
						$validated = true;
					} elseif (!$elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID] && $elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL] && $elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]) {
						$validated = true;
					}
                    if($elt[VracHistoryView::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION) {
                        $validated = false;
                    }
                    if($elt[VracHistoryView::VRAC_REF_PLURIANNUEL]) {
                        $isAdossePluriannuel = true;
                    }
			?>
			<?php if($elt[VracHistoryView::VRAC_VIEW_STATUT] || $isProprietaire || $isOperateur): ?>
			<tr class="<?php echo $statusColor ?>" >
			  <td class="text-center" style="padding: 0;">
			  	<?php if (!$validated && $isOperateur): ?>
			  	<a class="supprimer" onclick="return confirm('Confirmez-vous la suppression du contrat?')" style="left: 5px;" href="<?php echo url_for('vrac_supprimer', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Supprimer</a>
			  	<?php endif; ?>
				<span class="statut <?php echo $statusColor ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_STATUT]; ?>" style="cursor: pointer;"></span>
                <?php if($elt[VracHistoryView::VRAC_OIOC_DATETRAITEMENT]): ?>
				<br />Envoi Oco : <?php echo format_date($elt[VracHistoryView::VRAC_OIOC_DATETRAITEMENT], 'd/M/y') ?>
				<?php endif; ?>
			  </td>
			  <td class="text-center" id="num_contrat">
                  <span style="font-size: 22px; cursor: pointer;" class="<?php echo getTypeIcon($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?>"></span><br />
                  <?php if ($isAdossePluriannuel): ?>
                  <a href="<?php echo url_for('vrac_visualisation', array('contrat' => $elt[VracHistoryView::VRAC_REF_PLURIANNUEL])) ?>"><span title="Contrat d'application n°<?php echo substr($elt[VracHistoryView::VRAC_VIEW_NUM], -2); ?> adossé au contrat pluriannuel cadre n°<?php echo $elt[VracHistoryView::VRAC_REF_PLURIANNUEL] ?>" class="style_label" style="text-align: center; background: url('/images/pictos/pi_pluriannuel.png') left 0 no-repeat;padding: 0px 5px 0 20px;"></span></a>
                  <?php endif; ?>
			    <?php if($elt[VracHistoryView::VRAC_VIEW_STATUT]): ?>
			    	<?php if ($validated): ?>
			    		<a class="highlight_link" href="<?php echo url_for("vrac_visualisation", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo $vraclibelle ?></a>
			    	<?php else: ?>
			    		<?php if ($elt[VracHistoryView::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION): ?>
			    			<a class="highlight_link" href="<?php echo url_for('vrac_annulation', array('contrat' => $vracid, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>"><?php echo $vraclibelle ?></a>
			    		<?php else: ?>
							<?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE)): ?>
				    		<a class="highlight_link" href="<?php echo url_for('vrac_validation', array('contrat' => $vracid, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>"><?php echo $vraclibelle ?></a>
							<?php elseif ($isOperateur): ?>
							<a class="highlight_link" href="<?php echo url_for("vrac_visualisation", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo $vraclibelle ?></a>
							<?php endif; ?>
						<?php endif; ?>
			    	<?php endif; ?>
			    <?php else: ?>
			    	<?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE) || $isOperateur): ?>
			      	<a class="highlight_link" href="<?php echo url_for("vrac_edition", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Accéder</a>
			      	<?php endif; ?>
			    <?php endif; ?>
			  </td>
			 <td class="text-left">
                 <span class="glyphicon glyphicon-minus"></span> Acheteur :
                 <?php if ($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL]): ?>
                     <span class="texte_vert" title="Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL])) ?>" style="cursor: pointer;">
                 <?php else: ?>
                     <span class="texte_rouge">
                 <?php endif; ?>
			      <?php if($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM]): ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM] ?>
			      <?php elseif($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE]): ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE] ?>
			      <?php else: ?>
			          <?php echo $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID]; ?>
			      <?php endif; ?>
			      </span>
                  <br />
                  <span class="glyphicon glyphicon-minus"></span> Vendeur :
                  <?php if ($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]): ?>
                      <span class="texte_vert" title="Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_VENDEURVAL])) ?>" style="cursor: pointer;">
                  <?php else: ?>
                      <span class="texte_rouge">
                  <?php endif; ?>
                  <?php if($elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM]): ?>
                      <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM] ?>
                  <?php elseif($elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE]): ?>
                      <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE] ?>
                  <?php else: ?>
                      <?php echo $elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID]; ?>
                  <?php endif; ?>
                  </span>
                  <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]): ?>
                      <br />
                      <span class="glyphicon glyphicon-minus"></span> Courtier :
                      <?php if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]): ?>
                          <span class="texte_vert" title="Signé le <?php echo date('d/m/Y', strtotime($elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL])) ?>" style="cursor: pointer;">
                      <?php else: ?>
                          <span class="texte_rouge">
                      <?php endif; ?>
                     <?php if($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM]): ?>
                         <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM] ?>
                     <?php elseif($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE]): ?>
                         <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE] ?>
                     <?php else: ?>
                         <?php echo $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID]; ?>
                     <?php endif; ?>
                     </span>
                  <?php endif; ?>
		    </td>
			    <td class="text-left"><?php echo substr($elt[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE], strpos($elt[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE], ' ')) ?> <?php echo $elt[VracHistoryView::VRAC_VIEW_MILLESIME] ?></td>
			    <td class="text-center">
			    	<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'vrac' && !$pluriannuel): ?>
			    		<?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0'; ?> / <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?>&nbsp;hl
			    	<?php else: ?>
			    		<?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?>&nbsp;<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'raisin'): ?>kg<?php else: ?>hl<?php endif; ?>
			    	<?php endif; ?>
			    </td>
                <?php if (!$pluriannuel): ?>
			    <td class="text-center">
			    	<?php if (isset($elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]) && $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]): ?>
			    	<?php echo $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE] ?>&nbsp;<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] != 'raisin'): ?>€/hl<?php else: ?>€/kg<?php endif;?>
					<?php endif; ?>
                </td>
                <?php endif; ?>
			</tr>
			<?php endif; ?>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>
