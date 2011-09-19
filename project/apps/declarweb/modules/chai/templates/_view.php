<p><span>Nom : </span><?php echo $chai->getNom() ?></p>
<p><span>Siret : </span><?php echo $chai->getSiret() ?></p>
<?php if ($interpro->get('_id') == $chai->getInterpro()): ?>
<div class="btn">
    <a href="<?php echo url_for('compte_update_chai', array('id' => $chai->getIdentifiant())) ?>" class="modifier">Modifier</a>
</div>
<?php endif; ?>