<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'partenaires')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Compte partenaire</h1>
    <div id="compteModification">
        <?php include_partial('ComptePartenaire/formCompteModification', array('form' => $form))?>
		<strong class="champs_obligatoires">* Champs obligatoires</strong>
    </div>
</div>
</section>

