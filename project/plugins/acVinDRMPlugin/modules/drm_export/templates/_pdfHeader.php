<div id="header">
   <center>
<h1>
<?php if (!$drm->valide->date_saisie) {
   echo '<span class="rectificative">Brouillon&nbsp;: </span> ';
 }?><?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>Extrait de la d<?php else: ?>D<?php endif; ?>éclaration récapitulative mensuelle en droits suspendus de <?php echo $drm->getHumanDate(); ?> 
<?php if($drm->isRectificative()): ?>
 - <span class="rectificative">Rectificative <?php echo sprintf('%02d', $drm->rectificative) ?></span>
<?php endif; ?>
<br/>Vins de la vallée du Rhône et de Provence (Inter-Rhône, CIVP, InterVins SE)
</h1></center>
<table>
<tr>
	<td class="premier">Nom / Raison sociale : <strong><?php echo ($drm->declarant->raison_sociale)? $drm->declarant->raison_sociale : $drm->declarant->nom ?></strong></td>
   <td>N° DRM : <strong><?php echo preg_replace('/DRM-/', '', $drm->_id); ?></strong><?php if($drm->isValidee()): ?><br />(<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>saisie interne le<?php else: ?>validée le<?php endif;?> <strong><?php echo $drm->getEuValideDate(); ?></strong>)<?php endif; ?></td>
</tr>
<tr>
	<td class="premier" >Lieu où est tenue la comptabilité matière :
		<strong>
		<?php if ($drm->declarant->comptabilite->adresse): ?>
		<?php echo $drm->declarant->comptabilite->adresse ?>, <?php echo $drm->declarant->comptabilite->code_postal ?> <?php echo $drm->declarant->comptabilite->commune ?> <?php echo $drm->declarant->comptabilite->pays ?>
		<?php else: ?>
		<?php echo $drm->declarant->siege->adresse ?>, <?php echo $drm->declarant->siege->code_postal ?> <?php echo $drm->declarant->siege->commune ?> <?php echo $drm->declarant->siege->pays ?>
		<?php endif; ?>
		</strong>
	</td>
	<td>
		<?php if($drm->declarant->siret): ?>
			N°SIRET : <strong><?php echo $drm->declarant->siret ?></strong>
		<?php elseif($drm->declarant->cni): ?>
			N° CNI : <strong><?php echo $drm->declarant->cni ?></strong>
		<?php else: ?>
			N° SIRET :
		<?php endif; ?>
	</td>
</tr>
<tr>
	<td class="premier">Adresse du chai : 
		<strong><?php echo $drm->declarant->siege->adresse ?>, <?php echo $drm->declarant->siege->code_postal ?> <?php echo $drm->declarant->siege->commune ?> <?php echo $drm->declarant->siege->pays ?></strong>
	</td>
	<td>N° accises / EA : <strong><?php echo $drm->declarant->no_accises ?></strong></td>
</tr>
<tr>
	<td class="premier">Service des douanes de : <strong><?php echo $drm->declarant->service_douane ?></strong></td>
	<td>N° CVI : <strong><?php echo $drm->declarant->cvi ?></strong></td>
</tr>
</table>
</div>
