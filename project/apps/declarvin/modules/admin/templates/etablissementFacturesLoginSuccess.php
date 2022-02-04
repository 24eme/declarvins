 <?php include_component('global', 'navTop', array('active' => 'factures')); ?>
<section id="contenu">
    <div class="clearfix" id="application_dr">
        <div id="mon_compte">
            <?php include_partial('admin/etablissement_login_form', array('form' => $form, 'route' => '@etablissement_login_factures')) ?>
        </div>
    </div>
</section>
