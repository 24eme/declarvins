<?php include_component('global', 'navBack', array('active' => 'comptes')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<h1>Recherche</h1>
    <div id="mon_compte">
        <?php include_partial('compte_recherche_form', array('form' => $form))?>
    </div>
    <a href="<?php echo url_for("compte_ajout") ?>">Nouveau</a>
</div>
</section>