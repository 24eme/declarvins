<tr class="<?php echo $statusColor; ?>" >
  <td class="type" >
      <!--<span class="type_<?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]; ?>">
          <?php echo ($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT])? typeProduit($elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT]) : ''; ?>
      </span>-->
      <?php echo $elt[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] ?>
  </td>
  <td id="num_contrat">
      <a href="<?php echo url_for("vrac_visualisation", array('numero_contrat' => $vracid, 'etablissement' => $etablissement)) ?>"><?php echo substr($vracid,0,8)."&nbsp;".substr($vracid,8,  strlen($vracid)-1); ?></a>
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
    </td>
    <td>           
        <?php echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLENLEVE]))? $elt[VracHistoryView::VRAC_VIEW_VOLENLEVE] : '0';
              echo ' / ';
              echo (isset($elt[VracHistoryView::VRAC_VIEW_VOLPROP]))? $elt[VracHistoryView::VRAC_VIEW_VOLPROP] : '0';
         ?>
    </td>
</tr>