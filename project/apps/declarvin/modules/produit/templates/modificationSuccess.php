<?php include_partial('global/navBack', array('active' => 'produits')); ?>
<section id="contenu">
	<?php if ($sf_user->hasFlash('notice')){ ?>
    <div id="flash_message">
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice'); ?></div>
    </div>
	<?php } ?>
	<section id="principal">
	<div class="clearfix" id="application_dr">
	    <h1>Modification du noeud <strong><?php echo $noeud ?></strong>.</h1>
	    <?php include_partial('produit/popup', array('form' => $form)) ?>
	</div>
	</section>
</section>