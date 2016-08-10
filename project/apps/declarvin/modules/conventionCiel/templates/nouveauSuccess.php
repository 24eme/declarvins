<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>

<section id="contenu">
	<form id="creation_compte" method="post" action="<?php echo url_for('convention_nouveau', $etablissement) ?>">

		<input id="contrat_nb_etablissement" type="hidden" name="nb_etablissement" value="<?php echo $nbEtablissement ?>" />
		<h1><strong>Étape 1 :</strong> Création du bénéficiaire</h1>
		<?php echo $form->renderHiddenFields(); ?>
		<?php echo $form->renderGlobalErrors(); ?>
        <p class="intro">Afin d'identifier l'opérateur bénéficiaire, veuillez remplir les champs suivants :</p>
        <div class="col">
			<div class="ligne_form">
				<?php echo $form['raison_sociale']->renderError() ?>
				<?php echo $form['raison_sociale']->renderLabel() ?>
				<?php echo $form['raison_sociale']->render() ?>
			</div>
		</div>
		<div class="col">
			<div class="ligne_form">
				<?php echo $form['no_operateur']->renderError() ?>
				<?php echo $form['no_operateur']->renderLabel() ?>
				<?php echo $form['no_operateur']->render() ?>
			</div>
		</div>
		<div id="infos_etablissements">
			<h1>Etablissement<?php if(count($form['etablissements']) > 1): ?>s<?php endif; ?></h1>
			<?php foreach ($form['etablissements'] as $id => $etablissement) : ?>
				<?php include_partial('etablissement', array('form' => $etablissement, 'etablissement' => $form->getObject()->etablissements->get($id))); ?>
			<?php endforeach; ?>      
		</div>
		<?php if (isset($form['interpro'])): ?>
		<div class="col">
			<div class="ligne_form">
				<?php echo $form['interpro']->renderError() ?>
				<?php echo $form['interpro']->renderLabel() ?>
				<?php echo $form['interpro']->render() ?>
			</div>
		</div>
		<?php endif; ?>
		<p class="intro">Afin d'identifier le signataire de la convention, veuillez remplir les champs suivants :</p>
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
				<?php echo $form['email']->renderError() ?>
				<?php echo $form['email']->renderLabel() ?>
				<?php echo $form['email']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['telephone']->renderError() ?>
				<?php echo $form['telephone']->renderLabel() ?>
				<?php echo $form['telephone']->render() ?>
			</div>
		</div>
                
        <strong class="champs_obligatoires">* Champs obligatoires</strong>
		<div class="ligne_btn">
			<button type="submit" class="btn_suiv"><span>Suivant</span></button>
		</div>
	</form>
</section>
<!-- fin #contenu -->