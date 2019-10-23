<?php $clauses = $configurationVrac->clauses ?>
<?php $clauses_complementaires = $configurationVrac->clauses_complementaires ?>

<form method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
<?= $form->renderHiddenFields(); ?>
<h1>Clauses</h1>
<?php foreach ($clauses as $clause): ?>
    <h2><?= $clause['nom'] ?></h2>
    <p><?= $clause['description'] ?></p>
<?php endforeach; ?>

<h1>Clauses complémentaires</h1>
<?php foreach ($clauses_complementaires as $key => $clause): ?>

    <h2><?= $clause['nom'] ?></h2>
    <p><?= $clause['description'] ?></p>

    <div class="section_label_strong bloc_condition">
        <?= $form[$key]->renderError() ?>
        <span><?= $form[$key]->render() ?> <?= $form[$key]->renderLabel() ?></span>
    </div>
<?php endforeach; ?>

<?php if ($configurationVrac->informations_complementaires): ?>
<h1>Informations complémentaires</h1>
<?= html_entity_decode($configurationVrac->informations_complementaires); ?>
<?php endif; ?>

<div class="ligne_form_btn">
    <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
    <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
</div>
