<strong><?php echo $etablissement->getInterproObject()->nom; ?></strong><br />
<?php echo $etablissement->nom ?> <?php if ($etablissement->siret): ?>(<?php echo $etablissement->siret ?>)<?php endif; ?>
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
    <?php if ($etablissement->siege->adresse): ?>
        <br /><br />
        <?php echo $etablissement->siege->adresse ?>
        <br />
        <?php echo $etablissement->siege->code_postal ?> 
    <?php endif; ?>
    <?php echo $etablissement->siege->commune ?>
    <br /><br />
    Familles : <?php echo $etablissement->famille ?> / <?php echo $etablissement->sous_famille ?>
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
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<br /><br />
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
    <div class="btn">
        <?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVER): ?>
            <a class="btn_valider" href="<?php echo url_for('validation_archiver', array('etablissement' => $etablissement->getIdentifiant(), 'num_contrat' => $contrat->no_contrat)) ?>" class="modifier">Archiver</a> 
        <?php else: ?>
            <a class="btn_valider" href="<?php echo url_for('validation_desarchiver', array('etablissement' => $etablissement->getIdentifiant(), 'num_contrat' => $contrat->no_contrat)) ?>" class="modifier">Désarchiver</a> 
        <?php endif; ?>
        <a class="btn_valider" href="<?php echo url_for('validation_delier', array('etablissement' => $etablissement->getIdentifiant(), 'num_contrat' => $contrat->no_contrat)) ?>" class="modifier">Délier</a>
    </div>
<?php endif; ?>