<?php
$contrat = VracClient::getInstance()->findByNumContrat($vrac->getKey());
?>

<tr class="itemContrat contenu">
    <td>
        <span><?php echo $vrac->getKey() ?> (<?php echo $contrat->volume_propose; ?>&nbsp;hl à <?php echo $contrat->prix_unitaire; ?>&nbsp;€/hl)<br /><?php if ($contrat->acheteur->raison_sociale) { echo $contrat->acheteur->raison_sociale; if ($contrat->acheteur->nom) { echo ' ('.$contrat->acheteur->nom.')'; } } else { $contrat->acheteur->nom; } ?></span>
    </td>
    <td align="center">
    	<?php echo $vrac->volume ?>&nbsp;HL&nbsp;&nbsp;
    	<a href="<?php echo url_for('drm_vrac_update_volume', $vrac) ?>" class="btn_edit btn_popup" data-popup-ajax="true" data-popup="#popup_edit_contrat_<?php echo $vrac->getKey() ?>" data-popup-config="configForm"></a>
    </td>
    <td align="center">
        <a href="<?php echo url_for('drm_delete_vrac', $vrac) ?>"><img src="/images/pictos/pi_supprimer.png"></a>
    </td>
</tr>