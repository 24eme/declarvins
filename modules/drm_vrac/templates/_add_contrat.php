<tr class="addContrat">
    <td colspan="3" class="libelle">
        <?php echo $detail->getLibelle(ESC_RAW); ?> (sortie vrac : <?php echo $detail->sorties->vrac ?> hl)
        <?php if($hasContrat): ?>
        <div class="btn">
            <a class="btn_ajouter_ligne_template" data-container="#contrats<?php echo $detail->getIdentifiantHTML() ?> tbody" data-template="#template_form_detail_contrats_item<?php echo $detail->getIdentifiantHTML() ?>" href="#"></a>
        </div>
        <?php endif; ?>
    </td>
</tr>

