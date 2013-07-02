<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => $type)); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
		
    		<h1><strong>DRM</strong></h1>
    		<h1>Filtres</h1>
    		<div class="contenu clearfix">
	        	<?php include_partial($form->getFormTemplate(), array('type' => $type, 'form' => $form)) ?>
	        </div>
    		
    	</div>
	</section>
</section>
<style type="text/css">
table th, table td {
	padding : 0 10px;
	text-align: left;
}
</style>