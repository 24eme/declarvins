<?php 
$acteur = null;
$validated = true;
if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_ACHETEURID]) {
	$acteur = VracClient::VRAC_TYPE_ACHETEUR;
	$const = VracHistoryView::VRAC_VIEW_ACHETEURVAL;
}
if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_MANDATAIREID]) {
	$acteur = VracClient::VRAC_TYPE_COURTIER;
	$const = VracHistoryView::VRAC_VIEW_MANDATAIREVAL;
}
if ($etablissement && $etablissement->identifiant == $elt[VracHistoryView::VRAC_VIEW_VENDEURID]) {
	$acteur = VracClient::VRAC_TYPE_VENDEUR;
	$const = VracHistoryView::VRAC_VIEW_VENDEURVAL;
}
if ($acteur) {
	if (!$elt[$const]) {
		$validated = false;
	}
}
?>

<tr class="<?php echo $statusColor; ?>" >
  <td>
  	<?php if (!$validated): ?>
  	<a class="supprimer" onclick="return confirm('Confirmez-vous la suppression du contrat?')" style="left: 5px;" href="<?php echo url_for('vrac_supprimer', array('numero_contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Supprimer</a>
  	<?php endif; ?>
    <?php echo $elt[VracHistoryView::VRAC_VIEW_STATUT]; ?>
  </td>
  <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): $libelles = Vrac::getModeDeSaisieLibelles(); ?>
  <td><?php echo ($elt[VracHistoryView::VRAC_VIEW_MODEDESAISIE])? $libelles[$elt[VracHistoryView::VRAC_VIEW_MODEDESAISIE]] : ''; ?></td>
  <?php endif; ?>
  <td class="type" >
      <!--<span class="type_<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]; ?>">
          <?php echo ($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT])? typeProduit($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) : ''; ?>
      </span>-->
      <?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?>
  </td>
  <td id="num_contrat">
    <?php if($elt[VracHistoryView::VRAC_VIEW_STATUT]): ?>
    	<?php if ($validated): ?>
      		<a href="<?php echo url_for("vrac_visualisation", array('numero_contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo substr($vracid,0,8)."&nbsp;".substr($vracid,8,  strlen($vracid)-1); ?></a>
    	<?php else: ?>
			<?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
    		<a href="<?php echo url_for('vrac_validation', array('numero_contrat' => $vracid, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>">Brouillon</a>
			<?php endif; ?>
    	<?php endif; ?>
    <?php else: ?>
    	<?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
      	<a href="<?php echo url_for("vrac_edition", array('numero_contrat' => $vracid, 'etablissement' => $etablissement)) ?>">Brouillon</a>
      	<?php endif; ?>
    <?php endif; ?>
      
  </td>
  <td>
        <ul>  
          <li>
            <?php include_partial('vrac/listItemSoussigne', array(
                                                          'libelle' => 'Vendeur :',
                                                          'identifiant' => $elt[VracHistoryView::VRAC_VIEW_VENDEUR_ID],
                                                          'nom' => $elt[VracHistoryView::VRAC_VIEW_VENDEUR_NOM],
                                                          'rs' => $elt[VracHistoryView::VRAC_VIEW_VENDEUR_RAISON_SOCIALE],
                                                                  )) ?>
          </li>
          <li>
            <?php include_partial('vrac/listItemSoussigne', array(
                                                          'libelle' => 'Acheteur :',
                                                          'identifiant' => $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_ID],
                                                          'nom' => $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_NOM],
                                                          'rs' => $elt[VracHistoryView::VRAC_VIEW_ACHETEUR_RAISON_SOCIALE],
                                                                  )) ?>
          </li>
          <li>
            <?php include_partial('vrac/listItemSoussigne', array(
                                                          'libelle' => 'Mandataire :',
                                                          'identifiant' => $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_ID],
                                                          'nom' => $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_NOM],
                                                          'rs' => $elt[VracHistoryView::VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE],
                                                                  )) ?>
          </li>
       </ul>
    </td>              
    <td>
      <?php echo ($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])? ConfigurationClient::getCurrent()->get($elt[VracHistoryView::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat() : ''; ?>
      <br />
      <?php echo $elt[VracHistoryView::VRAC_VIEW_MILLESIME] ?> <?php echo $elt[VracHistoryView::VRAC_VIEW_LABELS] ?>
    </td>
    <td>           
        <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0';
              echo ' hl / ';
              echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP].' hl' : '0 hl';
         ?>
    </td>
    <td>           
        <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE]))? $elt[VracHistoryView::VRAC_VIEW_PRIXUNITAIRE] : '0'; ?>&nbsp;€/hl Hors cotisations<br />
    </td>
</tr>