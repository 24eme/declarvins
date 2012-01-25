<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
		<div id="application_dr">
	        
	    </div>
        <div id="btn_etape_dr">
			<a href="<?php echo url_for('drm_recap', ConfigurationClient::getCurrent()->declaration->certifications->AOP) ?>" class="btn_prec">Précédent</a>
		</div>
    </section>
</section>
