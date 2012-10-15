<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>
<section id="contenu" class="vracs">
    <div id="principal" class="produit">
        <h1>
            Contrats interprofessionnel<?php if(!$etablissement): ?> en cours de saisis<?php endif; ?> &nbsp;
            <a class="btn_ajouter" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>"></a>
        </h1>
        <?php if (!$etablissement): ?>
    	<div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form, 'route' => '@vrac_admin'))?>
        </div>
        <?php endif; ?>
        <?php include_partial('list', array('vracs' => $vracs, 'etablissement' => $etablissement)); ?>
    </div>
</section>