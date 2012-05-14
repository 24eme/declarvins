<?php include_partial('global/navBack', array('active' => 'admin')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Connexion</h1>
    <div id="mon_compte">
        <?php include_partial('admin/form', array('form' => $formLogin))?>
    </div>
</div>
</section>