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

        <div class="filtre">
        	<?php if($statut !== 0): ?><a href="<?php echo url_for('vrac_admin') ?>">En cours de saisie</a><?php else: ?><strong>En cours de saisie</strong><?php endif; ?> |
            <?php if($statut !== VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?><a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION)) ?>">En attente de validation</a><?php else: ?><strong>En attente de validation</strong><?php endif; ?> | 
            <?php if($statut !== VracClient::STATUS_CONTRAT_SOLDE): ?><a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_SOLDE)) ?>">Soldé</a><?php else: ?><strong>Soldé</strong><?php endif; ?> | 
            <?php if($statut !== VracClient::STATUS_CONTRAT_NONSOLDE): ?><a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_NONSOLDE)) ?>">Non soldé</a><?php else: ?><strong>Non soldé</strong><?php endif; ?> | 
            <?php if($statut !== VracClient::STATUS_CONTRAT_ANNULE): ?><a href="<?php echo url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_ANNULE)) ?>">Annulé</a><?php else: ?><strong>Annulé</strong><?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php include_partial('list', array('vracs' => $vracs, 'vracs_attente' => $vracs_attente, 'etablissement' => $etablissement)); ?>
    </div>
</section>