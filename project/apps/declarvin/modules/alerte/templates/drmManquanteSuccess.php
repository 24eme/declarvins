<?php include_component('global', 'navBack', array('active' => 'alertes', 'subactive' => '')); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
    		<h1>DRMS MANQUANTES</h1>
    		<?php include_partial('list', array('alertes' => $alertes)) ?>
    	</div>
	</section>
</section>