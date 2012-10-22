<div id="header">
   <center>
		<h1>Contrat d'achat interprofessionnel</h1>
	</center>
	<table>
	<tr>
		<td width="50%"><?php if ($vrac->premiere_mise_en_marche): ?>Première mise en marché<?php endif; ?></td>
   		<td width="50%" style="text-align: right;">Mode de saisie : DTI</td>
	</tr>
	<tr>
		<td width="50%">Saisie le <?php echo $vrac->getEuSaisieDate(); ?></td>
		<?php if ($vrac->isValide()): ?>
		<td width="50%" style="text-align: right;">N° de Visa du contrat : <?php echo $vrac->numero_contrat ?></td>
		<?php else: ?>
		<td width="50%" style="text-align: right;">&nbsp;</td>
		<?php endif; ?>
	</tr>
	<?php if ($vrac->cas_particulier != ConfigurationVrac::CAS_PARTICULIER_DEFAULT_KEY): ?>
	<tr>
		<td width="50%">Condition particulière : <?php echo $configurationVrac->formatCasParticulierLibelle(array($vrac->cas_particulier)); ?></td>
		<td width="50%">&nbsp;</td>
	</tr>
	<?php endif; ?>
</table>
</div>
