<p><span>Nom : </span><?php echo $etablissement->getNom() ?></p>
<p><span>Interpro : </span><?php //echo $etablissement->getInterproObj()->nom; ?></p>
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
<p><span>Siret : </span><?php echo $etablissement->getSiret() ?></p>
<div class="btn">
	<?php if ($etablissement->statut != _Tiers::STATUT_ARCHIVER): ?>
    <a href="<?php echo url_for('validation_archiver', array('etablissement' => $etablissement->getIdentifiant())) ?>" class="modifier">Archiver</a> | 
    <?php endif; ?>
    <a href="<?php echo url_for('validation_delier', array('etablissement' => $etablissement->getIdentifiant())) ?>" class="modifier">Délier</a>
</div>
<?php endif; ?>