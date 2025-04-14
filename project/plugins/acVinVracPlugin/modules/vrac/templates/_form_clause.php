<?php $clauses = $form->getObject()->clauses ?>
<?php $clauses_complementaires = $configurationVrac->clauses_complementaires; ?>

<style>
#informations_complementaires table td, #informations_complementaires table th {
    border: 1px solid #000;
}
</style>

<form method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>" enctype="multipart/form-data">
 <?php echo $form->renderHiddenFields() ?>
 <?php echo $form->renderGlobalErrors() ?>
<h1 style="margin: 0px 0px 0px 0px">Clauses<?php if ($form->getObject()->isAdossePluriannuel() && $form->configuration->isContratPluriannuelActif()): ?> prédéfinies dans le contrat pluriannuel cadre<?php endif; ?></h1>
<?php foreach ($clauses as $k => $clause): ?>
    <h2><?= $clause['nom'] ?></h2>
    <p><?= $clause['description'] ?></p>
    <?php if ($k == 'resiliation'): ?>

    <?php if (isset($form['clause_resiliation_cas'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_resiliation_cas']->renderError() ?>
        <?php echo $form['clause_resiliation_cas']->renderLabel() ?>
        <?php echo $form['clause_resiliation_cas']->render(array('style' => 'width:50%;')) ?>
    </div>
    <?php endif; ?>
    <?php if (isset($form['clause_resiliation_preavis'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_resiliation_preavis']->renderError() ?>
        <?php echo $form['clause_resiliation_preavis']->renderLabel() ?>
        <?php echo $form['clause_resiliation_preavis']->render(array('style' => 'width:50%;')) ?>
    </div>
    <?php endif; ?>
    <?php if (isset($form['clause_resiliation_indemnite'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_resiliation_indemnite']->renderError() ?>
        <?php echo $form['clause_resiliation_indemnite']->renderLabel() ?>
        <?php echo $form['clause_resiliation_indemnite']->render(array('style' => 'width:50%;')) ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php if ($k == 'revision_prix'): ?>
    <?php if (isset($form['clause_revision_prix'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_revision_prix']->renderError() ?>
        <?php echo $form['clause_revision_prix']->renderLabel() ?>
        <?php echo $form['clause_revision_prix']->render(array('style' => 'width:50%;')) ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php if ($k == 'liberte_contractuelle'): ?>

    <?php if (isset($form['clause_initiative_contractuelle_producteur'])): ?>
    <div class="section_label_strong" style="margin: 5px 0;">
        <?php echo $form['clause_initiative_contractuelle_producteur']->renderError() ?>
        <?php echo $form['clause_initiative_contractuelle_producteur']->render() ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>
<?php endforeach; ?>
<?php if (count($clauses_complementaires) > 0): ?>
<h1 style="margin: 15px 0px 0px 0px">Clauses complémentaires</h1>
	<?php
    $clausesMask = $configurationVrac->getClausesMask($form->getObject()->getClausesMaskConf())->getRawValue();
    foreach ($clauses_complementaires as $key => $clause):
        if (in_array($key, $clausesMask)) continue;
        if (!isset($form[$key])) continue;
    ?>

    <h2><?= $clause['nom'] ?></h2>
    <p><?= html_entity_decode($clause['description']) ?></p>

    <div class="section_label" style="text-align: right; padding: 10px 0;">
        <?= $form[$key]->renderError() ?>
        <span><?= $form[$key]->render(array('style' => 'margin-top: 0px;vertical-align: top;')) ?> <?= $form[$key]->renderLabel() ?></span>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($configurationVrac->informations_complementaires): ?>
<h1 style="margin: 15px 0 0 0;"><?php echo $form->getComplementsTitle() ?></h1>
<div id="informations_complementaires">
<?= html_entity_decode($configurationVrac->informations_complementaires); ?>
</div>
<?php endif; ?>

<?php if (isset($form['autres_conditions'])): ?>
<h1>Autres conditions</h1>
<?= $form['autres_conditions']->render(array('style' => 'width:99%;height:120px;')) ?>
<?php endif; ?>

<?php if (isset($form['annexe'])||isset($form['annexe_precontractuelle'])): ?>
<h1 style="margin: 15px 0px 0px 0px">Annexes</h1>
<?php if(isset($form['annexe_precontractuelle'])): ?>
<div class="section_label_strong" style="margin: 15px 0;">
    <?php echo $form['annexe_precontractuelle']->renderError() ?>
    <?php echo $form['annexe_precontractuelle']->renderLabel() ?>
    <?php echo $form['annexe_precontractuelle']->render(array('style' => 'display: inline-block;')) ?>
    <?php if($file = $form->getObject()->getAnnexeFilename('annexe_precontractuelle')): ?>
    <a style="display: inline-block;" href="<?php echo url_for('vrac_annexe', $form->getObject()) ?>?type=annexe_precontractuelle" target="_blank"><?php echo $file ?></a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if(isset($form['annexe_autre'])): ?>
<div class="section_label_strong" style="margin: 5px 0;">
    <?php echo $form['annexe_autre']->renderError() ?>
    <?php echo $form['annexe_autre']->renderLabel() ?>
    <?php echo $form['annexe_autre']->render(array('style' => 'display: inline-block;')) ?>
    <?php if($file = $form->getObject()->getAnnexeFilename('annexe_autre')): ?>
    <a style="display: inline-block;" href="<?php echo url_for('vrac_annexe', $form->getObject()) ?>?type=annexe_autre" target="_blank"><?php echo $file ?></a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>

<div class="ligne_form_btn">
	<?php if($form->getObject()->has_transaction): ?>
		<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
	<?php else: ?>
		<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
	<?php endif; ?>
    <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
</div>
