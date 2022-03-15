<cas:entities>
<?php if ($compte->exist("tiers") && $compte->tiers): ?>
<?php foreach ($compte->tiers as $k => $t): $e = EtablissementClient::getInstance()->find($t->id); ?>
    <cas:entity>
        <cas:raison_sociale><?php echo $t->raison_sociale; ?></cas:raison_sociale>
<?php if ($e->cvi): ?>
        <cas:cvi><?php echo $e->cvi; ?></cas:cvi>
<?php endif; ?>
<?php if ($e->siret): ?>
        <cas:siret><?php echo $e->siret; ?></cas:siret>
<?php endif; ?>
<?php if ($e->no_accises): ?>
        <cas:accise><?php echo $e->no_accises; ?></cas:accise>
<?php endif; ?>
<?php if ($e->no_tva_intracommunautaire): ?>
        <cas:tva><?php echo $e->no_tva_intracommunautaire; ?></cas:tva>
<?php endif; ?>
    <cas:entity>
<?php endforeach; ?>
<?php else: ?>
    <cas:entity>
        <cas:raison_sociale><?php echo $compte->nom; ?></cas:raison_sociale>
        <cas:email><?php echo $compte->email; ?></cas:email>
    </cas:entity>
<?php endif; ?>
</cas:entities>