<?php
$contrat = VracClient::getInstance()->findByNumContrat($form->getObject()->getKey());
?>

<tr class="itemContrat contenu">
    <td>
        <span><?php echo $form->getObject()->getKey() ?> (<?php echo $contrat->volume_propose; ?>&nbsp;hl à <?php echo $contrat->prix_unitaire; ?>&nbsp;€/hl)<br /><?php if ($contrat->acheteur->raison_sociale) { echo $contrat->acheteur->raison_sociale; if ($contrat->acheteur->nom) { echo ' ('.$contrat->acheteur->nom.')'; } } else { $contrat->acheteur->nom; } ?></span>
    </td>
    <td align="center">
        <form action="<?php echo url_for('drm_vrac_update_volume', $form->getObject()) ?>" method="post">
            <?php echo $form->renderGlobalErrors() ?>
            <?php echo $form->renderHiddenFields() ?>
            <span>
                <?php echo $form['volume']->render() ?>
                <span class="unite"> (en hl) </span>
                <span class="error"><?php echo $form['volume']->renderError() ?></span>
                <button name="valider" class="btn_valider" type="submit">OK</button>
            </span>
        </form>
    </td>
    <td align="center">
        <a href="<?php echo url_for('drm_delete_vrac', $form->getObject()) ?>"><img src="/images/pictos/pi_supprimer.png"></a>
    </td>
</tr>