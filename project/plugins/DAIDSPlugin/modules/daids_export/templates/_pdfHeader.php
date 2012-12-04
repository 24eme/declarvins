<div id="header">
   <center>
<h1>
<?php if (!$daids->valide->date_saisie) {
   echo '<span class="rectificative">Brouillon&nbsp;: </span> ';
 }?><?php if ($daids->mode_de_saisie == DAIDSClient::MODE_DE_SAISIE_PAPIER): ?>Extrait de la d<?php else: ?>D<?php endif; ?>éclaration annuelle d'inventaire / déclaration de stocks de <?php echo $daids->periode; ?> 
<?php if($daids->isRectificative()): ?>
 - <span class="rectificative">Rectificative <?php echo sprintf('%02d', $daids->rectificative) ?></span>
<?php endif; ?>
<br/>Vins de la vallées du Rhône et de Provence (Inter-Rhône, CIVP, InterVins SE)
</h1></center>
<table>
<tr>
	<td class="premier"><strong>Nom / Raison sociale : <?php echo $daids->declarant->nom ?></strong></td>
   <td>n° DAI/DS : <?php echo preg_replace('/DAIDS-/', '', $daids->_id); ?><?php if($daids->isValidee()): ?> (<?php if ($daids->mode_de_saisie == DAIDSClient::MODE_DE_SAISIE_PAPIER): ?>saisie interne le<?php else: ?>validée le<?php endif;?> <?php echo $daids->getEuValideDate(); ?>)<?php endif; ?></td>
</tr>
<tr>
	<td class="premier" >Lieu où est tenue la comptabilité matière :
		<?php if ($daids->declarant->comptabilite->adresse): ?>
		<?php echo $daids->declarant->comptabilite->adresse ?>, <?php echo $daids->declarant->comptabilite->code_postal ?> <?php echo $daids->declarant->comptabilite->commune ?> <?php echo $daids->declarant->comptabilite->pays ?>
		<?php else: ?>
		<?php echo $daids->declarant->siege->adresse ?>, <?php echo $daids->declarant->siege->code_postal ?> <?php echo $daids->declarant->siege->commune ?> <?php echo $daids->declarant->siege->pays ?>
		<?php endif; ?>
	</td>
	<td>
		<?php if($daids->declarant->siret): ?>
			SIRET : <?php echo $daids->declarant->siret ?>
		<?php elseif($daids->declarant->cni): ?>
			CNI : <?php echo $daids->declarant->cni ?>
		<?php else: ?>
			SIRET :
		<?php endif; ?>
	</td>
</tr>
<tr>
	<td class="premier">Adresse et n° d'EA du chai : 
		<?php echo $daids->declarant->siege->adresse ?>, <?php echo $daids->declarant->siege->code_postal ?> <?php echo $daids->declarant->siege->commune ?> <?php echo $daids->declarant->siege->pays ?>
	</td>
	<td>Accises : <?php echo $daids->declarant->no_accises ?></td>
</tr>
<tr>
	<td class="premier">Service des douanes de : <?php echo $daids->declarant->service_douane ?></td>
	<td>CVI : <?php echo $daids->declarant->cvi ?></td>
</tr>
</table>
</div>
