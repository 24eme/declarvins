<?php include_component('global', 'navTop', array('active' => 'drm')); ?>


<section id="contenu">

    <!-- #principal -->
    <section id="principal">

        
        <div id="contenu_onglet">
        
        	<?php echo $cielResponse; ?>

            <div id="btn_etape_dr">
            	<a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>" class="btn_suiv"><span>Retour à mon espace</span></a>
            </div>
        </div> 

    </section>
</section>
