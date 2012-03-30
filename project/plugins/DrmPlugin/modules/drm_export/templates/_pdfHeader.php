<div id="header">
<h1>Déclaration récapitulative mensuelle de mars 2012
<?php if($drm->isRectificative()): ?>
- <span style="font-style: italic;">Rectificative n° <?php echo sprintf('%02d', $drm->rectificative) ?></span>
<?php endif; ?>
<span class="date_validation">(validée le 01/12/2012)</span>
</h1>
<table>
<tr>
	<td class="premier"><strong>Nom / Raison sociale : <?php echo $drm->declarant->nom ?></strong></td>
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
	<td class="premier" >Lieu où est tenue la comptabilité matière :
		<?php if ($drm->declarant->comptabilite->adresse): ?>
		<?php echo $drm->declarant->comptabilite->adresse ?>, <?php echo $drm->declarant->comptabilite->code_postal ?> <?php echo $drm->declarant->comptabilite->commune ?>
		<?php else: ?>
		<?php echo $drm->declarant->siege->adresse ?>, <?php echo $drm->declarant->siege->code_postal ?> <?php echo $drm->declarant->siege->commune ?>
		<?php endif; ?>
	</td>
	<td>CVI : <?php echo $drm->declarant->cvi ?></td>
</tr>
<tr>
	<td class="premier">Adresse et n°d'EA du chai : 
		<?php echo $drm->declarant->siege->adresse ?>, <?php echo $drm->declarant->siege->code_postal ?> <?php echo $drm->declarant->siege->commune ?>
	</td>
	<td>Assices : <?php echo $drm->declarant->no_accises ?></td>
</tr>
<tr>
	<td class="premier">Service des douanes de : Orange</td>
	<td></td>
</tr>
</table>
</div>