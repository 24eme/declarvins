<p><?php 
if (!$valide_interpro) {
  echo "Vous n'avez pas validé ce compte pour votre interpro.";
 }else{
  echo "Vous avez validé ce compte pour votre interpro.";
 }
?></p>
<p><?php
if (!$compte_active) {
  echo "Ce compte n'est pas activé : toutes les interpros ne l'ont pas validé.";
 }else{
  echo "Ce compte est activé : toutes les interpros l'ont validé.";
 }
?></p>
<?php if (!$valide_interpro && !$compte_active): ?>
<form method="post" action="<?php echo url_for('@validation_fiche') ?>">
<input type="hidden" name="interpro_id" value="<?php echo $interpro->get('_id') ?>" />
<div class="btnValidation">
    <input type="submit" value="Valider" />
</div>
</form>
<?php endif; ?>