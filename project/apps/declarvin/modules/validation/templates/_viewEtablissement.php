<strong><?php echo $etablissement->identifiant ?></strong> - <?php echo $etablissement->raison_sociale ?><br /><br />
SIRET : <?php echo $etablissement->siret ?><br />
CVI : <?php echo $etablissement->cvi ?><br />
EA : <?php echo $etablissement->no_accises ?><br />
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
    <?php if ($etablissement->siege->adresse): ?>
        <br />
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
    <?php 
    	if ($etablissement->exist('zones')): 
    		$zones = array();
            foreach ($etablissement->zones as $zone): $zones[] = $zone->libelle; endforeach;
    ?>
    <br />
    Zones : <?php echo implode(', ', $zones); ?>
    <?php endif; ?>
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
        <?php if (!$compte->tiers->exist($etablissement->_id)): ?>
            <a class="btn_valider" href="<?php echo url_for('validation_lier', array('etablissement' => $etablissement->getIdentifiant(), 'num_contrat' => $contrat->no_contrat)) ?>" class="modifier">Lier</a> 
        <?php else: ?>
            <a class="btn_valider" href="<?php echo url_for('validation_delier', array('etablissement' => $etablissement->getIdentifiant(), 'num_contrat' => $contrat->no_contrat)) ?>" class="modifier">Délier</a> 
        <?php endif; ?>
    </div>
<?php endif; ?>