<?php
use_helper('Float');
$produits = $drm->getProduitsReserveInterpro();
if (count($produits) && DRMClient::hasActiveReserveInterpro()): ?>
<div class="tableau_ajouts_liquidations">
<h2><strong>Réserve</strong> interprofessionnelle</h2>
<p style="padding: 10px;">L'assemblée générale de votre interprofession a voté la mise en place d'une réserve interprofessionnelle. Le tableau suivant récapitule les volumes de votre réserve :</p>
<table class="tableau_recap" style="width:100%;">
    <thead>
        <tr>
            <td style="font-weight: bold; border: none; width: 330px;">&nbsp;</td>
            <th style="font-weight: bold; border: none; width: 120px;">Volumes en réserve</th>
            <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <td style="font-weight: bold; border: none; width: 33px;">&nbsp;</td>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $p) : ?>
                    <tr>
                        <td style="text-align: left;"><?php echo $p->getFormattedLibelle(ESC_RAW); ?><?php if (!$p->hasReserveInterproMultiMillesime()): ?> - <span style="opacity:80%; font-size:95%;"><?php echo $p->printMillesimeOrRegulation() ?></span><?php endif; ?></td>
                        <td style="text-align: right;"><strong><?php echoFloat($p->getReserveInterpro()); ?></strong>&nbsp;hl</td>
                        <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !isset($hideFormReserve) && !$p->hasReserveInterproMultiMillesime()): ?>
                        <td style="background: #f1f1f1;border: none;text-align: left;">
                            <a onclick="document.querySelector('#modale_<?php echo $p->getIdentifiantHTML() ?>').showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </a>
                            <dialog id="modale_<?php echo $p->getIdentifiantHTML() ?>">
                                <form method="post" action="<?php echo url_for('drm_update_reserve_produit', $drm) ?>?millesime=<?php echo $p->getMillesimeForReserveInterpro() ?>">
                                    <input type="hidden" name="hashproduit" value="<?php echo $p->getHash() ?>" />
                                    <p style="text-align: right;">
                                        <a style="cursor:pointer;" onclick="document.querySelector('#modale_<?php echo $p->getIdentifiantHTML() ?>').close()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                            </svg>
                                        </a>
                                    </p>
                                    <p style="padding: 5px 0;">Le volume en réserve du <strong><?php echo $p->getFormattedLibelle(ESC_RAW); ?></strong> <?php echo $p->printMillesimeOrRegulation() ?> est de <strong><?php echoFloat($p->getReserveInterpro()); ?></strong>&nbsp;hl</p>
                                    <p style="padding: 5px 0;"><label for="reserve">Nouveau volume en reserve (<?php echo $p->printMillesimeOrRegulation() ?>) : </label><input id="reserve" type="text" inputmode="numeric" name="reserve" required />&nbsp;hl</p>
                                    <p style="padding: 5px 0;text-align: center;"><input type="submit" name="submit" value="Valider" /></p>
                                </form>
                            </dialog>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php if ($p->hasReserveInterproMultiMillesime()): foreach ($p->getReserveInterproDetails() as $millesime => $volume): ?>
                        <tr>
                            <td style="text-align: right"><span style="opacity:80%; font-size:95%;"><?php echo $p->printMillesimeOrRegulation($millesime) ?></span></td>
                            <td style="text-align: right"><?php echoFloat($volume); ?></strong>&nbsp;hl</td>
                            <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !isset($hideFormReserve)): ?>
                            <td style="background: #f1f1f1;border: none;text-align: left;">
                                <a onclick="document.querySelector('#modale_<?php echo $p->getIdentifiantHTML() ?>_<?php echo $millesime ?>').showModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </a>
                                <dialog id="modale_<?php echo $p->getIdentifiantHTML() ?>_<?php echo $millesime ?>">
                                    <form method="post" action="<?php echo url_for('drm_update_reserve_produit', $drm) ?>?millesime=<?php echo $millesime ?>">
                                        <input type="hidden" name="hashproduit" value="<?php echo $p->getHash() ?>" />
                                        <p style="text-align: right;">
                                            <a style="cursor:pointer;" onclick="document.querySelector('#modale_<?php echo $p->getIdentifiantHTML() ?>_<?php echo $millesime ?>').close()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                </svg>
                                            </a>
                                        </p>
                                        <p style="padding: 5px 0;">Le volume en réserve du <strong><?php echo $p->getFormattedLibelle(ESC_RAW); ?></strong> <?php echo $p->printMillesimeOrRegulation($millesime) ?> est de <strong><?php echoFloat($volume); ?></strong>&nbsp;hl</p>
                                        <p style="padding: 5px 0;"><label for="reserve">Nouveau volume en reserve (<?php echo $p->printMillesimeOrRegulation($millesime) ?>) : </label><input id="reserve" type="text" inputmode="numeric" name="reserve" required />&nbsp;hl</p>
                                        <p style="padding: 5px 0;text-align: center;"><input type="submit" name="submit" value="Valider" /></p>
                                    </form>
                                </dialog>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
