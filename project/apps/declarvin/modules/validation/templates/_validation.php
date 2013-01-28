<p><i><?php 
if (!$valide_interpro) {
  echo "Vous n'avez pas validé ce compte pour votre interpro.";
 }else{
  echo "Vous avez validé ce compte pour votre interpro.";
 }
?></i></p><br />
<?php if (!$valide_interpro && !$compte_active && $nbEtablissement != 0): ?>
<form method="post" action="<?php echo url_for('validation_validation', array('num_contrat' => $contrat->no_contrat)) ?>">
<input type="hidden" name="interpro_id" value="<?php echo $interpro->get('_id') ?>" />
<div class="ligne_form_btn">
	<button type="submit" class=" btn_suiv"><span>Valider</span></button>
</div>
</form>
<?php endif; ?>