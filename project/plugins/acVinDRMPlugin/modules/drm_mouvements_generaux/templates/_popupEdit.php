<div id="popup_edit_produit_<?php echo $detail->getIdentifiantHTML() ?>" class="popup_contenu">
    <p style="margin-left: 105px;"><strong><?php echo $detail->getFormattedLibelle(ESC_RAW); ?></strong></p>
	<?php include_partial('formEdit', array('form' => $form, 'detail' => $detail)) ?>
</div>