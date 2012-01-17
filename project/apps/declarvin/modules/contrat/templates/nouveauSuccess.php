<section id="contenu">
<?php if($sf_user->hasFlash('notice')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>
<form id="creation_compte" method="post" action="<?php echo url_for('@contrat_nouveau') ?>">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>
	<input id="contrat_nb_etablissement" type="hidden" name="nb_etablissement" value="<?php echo $nbEtablissement ?>" />
	<h1>Étape 1 : <strong>Création de compte</strong></h1>
	<div class="col">
		<div class="ligne_form">
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
	</div>
	<div class="col">
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
	
	<div id="infos_etablissements">
		<h2>Etablissement</h2>
		
		<a id="btn_ajouter_etablissement" href="javascript:addEtablissement()">Ajouter <span>un établissement</span></a>
		
		<div id="etablissements">
			<div class="etablissement" id="etablissement0">
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
			<?php 
				$i=0;
				$first = true;
				foreach ($form['etablissements'] as $etablissement): 
				if (!$first):
			?>
			<div class="etablissement" id="etablissement<?php echo $i ?>">
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
		
		
		<div class="ligne_btn">
			<button type="submit" class="btn_valider">Valider</button>
		</div>
	</div>
</form>
</section>
<!-- fin #contenu -->