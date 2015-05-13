<?php use_helper('Float'); ?>
<tr class="addContrat">
    <td class="libelle" width="600px">
        <strong><?php echo $detail->getLibelle(ESC_RAW); ?></strong>
    </td>
    <td style="text-align: left;">
    	Sortie vrac&nbsp;&nbsp;: <span style="width: 60%; text-align: right; display: inline-block;"><strong><?php echo echoLongFloat($detail->sorties->vrac) ?></strong>hl</span><br />
    	Total saisie&nbsp;: <span style="width: 60%; text-align: right; display: inline-block;"><span id="total_saisie_<?php echo $detail->getIdentifiantHTML() ?>" class="total_saisie_contrats" style="font-weight: bold;">0</span>hl</span>
    </td>
    <td width="35px">
        <?php if($hasContrat): ?>
            &nbsp;<a class="btn_ajouter_ligne_template" data-container="#contrats<?php echo $detail->getIdentifiantHTML() ?> tbody" data-template="#template_form_detail_contrats_item<?php echo $detail->getIdentifiantHTML() ?>" href="#"></a>
        <?php endif; ?>
    </td>
</tr>

