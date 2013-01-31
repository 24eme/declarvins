<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'partenaires')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Ajout d'un compte partenaire</h1>
    <div id="compteAjout">
        <?php include_partial('ComptePartenaire/formCompteAjout', array('form' => $form))   ?>
    </div>
</div>
</section>
