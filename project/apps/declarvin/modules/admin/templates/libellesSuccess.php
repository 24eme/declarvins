<?php include_component('global', 'navBack', array('active' => 'libelles')); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
    		<h1>Messages</h1>
    		<?php include_partial('tableLibelles', array('object' => $messages, 'type' => 'messages')) ?>
    		<h1>Droits</h1>
    		<?php include_partial('tableLibelles', array('object' => $droits, 'type' => 'droits')) ?>
    		<h1>Labels</h1>
    		<?php include_partial('tableLibelles', array('object' => $labels, 'type' => 'labels')) ?>
		</div>
	</section>
</section>