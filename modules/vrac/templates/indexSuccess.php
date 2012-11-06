<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>
<section id="contenu" class="vracs">
    <div id="principal" class="produit">
        <h1>
            Contrats interprofessionnels<?php if(!$etablissement): ?> en cours de saisies<?php endif; ?> &nbsp;
            <a class="btn_ajouter" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>">Ajouter</a>
        </h1>
        <?php if (!$etablissement): ?>
    	<div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form, 'route' => '@vrac_admin'))?>
        </div>
        <br /><br />
        <?php endif; ?>
        <?php include_partial('list', array('vracs' => $vracs, 'etablissement' => $etablissement)); ?>
    </div>
</section>