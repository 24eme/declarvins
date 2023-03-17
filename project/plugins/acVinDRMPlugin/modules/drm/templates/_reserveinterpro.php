<?php
use_helper('Float');
$produits = $drm->getProduitsReserveInterpro();
if (count($produits) && DRMClient::hasActiveReserveInterpro()): ?>
<div class="tableau_ajouts_liquidations">
<h2><strong>Réserve</strong> interprofessionnelle</h2>
<p style="padding: 10px;">L'assemblée générale d'Inter Rhône a voté la mise en place d'une réserve interprofessionnelle. Le tableau suivant récapitule les volumes de votre réserve :</p>
<table class="tableau_recap" style="width:auto;">
    <thead>
        <tr>
            <td style="font-weight: bold; border: none; width: 330px;">&nbsp;</td>
            <th style="font-weight: bold; border: none; width: 120px;">Volumes en réserve</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $p) : ?>
                    <tr>
                        <td style="text-align: left;"><?php echo $p->getLibelle(); ?></td>
                        <td style="text-align: right;"><strong><?php echoLongFloat($p->getReserveInterpro()); ?></strong>&nbsp;hl</td>
                    </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
