<?php include_component('global', 'navBack', array('active' => 'etablissement')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<h1>Connexion</h1>
    <h2><a href="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>">Gestion CSV</a></h2>&nbsp;
    <div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form))?>
    </div>
</div>
</section>