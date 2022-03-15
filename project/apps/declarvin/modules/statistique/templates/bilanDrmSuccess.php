<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'bilan_drm')); ?>
<section id="contenu">
    <section id="principal">
        <div class="clearfix" id="application_dr">
            <h1>Etat des DRM saisies</h1>
            <p>
                <?php foreach (array_values(DRMClient::getAllLibellesStatusBilan()) as $num => $libelle): ?>
                    <?php if ($num == 1): ?><font color="green"><?php endif; ?>
                    <strong><?php echo $num; ?></strong> : <?php echo $libelle; ?>
                    <?php if ($num == 1): ?></font><?php endif; ?>
                    <br />
                <?php endforeach; ?>
            </p>
            <br />
            <div class="popup_form contenu clearfix">
                <p class="ligne_form_btn" style="text-align: left;">
                    <a class="btn_valider" href="/exports/BILANDRM/<?php echo $interpro->identifiant ?>/">&raquo; Accéder aux fichiers de Bilan</a>
                </p>
            </div>
        </div>
    </section>
</section>
