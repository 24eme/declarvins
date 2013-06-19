<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'oioc')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Ajout d'un compte oioc</h1>
    <div id="compteAjout">
        <?php include_partial('CompteOIOC/formCompteAjout', array('form' => $form))   ?>
    </div>
</div>
</section>
