<tr class="addContrat">
    <td class="libelle">
        <strong><?php echo $detail->getLibelle(ESC_RAW); ?></strong>
    </td>
    <td>
    	Sortie vrac : <?php echo $detail->sorties->vrac ?>hl
    </td>
    <td>
        <?php if($hasContrat): ?>
            &nbsp;<a class="btn_ajouter_ligne_template" data-container="#contrats<?php echo $detail->getIdentifiantHTML() ?> tbody" data-template="#template_form_detail_contrats_item<?php echo $detail->getIdentifiantHTML() ?>" href="#"></a>
        <?php endif; ?>
    </td>
</tr>

