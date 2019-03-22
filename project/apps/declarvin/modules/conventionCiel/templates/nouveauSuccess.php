<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>

<style>
#creation_compte .ligne_form label {
	width: 195px;
}
#creation_compte .ligne_form input[type="text"], #creation_compte .ligne_form input[type="password"], #creation_compte .ligne_form select {
	width: 215px;
}

#creation_compte .radio_align label {
	width: auto !important;
}

#creation_compte .col {
	padding: 0 5px;
}
#creation_compte #infos_etablissements {
	width: 425px !important;
	margin: 0px !important;
	padding: 0 5px !important;
}
#creation_compte #infos_etablissements .etablissement {
	display:block;
	width: auto !important;
	margin: 0 0 20px 0 !important;
}
#creation_compte .ligne_btn {
	margin: 10px 0 0 0 !important;
}
</style>

<section id="contenu">
	<form id="creation_compte" method="post" action="<?php echo url_for('convention_nouveau', $etablissement) ?>">

		<input id="contrat_nb_etablissement" type="hidden" name="nb_etablissement" value="<?php echo $nbEtablissement ?>" />
		<h1><strong>Étape 1 :</strong> Identification de l'opérateur</h1>
		<?php echo $form->renderHiddenFields(); ?>
		<?php echo $form->renderGlobalErrors(); ?>
        <p class="intro">Afin d'identifier l'opérateur bénéficiaire, veuillez remplir les champs suivants :</p>
		<div class="col">
			<h1><strong>Bénéficiaire</strong></h1>
			<div class="ligne_form">
				<?php echo $form['raison_sociale']->renderError() ?>
				<?php echo $form['raison_sociale']->renderLabel() ?>
				<?php echo $form['raison_sociale']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['no_operateur']->renderError() ?>
				<?php echo $form['no_operateur']->renderLabel() ?>
				<?php echo $form['no_operateur']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['no_siret_payeur']->renderError() ?>
				<?php echo $form['no_siret_payeur']->renderLabel() ?>
				<?php echo $form['no_siret_payeur']->render() ?>
			</div>
		    <div class="ligne_form">
		        <?php echo $form['adresse']->renderError() ?>
		        <?php echo $form['adresse']->renderLabel() ?>
		        <?php echo $form['adresse']->render() ?>
		    </div>
		    <div class="ligne_form">
		        <?php echo $form['code_postal']->renderError() ?>
		        <?php echo $form['code_postal']->renderLabel() ?>
		        <?php echo $form['code_postal']->render() ?>
		    </div>
		    <div class="ligne_form">
		        <?php echo $form['commune']->renderError() ?>
		        <?php echo $form['commune']->renderLabel() ?>
		        <?php echo $form['commune']->render() ?>
		    </div>
		    <div class="ligne_form">
		        <?php echo $form['email_beneficiaire']->renderError() ?>
		        <?php echo $form['email_beneficiaire']->renderLabel() ?>
		        <?php echo $form['email_beneficiaire']->render() ?>
		    </div>
		    <div class="ligne_form">
		        <?php echo $form['telephone_beneficiaire']->renderError() ?>
		        <?php echo $form['telephone_beneficiaire']->renderLabel() ?>
		        <?php echo $form['telephone_beneficiaire']->render() ?>
		    </div>
		    <div class="ligne_form">
		        <?php echo $form['date_fin_exercice']->renderError() ?>
		        <?php echo $form['date_fin_exercice']->renderLabel() ?>
		        <?php echo $form['date_fin_exercice']->render(array('class' => 'datepicker')) ?>
		    </div>
		    <div class="ligne_form">
		        <?php echo $form['date_ciel']->renderError() ?>
		        <?php echo $form['date_ciel']->renderLabel() ?>
		        <?php echo $form['date_ciel']->render(array('class' => 'datepicker')) ?>
		    </div>
		</div>
		
		<div id="infos_etablissements" class="col">
			<h1 style="margin: 0 0 22px 0;">&nbsp;</h1>
			<?php if (isset($form['interpro'])): ?>
			<div class="ligne_form radio_align">
				<?php echo $form['interpro']->renderError() ?>
				<?php echo $form['interpro']->renderLabel() ?>
				<?php echo $form['interpro']->render() ?>
			</div>
			<?php endif; ?>
			<?php foreach ($form['etablissements'] as $id => $etablissement) : ?>
				<?php include_partial('etablissement', array('form' => $etablissement, 'etablissement' => $form->getObject()->etablissements->get($id))); ?>
			<?php endforeach; ?>    
		</div>
		
		
		<div class="col">
			<h1><strong>Signataire</strong></h1>
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
				<?php echo $form['email']->renderError() ?>
				<?php echo $form['email']->renderLabel() ?>
				<?php echo $form['email']->render() ?>
			</div>
			<div class="ligne_form">
				<?php echo $form['telephone']->renderError() ?>
				<?php echo $form['telephone']->renderLabel() ?>
				<?php echo $form['telephone']->render() ?>
			</div>
			<div class="ligne_form radio_align">
				<?php echo $form['representant_legal']->renderError() ?>
				<?php echo $form['representant_legal']->renderLabel() ?>
				<?php echo $form['representant_legal']->render() ?>
			</div>
		</div>
		<div class="col">
			<h1>&nbsp;</h1>
			<p style="margin: 0 0 15px;">Si vous avez reçu un mandat pour déclarer et/ou régler les contributions indirectes pour le compte d'une société française, merci de bien vouloir le préciser ci-dessous.</p>
			<div class="ligne_form">
				<?php echo $form['mandataire']->renderError() ?>
				<?php echo $form['mandataire']->renderLabel() ?>
				<?php echo $form['mandataire']->render() ?>
			</div>
		</div>
		<div style="text-align: right;">
			<strong class="champs_obligatoires">* Champs obligatoires</strong>
		</div>
		<div class="ligne_btn">
			<button type="submit" class="btn_suiv"><span>Suivant</span></button>
		</div>
	</form>
</section>
<!-- fin #contenu -->