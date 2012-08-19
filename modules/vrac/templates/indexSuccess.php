<?php include_component('global', 'nav', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
    <div id="principal" class="produit">
        <h1>
            Contrat Vrac &nbsp;
            <a class="btn_ajouter" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>"></a>
        </h1>
        <?php include_partial('table_contrats', array('vracs' => $vracs, 'etablissement' => $etablissement)); ?>
    </div>
</section>