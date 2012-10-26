<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'comptes')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<h1>Compte&nbsp;<a class="btn_ajouter" href="<?php echo url_for("compte_ajout") ?>">Ajouter</a></h1>
    <div id="mon_compte">
        <?php include_partial('compte_recherche_form', array('form' => $form))?>
    </div>
    
</div>
</section>