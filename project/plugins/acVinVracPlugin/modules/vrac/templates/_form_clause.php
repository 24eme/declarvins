<?php $clauses = $configurationVrac->clauses ?>
<?php $clauses_complementaires = $configurationVrac->clauses_complementaires ?>

<form method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>" enctype="multipart/form-data">
 <?php echo $form->renderHiddenFields() ?>
 <?php echo $form->renderGlobalErrors() ?>
<h1 style="margin: 0px 0px 0px 0px">Clauses</h1>
<?php foreach ($clauses as $k => $clause): ?>
    <h2><?= $clause['nom'] ?></h2>
    <p><?= $clause['description'] ?></p>
    <?php if ($k == 'resiliation'): ?>
    
    <?php if (isset($form['clause_resiliation_cas'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_resiliation_cas']->renderError() ?>
        <?php echo $form['clause_resiliation_cas']->renderLabel() ?>
        <?php echo $form['clause_resiliation_cas']->render() ?>
    </div>
    <?php endif; ?>
    <?php if (isset($form['clause_resiliation_preavis'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_resiliation_preavis']->renderError() ?>
        <?php echo $form['clause_resiliation_preavis']->renderLabel() ?>
        <?php echo $form['clause_resiliation_preavis']->render() ?>
    </div>
    <?php endif; ?>
    <?php if (isset($form['clause_resiliation_indemnite'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_resiliation_indemnite']->renderError() ?>
        <?php echo $form['clause_resiliation_indemnite']->renderLabel() ?>
        <?php echo $form['clause_resiliation_indemnite']->render() ?>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
<?php endforeach; ?>
<?php if ($clauses_complementaires): ?>
<h1 style="margin: 15px 0px 0px 0px">Clauses complémentaires</h1>
	<?php 
    foreach ($clauses_complementaires as $key => $clause): 
        if ($key == 'transfert_propriete' && $form->getObject()->type_transaction != 'vrac') continue;
    ?>

    <h2><?= $clause['nom'] ?></h2>
    <p><?= html_entity_decode($clause['description']) ?></p>

    <div class="section_label" style="text-align: right; padding: 10px 0;">
        <?= $form[$key]->renderError() ?>
        <span><?= $form[$key]->render() ?> <?= $form[$key]->renderLabel() ?></span>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($configurationVrac->informations_complementaires): ?>
<h1>Informations complémentaires</h1>
<?= html_entity_decode($configurationVrac->informations_complementaires); ?>
<?php endif; ?>

<h1>Autres conditions</h1>
<?= $form['autres_conditions']->render(array('style' => 'width:99%;height:120px;')) ?>

<?php if (isset($form['annexe_file'])): ?>
<h1 style="margin: 15px 0px 0px 0px">Annexes</h1>
<div class="section_label_strong" style="margin: 5px 0;">
    <?php echo $form['annexe_file']->renderError() ?>
    <?php echo $form['annexe_file']->renderLabel() ?>
    <?php echo $form['annexe_file']->render() ?>
</div>
<?php endif; ?>
    
<div class="ligne_form_btn">
	<?php if($form->getObject()->has_transaction): ?>
		<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
	<?php else: ?>
		<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
	<?php endif; ?>
    <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
</div>
