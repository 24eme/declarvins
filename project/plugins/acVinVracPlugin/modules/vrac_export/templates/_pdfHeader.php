<div id="header">
	<div id="logo">
		<img src="<?php echo sfConfig::get('sf_web_dir')?>/images/visuels/logo_<?php echo strtolower($configurationVrac->getInterproId()) ?>.png" alt="<?php echo $configurationVrac->getInterproId() ?>" />
	</div>
	<center>
		<h1>
			<?php if ($vrac->numero_contrat): ?>Contrat<?php else: ?>Proposition<?php endif; ?><?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE): ?> ANNULÉ<?php if (!$vrac->numero_contrat): ?>E<?php endif; ?><?php else: ?><?php if ($vrac->isPluriannuel()): ?> pluriannuel cadre<?php endif; ?> de <?php echo strtolower($configurationVrac->formatTypesTransactionLibelle(array($vrac->type_transaction))); ?><?php if ($vrac->type_transaction == 'vrac'): ?> en vrac<?php endif; ?><?php endif; ?>
			<?php if($vrac->isRectificative()): ?>
 			- <span class="rectificative">Rectificatif <?php echo sprintf('%02d', $vrac->rectificative) ?></span>
			<?php endif; ?>
		</h1>
	</center>

	<table>
		<tr>
			<td width="50%"><?php if ($vrac->premiere_mise_en_marche): ?>Première mise en marché<?php endif; ?></td>
	   		<td width="50%" style="text-align: right;">Saisie <?php echo $vrac->getModeDeSaisieLibelle() ?></td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<td width="50%">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%">Saisie le <?php echo $vrac->getEuSaisieDate(); ?></td>
			<?php if ($vrac->isValide()): ?>
			<td width="50%" style="text-align: right;">N° de Visa du contrat : <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE): ?> <strike style="font-size:11px;"><?php echo $vrac->numero_contrat; ?></strike> ANNULÉ<?php else: ?><?php echo $vrac->numero_contrat; ?><?php endif; ?></td>
			<?php else: ?>
			<td width="50%" style="text-align: right;">&nbsp;</td>
			<?php endif; ?>
		</tr>
	</table>
</div>
