<div id="header">
   <center>
<h1>
<?php if (!$daids->valide->date_saisie) {
   echo '<span class="rectificative">Brouillon&nbsp;: </span> ';
 }?><?php if ($daids->mode_de_saisie == DAIDSClient::MODE_DE_SAISIE_PAPIER): ?>Extrait de la d<?php else: ?>D<?php endif; ?>éclaration annuelle d'inventaire / déclaration de stocks de <?php echo $daids->periode; ?> 
<?php if($daids->isRectificative()): ?>
 - <span class="rectificative">Rectificative <?php echo sprintf('%02d', $daids->rectificative) ?></span>
<?php endif; ?>
<br/>Vins de la vallée du Rhône et de Provence (Inter-Rhône, CIVP, InterVins SE)
</h1></center>
<table>
<tr>
	<td class="premier">Nom / Raison sociale : <strong><?php echo $daids->declarant->nom ?></strong></td>
   <td>N° DAI/DS : <strong><?php echo preg_replace('/DAIDS-/', '', $daids->_id); ?></strong><?php if($daids->isValidee()): ?> (<?php if ($daids->mode_de_saisie == DAIDSClient::MODE_DE_SAISIE_PAPIER): ?>saisie interne le<?php else: ?>validée le<?php endif;?> <strong><?php echo $daids->getEuValideDate(); ?></strong>)<?php endif; ?></td>
</tr>
<tr>
	<td class="premier" >Lieu où est tenue la comptabilité matière :
		<strong>
		<?php if ($daids->declarant->comptabilite->adresse): ?>
		<?php echo $daids->declarant->comptabilite->adresse ?>, <?php echo $daids->declarant->comptabilite->code_postal ?> <?php echo $daids->declarant->comptabilite->commune ?> <?php echo $daids->declarant->comptabilite->pays ?>
		<?php else: ?>
		<?php echo $daids->declarant->siege->adresse ?>, <?php echo $daids->declarant->siege->code_postal ?> <?php echo $daids->declarant->siege->commune ?> <?php echo $daids->declarant->siege->pays ?>
		<?php endif; ?>
		</strong>
	</td>
	<td>
		<?php if($daids->declarant->siret): ?>
			N° SIRET : <strong><?php echo $daids->declarant->siret ?></strong>
		<?php elseif($daids->declarant->cni): ?>
			N° CNI : <strong><?php echo $daids->declarant->cni ?></strong>
		<?php else: ?>
			N° SIRET :
		<?php endif; ?>
	</td>
</tr>
<tr>
	<td class="premier">Adresse du chai : 
		<strong><?php echo $daids->declarant->siege->adresse ?>, <?php echo $daids->declarant->siege->code_postal ?> <?php echo $daids->declarant->siege->commune ?> <?php echo $daids->declarant->siege->pays ?></strong>
	</td>
	<td>N° accises / EA : <strong><?php echo $daids->declarant->no_accises ?></strong></td>
</tr>
<tr>
	<td class="premier">Service des douanes de : <strong><?php echo $daids->declarant->service_douane ?></strong></td>
	<td>N° CVI : <strong><?php echo $daids->declarant->cvi ?></strong></td>
</tr>
</table>
</div>
