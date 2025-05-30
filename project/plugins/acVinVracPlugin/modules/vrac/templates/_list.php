<?php use_helper('Vrac'); ?>
<?php use_helper('Date') ?>

<div class="form-group">
    <input type="hidden" data-placeholder="Saisissez un numéro de contrat, un soussigné, un produit, un volume ou un prix" data-hamzastyle-container="#tableau_recap" class="hamzastyle" style="width:900px;" />
</div>

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
	        	$isAdmin = $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR);
	        	foreach ($vracs as $value):
	        		$elt = $value->value;
	        		if ($isAdmin && $elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID] && !$configurationProduit->exist($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])) {
	        			continue;
	        		}
					$validated = false;
					$isProprietaire = false;
                    $isAdossePluriannuel = false;
					$statusColor = statusColor($elt[VracHistoryView::VRAC_VIEW_STATUT]);
					$statusLibelle = statusLibelle($elt[VracHistoryView::VRAC_VIEW_STATUT], $pluriannuel);
					$vracid = $elt[VracHistoryView::VRAC_VIEW_NUM];
                    if ($elt[VracHistoryView::VRAC_VERSION]) {
                        $vracid .= '-'.$elt[VracHistoryView::VRAC_VERSION];
                    }
					$vraclibelle = $elt[VracHistoryView::VRAC_VIEW_NUM];
                    if (($pos = strpos($vraclibelle, '-')) !== false) {
                        $vraclibelle = substr($vraclibelle, 0, $pos);
                    }
                    if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == VracClient::VRAC_TYPE_COURTIER && $etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_MANDATAIREID]) {
						$isProprietaire = true;
					}
                    if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == VracClient::VRAC_TYPE_ACHETEUR && $etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_ACHETEURID]) {
						$isProprietaire = true;
					}
					if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == VracClient::VRAC_TYPE_VENDEUR && $etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_VENDEURID]) {
						$isProprietaire = true;
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
										if (empty($statusColor) && !$isProprietaire && !$isAdmin) {
											continue;
										}
		?>
        <?php $vendeur = $elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM] ?: $elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE] ?>
        <?php $vendeur = str_replace(['&quot;', '"'], '', $vendeur); ?>
        <tr data-words='<?php echo json_encode(array_merge(array(
                                                strtolower($elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM] ?: $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE]),
                                                strtolower($vendeur),
                                                strtolower($elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM] ?: $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE]),
                                                strtolower(str_replace(' Conventionnel', '', trim($elt[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE]))),
                                                strtolower($vraclibelle),
                                                strtolower($elt[VracHistoryView::VRAC_VIEW_MILLESIME]),
                                                strtolower($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]),
                                                $elt[VracHistoryView::VRAC_VIEW_VOLPROP],
                                                $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]
            )), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>'
            class="<?php echo $statusColor ?> hamzastyle-item vertical-center">
			  <td class="text-center" style="padding: 0;">
			  	<?php if ((!$validated||$pluriannuel) && $isAdmin && $elt[VracHistoryView::VRAC_VIEW_STATUT] != VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION): ?>
			  	<a class="supprimer" onclick="return confirm('Confirmez-vous la suppression du contrat?')" style="left: 5px;" href="<?php echo url_for('vrac_supprimer', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Supprimer</a>
			  	<?php endif; ?>
                <?php if (empty($statusColor)): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <title>En cours de saisie</title>
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                </svg>
                <?php else: ?>
				<span class="statut <?php echo $statusColor ?>" title="<?php echo $statusLibelle; ?>" style="cursor: pointer;"></span>
                <?php endif; ?>
                <?php if($elt[VracHistoryView::VRAC_OIOC_DATETRAITEMENT]): ?>
				<br />Envoi Oco : <?php echo format_date($elt[VracHistoryView::VRAC_OIOC_DATETRAITEMENT], 'd/M/y') ?>
				<?php endif; ?>
			  </td>
			  <td class="text-center" id="num_contrat">
                  <span style="font-size: 22px; cursor: pointer;" class="<?php echo getTypeIcon($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) ?>" title="<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?>"></span><br />
                  <?php if ($isAdossePluriannuel): ?>
                  <a href="<?php echo url_for('vrac_visualisation', array('contrat' => $elt[VracHistoryView::VRAC_REF_PLURIANNUEL])) ?>"><span title="Contrat d'application n°<?php echo substr($elt[VracHistoryView::VRAC_VIEW_NUM], -2); ?> adossé au contrat pluriannuel cadre n°<?php echo $elt[VracHistoryView::VRAC_REF_PLURIANNUEL] ?>" class="style_label" style="display: inline-block; height: 21px; width: 16px; text-align: center; background: url('/images/pictos/pi_pluriannuel.png') left 0 no-repeat;margin: 0px 5px 0 0px; vertical-align: middle;"></span></a>
                  <?php endif; ?>
			    <?php if($elt[VracHistoryView::VRAC_VIEW_STATUT]): ?>
			    	<?php if ($validated): ?>
			    		<a class="highlight_link" href="<?php echo url_for("vrac_visualisation", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo $vraclibelle ?></a>
			    	<?php else: ?>
			    		<?php if ($elt[VracHistoryView::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION): ?>
			    			<a class="highlight_link" href="<?php echo url_for('vrac_annulation', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo $vraclibelle ?></a>
			    		<?php else: ?>
							<?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE)): ?>
				    		<a class="highlight_link" href="<?php echo url_for('vrac_validation', array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Accéder</a>
							<?php elseif ($isAdmin): ?>
							<a class="highlight_link" href="<?php echo url_for("vrac_visualisation", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Accéder</a>
							<?php endif; ?>
						<?php endif; ?>
			    	<?php endif; ?>
			    <?php else: ?>
			    	<?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE) || $isAdmin): ?>
                        <?php if ($isProprietaire || $isAdmin): ?>
			      	<a class="highlight_link" href="<?php echo url_for("vrac_edition", array('contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Accéder</a>
                        <?php else: ?>
                            En cours d'édition par le responsable
                        <?php endif; ?>
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
                  <?php if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == VracClient::VRAC_TYPE_ACHETEUR): ?>
                      (Responsable)
                  <?php endif; ?>
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
                  <?php if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == VracClient::VRAC_TYPE_VENDEUR): ?>
                      (Responsable)
                  <?php endif; ?>
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
                     <?php if ($elt[VracHistoryView::VRAC_VIEW_VOUSETES] == VracClient::VRAC_TYPE_COURTIER): ?>
                         (Responsable)
                     <?php endif; ?>
                  <?php endif; ?>
		    </td>
			    <td class="text-left"><?php echo str_replace(' Conventionnel', '', trim($elt[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE])) ?> <?php echo $elt[VracHistoryView::VRAC_VIEW_MILLESIME] ?></td>
			    <td class="text-center">
					<?php if ($pluriannuel): ?>
                        <?php echo $elt[VracHistoryView::VRAC_VIEW_VOLPROP] ?> <?php echo ($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] === 'raisin' || $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] === 'mout') ? 'kg' : 'hl' ?>
					<?php else: ?>
				    	<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'vrac'): ?>
				    		<?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0'; ?> / <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?>&nbsp;hl
				    	<?php else: ?>
				    		<?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0'; ?>&nbsp;<?php if($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'raisin'): ?>kg<?php else: ?>hl<?php endif; ?>
				    	<?php endif; ?>
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
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>
