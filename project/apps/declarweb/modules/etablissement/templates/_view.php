<p><span>Nom : </span><?php echo $etablissement->getNom() ?></p>
<p><span>Interpro : </span><?php echo $etablissement->getInterproObj()->nom; ?></p>
<?php if ($interpro->get('_id') == $etablissement->getInterpro()): ?>
<p><span>Siret : </span><?php echo $etablissement->getSiret() ?></p>
<?php endif; ?>