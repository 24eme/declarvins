<script type="text/javascript">
	var familles = '<?php echo json_encode(sfConfig::get('app_etablissements_familles')) ?>';
</script>
<?php 
foreach ($contrat->etablissements as $etablissement): 
if ($etablissement->getKey() != $form->getObject()->getKey()):
?>
	<p>
		Raison sociale: <?php echo $etablissement->raison_sociale ?>
		<br />
		SIRET: <?php echo $etablissement->siret ?>
		<?php if ($etablissement->adresse): ?>
		<br />
		CNI: <?php echo $etablissement->cni ?>
		<br />
		CVI: <?php echo $etablissement->cvi ?>
		<br />
		Numéro accises: <?php echo $etablissement->no_accises ?>
		<br />
		Numéro TVA intracommunautaire: <?php echo $etablissement->no_tva_intracommunautaire ?>
		<br />
		Adresse: <?php echo $etablissement->adresse ?>
		<br />
		Code postal: <?php echo $etablissement->code_postal ?>
		<br />
		Commune: <?php echo $etablissement->commune ?>
		<br />
		Téléphone: <?php echo $etablissement->telephone ?>
		<br />
		Fax: <?php echo $etablissement->fax ?>
		<br />
		Email: <?php echo $etablissement->email ?>
		<br />
		Famille: <?php echo $etablissement->famille ?>
		<br />
		Sous famille: <?php echo $etablissement->sous_famille ?>
		<br />
		Service douane: <?php echo $etablissement->service_douane ?>
		<br />
		<?php if ($etablissement->comptabilite_adresse || $etablissement->comptabilite_code_postal || $etablissement->comptabilite_commune): ?>
		Comptabilité:
		<br />
		Adresse: <?php echo $etablissement->comptabilite_adresse ?>
		<br />
		Code postal: <?php echo $etablissement->comptabilite_code_postal ?>
		<br />
		Commune: <?php echo $etablissement->comptabilite_commune ?>
		<?php endif; ?>
		<?php endif; ?>
	</p>
	<hr />
<?php else: ?>
<form method="post" action="<?php echo url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey())) ?>">
	<div class="ligne_form ligne_form_label">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>

	<?php echo $form['raison_sociale']->renderError() ?>
	<?php echo $form['raison_sociale']->renderLabel() ?>
	<?php echo $form['raison_sociale']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['siret']->renderError() ?>
	<?php echo $form['siret']->renderLabel() ?>
	<?php echo $form['siret']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['cni']->renderError() ?>
	<?php echo $form['cni']->renderLabel() ?>
	<?php echo $form['cni']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['cvi']->renderError() ?>
	<?php echo $form['cvi']->renderLabel() ?>
	<?php echo $form['cvi']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['no_accises']->renderError() ?>
	<?php echo $form['no_accises']->renderLabel() ?>
	<?php echo $form['no_accises']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['no_tva_intracommunautaire']->renderError() ?>
	<?php echo $form['no_tva_intracommunautaire']->renderLabel() ?>
	<?php echo $form['no_tva_intracommunautaire']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['adresse']->renderError() ?>
	<?php echo $form['adresse']->renderLabel() ?>
	<?php echo $form['adresse']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['code_postal']->renderError() ?>
	<?php echo $form['code_postal']->renderLabel() ?>
	<?php echo $form['code_postal']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['commune']->renderError() ?>
	<?php echo $form['commune']->renderLabel() ?>
	<?php echo $form['commune']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['telephone']->renderError() ?>
	<?php echo $form['telephone']->renderLabel() ?>
	<?php echo $form['telephone']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['fax']->renderError() ?>
	<?php echo $form['fax']->renderLabel() ?>
	<?php echo $form['fax']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['email']->renderError() ?>
	<?php echo $form['email']->renderLabel() ?>
	<?php echo $form['email']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['famille']->renderError() ?>
	<?php echo $form['famille']->renderLabel() ?>
	<?php echo $form['famille']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['sous_famille']->renderError() ?>
	<?php echo $form['sous_famille']->renderLabel() ?>
	<?php echo $form['sous_famille']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['service_douane']->renderError() ?>
	<?php echo $form['service_douane']->renderLabel() ?>
	<?php echo $form['service_douane']->render() ?>
	</div>
	<p>Si l'adresse de comptabilité est différente:</p>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['comptabilite_adresse']->renderError() ?>
	<?php echo $form['comptabilite_adresse']->renderLabel() ?>
	<?php echo $form['comptabilite_adresse']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['comptabilite_code_postal']->renderError() ?>
	<?php echo $form['comptabilite_code_postal']->renderLabel() ?>
	<?php echo $form['comptabilite_code_postal']->render() ?>
	</div>
	<div class="ligne_form ligne_form_label">
	<?php echo $form['comptabilite_commune']->renderError() ?>
	<?php echo $form['comptabilite_commune']->renderLabel() ?>
	<?php echo $form['comptabilite_commune']->render() ?>
	</div>
	<div class="btn">
		<input type="submit" value="Modifier" />
	</div>
</form>
<hr />
<?php 
endif;
endforeach;
?>
