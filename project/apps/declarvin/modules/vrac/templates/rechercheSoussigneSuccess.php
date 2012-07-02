<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<?php include_partial('table_contrats', array('vracs' => $vracs, 'identifiant'=>$identifiant)); ?>
</section>