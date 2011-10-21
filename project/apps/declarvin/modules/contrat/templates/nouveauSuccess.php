<section id="contenu">
<?php if($sf_user->hasFlash('notice')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>
<form id="creation_compte"  method="post" action="<?php echo url_for('@contrat_nouveau') ?>">
	<h1>Étape 1 : <strong>Création de compte</strong></h1>
	<p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
	<div class="col">
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
	</div>
	<div class="col">
		<h2>Etablissement</h2>
		<div id="etablissements">
			<div id="etablissement0">
				<div class="ligne_form">
					<?php echo $form['etablissements'][0]['raison_sociale']->renderError() ?>
					<?php echo $form['etablissements'][0]['raison_sociale']->renderLabel() ?>
					<?php echo $form['etablissements'][0]['raison_sociale']->render() ?>
				</div>
				<div class="ligne_form">
					<?php echo $form['etablissements'][0]['siret_cni']->renderError() ?>
					<?php echo $form['etablissements'][0]['siret_cni']->renderLabel() ?>
					<?php echo $form['etablissements'][0]['siret_cni']->render() ?>
				</div>
			</div>
			<div class="ligne_form ligne_form_large">
				<label for="champ_8-1">Souhaitez-vous représenter d'autres établissements ?</label>
				<div class="champ_form champ_form_radio_cb">
					<input type="radio" id="r1" name="etablissement_supplementaire" value="Oui" onclick="addEtablissement()"<?php if ($nbEtablissement > 1): ?> checked="checked"<?php endif;?> />
					<label for="r1">Oui</label>
					<input type="radio" id="r2" name="etablissement_supplementaire" value="Non" onclick="removeAllEtablissement()"<?php if ($nbEtablissement == 1): ?> checked="checked"<?php endif;?> />
					<label for="r2">Non</label>
				</div>
			</div>
			<?php 
				$i=0;
				$first = true;
				foreach ($form['etablissements'] as $etablissement): 
				if (!$first):
			?>
			<div id="etablissement<?php echo $i ?>">
				<div class="ligne_form">
					<?php echo $etablissement['raison_sociale']->renderError() ?>
					<?php echo $etablissement['raison_sociale']->renderLabel() ?>
					<?php echo $etablissement['raison_sociale']->render() ?>
				</div>
				<div class="ligne_form">
					<?php echo $etablissement['siret_cni']->renderError() ?>
					<?php echo $etablissement['siret_cni']->renderLabel() ?>
					<?php echo $etablissement['siret_cni']->render() ?>
				</div>
				<div id="optionsEtablissement<?php echo $i ?>">
					<a href="javascript:removeEtablissement(<?php echo $i ?>)">Supprimer</a>
				</div>
			</div>
			<?php 
				endif;
				if ($first)
					$first = false;
				$i++; 
				endforeach; 
			?>
		</div>
		<a id="addEtablissement" href="javascript:addEtablissement()"<?php if ($nbEtablissement < 2): ?> style="display: none;"<?php endif; ?>>Ajouter un autre établissement</a>
		
		<div class="ligne_btn">
			<button type="submit" class="btn_valider">Valider</button>
		</div>
	</div>
</form>
</section>
<!-- fin #contenu -->