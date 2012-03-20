<div id="header">
<h1>Déclaration récapitulative mensuelle de mars 2012</h1>
<p class="date_validation">Déclaration validée le 01/12/2012</p>
<table>
<tr>
	<td class="premier">Nom / Raison sociale : <?php echo $drm->declarant->nom ?></td>
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
</table>
</div>