<?php include_component('global', 'navBack', array('active' => 'contrat')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Contrat</h1>
    <h2><a href="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>">Gestion CSV</a></h2>&nbsp;
    <div id="mon_compte">
        <?php include_partial('validation/form', array('form' => $formLogin))?>
    </div>
</div>
</section>