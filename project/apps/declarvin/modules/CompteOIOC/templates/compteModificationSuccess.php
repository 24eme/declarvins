<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'oioc')); ?>

<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Compte OIOC</h1>
    <div id="compteModification">
        <?php include_partial('CompteOIOC/formCompteModification', array('form' => $form))?>
		<strong class="champs_obligatoires">* Champs obligatoires</strong>
    </div>
</div>
</section>

