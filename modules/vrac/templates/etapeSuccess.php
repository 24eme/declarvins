<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs vrac_<?php echo $etape ?>">
    <?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>
	<?php include_partial('form_'.$etape, array('form' => $form, 'etape' => $etape, 'configurationVrac' => $configurationVrac)) ?>
</section>