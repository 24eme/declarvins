<strong><?php echo $etablissement->getInterproObject()->nom; ?></strong><br />
<?php echo $etablissement->nom ?> <?php if ($etablissement->siret): ?>(<?php echo $etablissement->siret ?>)<?php endif; ?>
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
    <?php if ($etablissement->siege->adresse): ?>
        <br /><br />
        <?php echo $etablissement->siege->adresse ?>
        <br />
        <?php echo $etablissement->siege->code_postal ?>&nbsp;<?php echo $etablissement->siege->commune ?>
        <br />
    	<?php echo $etablissement->siege->pays ?>
    <?php endif; ?>
    <br /><br />
    Familles : <?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?><?php if ($etablissement->sous_famille): ?> / <?php echo EtablissementFamilles::getSousFamilleLibelle($etablissement->famille, $etablissement->sous_famille) ?><?php endif; ?>
    <br />
    Douane : <?php echo $etablissement->service_douane ?>
    <?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
        <?php if ($etablissement->comptabilite->adresse || $etablissement->comptabilite->code_postal || $etablissement->comptabilite->commune): ?>
            <br /><br />
            Adresse comptabilité:
            <br />
            <?php echo $etablissement->comptabilite->adresse ?>
            <br />
            <?php echo $etablissement->comptabilite->code_postal ?> <?php echo $etablissement->comptabilite->commune ?>
            <br />
            <?php echo $etablissement->comptabilite->pays ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<br /><br />
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
    <div class="btn">
        <a class="btn_valider" href="<?php echo url_for('validation_lier', array('etablissement' => $etablissement->getIdentifiant(), 'num_contrat' => $contrat->no_contrat)) ?>" class="modifier">Lier</a>
    </div>
<?php endif; ?>