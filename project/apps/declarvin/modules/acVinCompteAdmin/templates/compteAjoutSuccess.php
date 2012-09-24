<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'comptes')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Ajout d'un compte</h1>
    <div id="compteAjout">
        <?php include_partial('acVinCompteAdmin/formCompteAjout', array('form' => $form))   ?>
    </div>
</div>
</section>
