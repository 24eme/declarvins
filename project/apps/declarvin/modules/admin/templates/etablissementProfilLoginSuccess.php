<?php include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => 'profil')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<h1>Connexion</h1>
    <div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form))?>
    </div>
</div>
</section>