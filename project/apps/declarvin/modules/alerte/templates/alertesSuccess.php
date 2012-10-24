<?php include_component('global', 'navBack', array('active' => 'alertes', 'subactive' => '')); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
			<?php include_partial('form', array('form' => $form)) ?>
    		<h1>Drms manquantes</h1>
    		<?php include_partial('list', array('type' => DRMsManquantes::ALERTE_DOC_ID, 'alertes' => $drms_manquantes)) ?>
    	</div>
	</section>
</section>