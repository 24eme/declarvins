<?php use_helper('Vrac'); ?>
<?php use_helper('Date') ?>
<div class="tableau_ajouts_liquidations">
	<table id="tableau_recap" class="visualisation_contrat">
	    <thead>
	        <tr>
                <th>Statut</th>
	            <th>Contrat <a href="" class="msg_aide" data-msg="help_popup_vrac_visa" title="Message aide"></a></th>
	            <th>Soussignés</th>
	            <th>Produit</th>
	            <th>Volume<br />enlevé&nbsp;/&nbsp;proposé</th>
	            <th>Prix (HT)</th>
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
					$statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
					$vracid = $elt[VracHistoryView::VRAC_VIEW_NUM];
					$vraclibelle = $elt[VracHistoryView::VRAC_VIEW_NUM];
					if ($elt[VracHistoryView::VRAC_VERSION]) {
						$vracid .= '-'.$elt[VracHistoryView::VRAC_VERSION];
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
					if ($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID] && $elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL] && $elt[VracHistoryView::VRAC_VIEW_VENDEURVAL] && $elt[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]) {
						$validated = true;
					} elseif (!$elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID] && $elt[VracHistoryView::VRAC_VIEW_ACHETEURVAL] && $elt[VracHistoryView::VRAC_VIEW_VENDEURVAL]) {
						$validated = true;
					}
                    if($elt[VracHistoryView::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION) {
                        $validated = false;
                    }
                    $isContratPluriannuelApplication = strpos($vracid, '-A') !== false;
					$numContratPluriannuelApplication = ($isContratPluriannuelApplication && $vracid)? substr($vracid, -3) : '';
					if ($pluriannuel && $isContratPluriannuelApplication) {
						$vraclibelle = '<p style="margin: 0;margin-top: 5px;"><svg style="padding-top: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/></svg><span title="Contrat d\'application du contrat pluriannuel cadre ci-dessus">'.$numContratPluriannuelApplication.'</span></p>';
					}
			?>
			<?php if($elt[VracHistoryView::VRAC_VIEW_STATUT] || $isProprietaire || $isOperateur): ?>
			<tr class="<?php echo $statusColor ?><?php if ($pluriannuel && !$isContratPluriannuelApplication): ?> pluriannuel_application<?php endif; ?>" >
			  <td class="text-center" style="padding: 0;">
			  	<?php if (!$validated && $isOperateur): ?>
			  	<a class="supprimer" onclick="return confirm('Confirmez-vous la suppression du contrat?')" style="left: 5px;" href="<?php echo url_for('vrac_supprimer', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Supprimer</a>
			  	<?php endif; ?>
                <?php if ($pluriannuel && !$isContratPluriannuelApplication): ?>
                    <span style="font-size: 13px; background: url('/images/pictos/pi_pluriannuel.png') left 0 no-repeat;padding: 0px 5px 0 20px;" title="Contrat pluriannuel cadre"></span>
                <?php else: ?>
				    <span class="statut <?php echo $statusColor ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_STATUT]; ?>" style="cursor: pointer;"></span>
                <?php endif; ?>
                <?php if($elt[VracHistoryView::VRAC_OIOC_DATETRAITEMENT]): ?>
				<br />Envoi Oco : <?php echo format_date($elt[VracHistoryView::VRAC_OIOC_DATETRAITEMENT], 'd/M/y') ?>
				<?php endif; ?>
			  </td>
			  <td class="text-center" id="num_contrat">
                  <span style="font-size: 22px; cursor: pointer;" class="<?php echo getTypeIcon($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?>"></span><br />
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
			      	<a class="highlight_link" href="<?php echo url_for("vrac_edition", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">
					<?php if ($pluriannuel && $isContratPluriannuelApplication): ?>
						<p style="margin: 0;margin-top: 5px;"><svg style="padding-top: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/></svg><span title="Contrat d\'application du contrat pluriannuel cadre ci-dessus">Accéder</span></p>
					<?php else: ?>
						Accéder
					<?php endif; ?>
					</a>
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
			    	<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'vrac'): ?>
			    		<?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0'; ?> / <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?>&nbsp;hl
			    	<?php else: ?>
			    		<?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?>&nbsp;<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'raisin'): ?>kg<?php else: ?>hl<?php endif; ?>
			    	<?php endif; ?>
			    </td>
			    <td class="text-center">
			    	<?php if (isset($elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]) && $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]): ?>
			    	<?php echo $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE] ?>&nbsp;<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] != 'raisin'): ?>€/hl<?php else: ?>€/kg<?php endif;?>
					<?php endif; ?>
                    <?php if ($pluriannuel && !$isContratPluriannuelApplication): ?>
                    <a class="text-warning" style="color: #ec971f; font-size: 20px; position: absolute; right: 2px;" title="Créer un contrat d'application" href="<?php echo url_for('vrac_pluriannuel', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><span class="glyphicon glyphicon-plus-sign"></span></a>
                    <?php endif; ?>
                </td>
			</tr>
			<?php endif; ?>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>
