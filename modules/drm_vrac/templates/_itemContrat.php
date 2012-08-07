<?php
$contrat = VracClient::getInstance()->retrieveById($form->getObject()->getKey());

?><tr>
        <td></td>
        <td align="center">
    <span><?php echo $form->getObject()->getKey() ?> (<?php echo $contrat->volume_propose; ?>&nbsp;hl de <?php echo ConfigurationClient::getCurrent()->get($contrat->produit)->getLibelleFormat() ?> à <?php echo $contrat->prix_unitaire; ?>&nbsp;€/hl)</span>
        </td>
        <td align="center">
                <form action="<?php echo url_for('drm_vrac_update_volume', $form->getObject()) ?>" method="post">
                        <?php echo $form->renderGlobalErrors() ?>
                        <?php echo $form->renderHiddenFields() ?>
                        <div class="ligne_form">
                                <?php echo $form['volume']->render() ?>
                                <span class="unite"> (en hl) </span>
                                <span class="error"><?php echo $form['volume']->renderError() ?></span>
                        </div>
                        <div class="ligne_form_btn">
                                <button name="valider" class="btn_valider" type="submit">OK</button>
                        </div>
                </form>
        </td>
        <td>
                <a href="<?php echo url_for('drm_delete_vrac', $form->getObject()) ?>">X</a>
        </td>
</tr>