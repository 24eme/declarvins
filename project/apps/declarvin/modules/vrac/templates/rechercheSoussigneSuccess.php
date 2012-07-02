<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<h1>Contrat(s) vrac(s) de l'établissement n°<strong><?php echo $identifiant ?></strong></h1>
	<?php include_partial('table_contrats', array('vracs' => $vracs, 'identifiant'=>$identifiant)); ?>
</section>