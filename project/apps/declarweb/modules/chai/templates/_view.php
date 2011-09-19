<p><span>Nom : </span><?php echo $chai->getNom() ?></p>
<p><span>Interpro : </span><?php echo $chai->getInterproObj()->nom; ?></p>
<?php if ($interpro->get('_id') == $chai->getInterpro()): ?>
<p><span>Siret : </span><?php echo $chai->getSiret() ?></p>
<div class="btn">
    <a href="<?php echo url_for('compte_update_chai', array('id' => $chai->getIdentifiant())) ?>" class="modifier">Modifier</a>
</div>
<?php endif; ?>