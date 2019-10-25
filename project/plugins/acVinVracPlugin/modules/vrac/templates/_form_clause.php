<?php $clauses = $configurationVrac->clauses ?>
<?php $clauses_complementaires = $configurationVrac->clauses_complementaires ?>

<form method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
<?= $form->renderHiddenFields(); ?>
<h1 style="margin: 0px 0px 0px 0px">Clauses</h1>
<?php foreach ($clauses as $clause): ?>
    <h2><?= $clause['nom'] ?></h2>
    <p><?= $clause['description'] ?></p>
<?php endforeach; ?>

<h1 style="margin: 15px 0px 0px 0px">Clauses complémentaires</h1>
<?php foreach ($clauses_complementaires as $key => $clause): ?>

    <h2><?= $clause['nom'] ?></h2>
    <p><?= html_entity_decode($clause['description']) ?></p>

    <div class="section_label" style="text-align: right; padding: 10px 0;">
        <?= $form[$key]->renderError() ?>
        <span><?= $form[$key]->render() ?> <?= $form[$key]->renderLabel() ?></span>
    </div>
<?php endforeach; ?>

<?php if ($configurationVrac->informations_complementaires): ?>
<h1>Informations complémentaires</h1>
<?= html_entity_decode($configurationVrac->informations_complementaires); ?>
<?php endif; ?>

<h1>Autres conditions</h1>
<?= $form['autres_conditions']->render(array('style' => 'width:99%;height:120px;')) ?>

<div class="ligne_form_btn">
	<?php if($form->getObject()->has_transaction): ?>
		<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
	<?php else: ?>
		<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
	<?php endif; ?>
    <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
</div>
