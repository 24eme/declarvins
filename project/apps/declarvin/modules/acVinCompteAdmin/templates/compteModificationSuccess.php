<?php include_component('global', 'navBack', array('active' => 'comptes')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Compte</h1>
    <div id="compteModification">
        <?php include_partial('acVinCompteAdmin/formCompteModification', array('form' => $form))?>
    </div>
</div>
</section>

