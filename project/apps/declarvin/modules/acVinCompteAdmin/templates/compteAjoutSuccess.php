<?php include_partial('global/navBack', array('active' => 'comptes')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Ajout d'un compte</h1>
    <div id="compteAjout">
        <?php include_partial('acVinCompteAdmin/formCompteAjout', array('form' => $form))   ?>
    </div>
</div>
</section>
