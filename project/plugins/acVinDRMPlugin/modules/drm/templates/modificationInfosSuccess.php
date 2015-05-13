<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php //include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'informations', 'pourcentage' => '5')); ?>

    <!-- #principal -->
    <section id="principal">
        <div id="application_dr">
            <div id="drm_informations">
                <p>Si vous souhaitez modifier vos informations de structure, merci de prendre contact avec votre Interpro.</p>
                <br /><br />
                <a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>" class="btn_suiv">
                    <span>Retour à mon espace</span>
                </a>
            </div>
        </div>
    </section>
</section>
