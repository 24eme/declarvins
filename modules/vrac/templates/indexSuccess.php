<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>
<section id="contenu" class="vracs">
    <div id="principal" class="produit">
        <h1>
            Contrats interprofessionnels &nbsp;
            <?php if (!$etablissement || $etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
            <a class="btn_ajouter" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>">Ajouter</a>
        	<?php endif; ?>
        </h1>
        <?php if (!$etablissement): ?>
    	<div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form, 'route' => '@vrac_admin'))?>
        </div>
        <br /><br />
        <?php endif; ?>
        <div>
        	<a href="<?php echo url_for('vrac_admin') ?>"<?php if($statut == VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?> class="active"<?php endif; ?>>En attente de validation</a> | 
        	<a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_SOLDE)) ?>"<?php if($statut == VracClient::STATUS_CONTRAT_SOLDE): ?> class="active"<?php endif; ?>>Soldé</a> |
        	<a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_NONSOLDE)) ?>"<?php if($statut == VracClient::STATUS_CONTRAT_NONSOLDE): ?> class="active"<?php endif; ?>>Non soldé</a> |
        	<a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_ANNULE)) ?>"<?php if($statut == VracClient::STATUS_CONTRAT_ANNULE): ?> class="active"<?php endif; ?>>Annulé</a>
        </div>
        <?php include_partial('list', array('vracs' => $vracs, 'etablissement' => $etablissement)); ?>
    </div>
</section>