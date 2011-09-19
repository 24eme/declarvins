<?php echo $message ?>
<?php if (!$valide): ?>
<form method="post" action="<?php echo url_for('@compte_validation') ?>">
<input type="hidden" name="interpro_id" value="<?php echo $interpro->get('_id') ?>" />
<div class="btnValidation">
    <input type="image" src="/images/boutons/btn_valider.png" alt="Valider" />
</div>
</form>
<?php endif; ?>