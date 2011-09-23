<?php 
$nbEtablissement = 0;
foreach ($contrat->etablissements as $etablissement): 
if ($etablissement->getKey() != $form->getObject()->getKey()):
$nbEtablissement++;
?>
	<p>
		Raison sociale: <?php echo $etablissement->raison_sociale ?>
		<br />
		SIRET: <?php echo $etablissement->siret ?>
	</p>
	<hr />
<?php 
endif;
endforeach;
?>
<h2>Ajouter un établissement</h2>
<form method="post" action="<?php echo url_for('@contrat_etablissement_nouveau') ?>">
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

	<div class="btn">
		<input type="submit" value="Ajouter" />
		<?php if ($nbEtablissement > 0): ?>
		<a href="<?php echo url_for('contrat_etablissement_modification', array('indice' => 0)) ?>">Etape suivante</a>
		<?php endif; ?>
	</div>
</form>
