<script type="text/javascript">
	var familles = '<?php echo json_encode(sfConfig::get('app_etablissements_familles')) ?>';
</script>
<?php if($sf_user->hasFlash('success')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('success'); ?></p>
<?php endif; ?>
<?php 
foreach ($contrat->etablissements as $etablissement): 
if ($etablissement->getKey() == $form->getObject()->getKey()):
?>

<form method="post" action="<?php echo ($recapitulatif)? url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) : url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey())); ?>">
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
		<input type="submit" value="<?php echo ($recapitulatif)? 'Modifier' : 'Ajouter'; ?>" />
		<a href="<?php echo ($recapitulatif)? url_for('contrat_etablissement_suppression', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) : url_for('contrat_etablissement_suppression', array('indice' => $etablissement->getKey())); ?>">Supprimer</a>
	</div>
</form>
<?php 
endif;
endforeach;
?>
