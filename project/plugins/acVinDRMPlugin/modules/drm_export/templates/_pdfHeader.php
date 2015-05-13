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
	<td class="premier"><strong>Nom / Raison sociale : <?php echo ($drm->declarant->raison_sociale)? $drm->declarant->raison_sociale : $drm->declarant->nom ?></strong></td>
   <td>n° DRM : <?php echo preg_replace('/DRM-/', '', $drm->_id); ?><?php if($drm->isValidee()): ?> (<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>saisie interne le<?php else: ?>validée le<?php endif;?> <?php echo $drm->getEuValideDate(); ?>)<?php endif; ?></td>
</tr>
<tr>
	<td class="premier" >Lieu où est tenue la comptabilité matière :
		<?php if ($drm->declarant->comptabilite->adresse): ?>
		<?php echo $drm->declarant->comptabilite->adresse ?>, <?php echo $drm->declarant->comptabilite->code_postal ?> <?php echo $drm->declarant->comptabilite->commune ?> <?php echo $drm->declarant->comptabilite->pays ?>
		<?php else: ?>
		<?php echo $drm->declarant->siege->adresse ?>, <?php echo $drm->declarant->siege->code_postal ?> <?php echo $drm->declarant->siege->commune ?> <?php echo $drm->declarant->siege->pays ?>
		<?php endif; ?>
	</td>
	<td>
		<?php if($drm->declarant->siret): ?>
			SIRET : <?php echo $drm->declarant->siret ?>
		<?php elseif($drm->declarant->cni): ?>
			CNI : <?php echo $drm->declarant->cni ?>
		<?php else: ?>
			SIRET :
		<?php endif; ?>
	</td>
</tr>
<tr>
	<td class="premier">Adresse et n° d'EA du chai : 
		<?php echo $drm->declarant->siege->adresse ?>, <?php echo $drm->declarant->siege->code_postal ?> <?php echo $drm->declarant->siege->commune ?> <?php echo $drm->declarant->siege->pays ?>
	</td>
	<td>Accises : <?php echo $drm->declarant->no_accises ?></td>
</tr>
<tr>
	<td class="premier">Service des douanes de : <?php echo $drm->declarant->service_douane ?></td>
	<td>CVI : <?php echo $drm->declarant->cvi ?></td>
</tr>
</table>
</div>
