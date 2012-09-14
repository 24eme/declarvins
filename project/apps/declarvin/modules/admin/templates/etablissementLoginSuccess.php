<?php include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => 'etablissement')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<h1>Connexion</h1>
    <div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form))?>
    </div>
    <br /><br />
    <h1>Mettre à jour les données</h1>
    <a href="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>">Importer les données GRC</a>
</div>
</section>