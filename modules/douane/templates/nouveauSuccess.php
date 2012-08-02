<?php include_component('global', 'navBack', array('active' => 'douanes')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Nouveau</h1>
	    <form class="popup_form" id="form_ajout" action="<?php echo url_for('douane_nouveau') ?>" method="post">
	    <?php include_partial('douane/form', array('form' => $form)) ?>
	    </form>
	</div>
	</section>
</section>