<h1>Etape 1 : Création de compte</h1>

<?php if($sf_user->hasFlash('notice')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>
<form method="post" action="<?php echo url_for('@contrat_nouveau') ?>">
	<div class="ligne_form">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>
	<input id="contrat_nb_etablissement" type="hidden" name="nb_etablissement" value="<?php echo $nbEtablissement ?>" />
	<?php echo $form['nom']->renderError() ?>
	<?php echo $form['nom']->renderLabel() ?>
	<?php echo $form['nom']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['prenom']->renderError() ?>
	<?php echo $form['prenom']->renderLabel() ?>
	<?php echo $form['prenom']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['fonction']->renderError() ?>
	<?php echo $form['fonction']->renderLabel() ?>
	<?php echo $form['fonction']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['telephone']->renderError() ?>
	<?php echo $form['telephone']->renderLabel() ?>
	<?php echo $form['telephone']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['fax']->renderError() ?>
	<?php echo $form['fax']->renderLabel() ?>
	<?php echo $form['fax']->render() ?>
	</div>
	<h2>Etablissements</h2>
	<div class="ligne_form">
	<table id="etablissements">
		<tr id="etablissement0">
			<td>
				<table>
					<tr>
						<td><?php echo $form['etablissements'][0]['raison_sociale']->renderLabel() ?></td>
						<td><?php echo $form['etablissements'][0]['raison_sociale']->render() ?></td>
						<td><?php echo $form['etablissements'][0]['raison_sociale']->renderError() ?></td>
					</tr>
					<tr>
						<td><?php echo $form['etablissements'][0]['siret_cni']->renderLabel() ?></td>
						<td><?php echo $form['etablissements'][0]['siret_cni']->render() ?></td>
						<td><?php echo $form['etablissements'][0]['siret_cni']->renderError() ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr id="etablissement0">
			<td>
				<table>
					<tr>
						<td>Souhaitez-vous représenter d'autres établissements ?</td>
						<td>
							<input type="radio" id="r1" name="etablissement_supplementaire" value="Oui" onclick="addEtablissement()"<?php if ($nbEtablissement > 1): ?> checked="checked"<?php endif;?> />
							<label for="r1">Oui</label>
							<input type="radio" id="r2" name="etablissement_supplementaire" value="Non" onclick="removeAllEtablissement()"<?php if ($nbEtablissement == 1): ?> checked="checked"<?php endif;?> />
							<label for="r2">Non</label>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	<?php 
		$i=0;
		$first = true;
		foreach ($form['etablissements'] as $etablissement): 
		if (!$first):
	?>
		<tr id="etablissement<?php echo $i ?>">
			<td>
				<table>
					<tr>
						<td><?php echo $etablissement['raison_sociale']->renderLabel() ?></td>
						<td><?php echo $etablissement['raison_sociale']->render() ?></td>
						<td><?php echo $etablissement['raison_sociale']->renderError() ?></td>
					</tr>
					<tr>
						<td><?php echo $etablissement['siret_cni']->renderLabel() ?></td>
						<td><?php echo $etablissement['siret_cni']->render() ?></td>
						<td><?php echo $etablissement['siret_cni']->renderError() ?></td>
					</tr>
				</table>
			</td>
		</tr>
	<?php 
		endif;
		if ($first)
			$first = false;
		$i++; 
		endforeach; 
	?>
	</table>
	<a id="addEtablissement" href="javascript:addEtablissement()"<?php if ($nbEtablissement): ?> style="display: none;"<?php endif; ?>>Ajouter un autre établissement</a>
	</div>

	<div class="btn">
		<input type="submit" value="Valider" />
	</div>
</form>
