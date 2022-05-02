<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>
<section id="contenu" class="vracs">
    <div id="principal" class="produit">
        <h1>
            Contrats interprofessionnels &nbsp;
            <?php if (!$etablissement || $etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
            <a class="btn_ajouter" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>">Ajouter</a>
        	<?php endif; ?>
        	<?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
        	<a class="btn_ajouter" href="<?php echo url_for('interpro_upload_csv_vrac_prix') ?>">Mise à jours des prix</a>
        	<?php endif; ?>
        </h1>

        <?php if (!$etablissement): ?>
    	<div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form, 'route' => '@vrac_admin'))?>
        </div>
        <?php endif; ?>

        <div class="filtre" style="text-align:center; margin:50px 0;">
            <?php if($statut !== 0 && $statut !== VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION && $statut !== VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?><a style="padding:10px; margin: 2px; border: 1px solid #86005b;" href="<?php echo (!$etablissement)? url_for('vrac_admin') : url_for('vrac_etablissement', array('sf_subject' => $etablissement)); ?>">En attente</a><?php else: ?><strong style="padding:10px; margin: 2px; background-color: #86005b; color: #fff;">En attente</strong><?php endif; ?>
            <?php if($statut !== VracClient::STATUS_CONTRAT_NONSOLDE): ?><a style="padding:10px; margin: 2px; border: 1px solid #86005b;" href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_NONSOLDE)) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE)); ?>">Non soldé</a><?php else: ?><strong style="padding:10px; margin: 2px; background-color: #86005b; color: #fff;">Non soldé</strong><?php endif; ?>
            <?php if($statut !== VracClient::STATUS_CONTRAT_SOLDE): ?><a style="padding:10px; margin: 2px; border: 1px solid #86005b;" href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_SOLDE)) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => VracClient::STATUS_CONTRAT_SOLDE)); ?>">Soldé</a><?php else: ?><strong style="padding:10px; margin: 2px; background-color: #86005b; color: #fff;">Soldé</strong><?php endif; ?>
            <?php if($statut !== VracClient::STATUS_CONTRAT_ANNULE): ?><a style="padding:10px; margin: 2px; border: 1px solid #86005b;" href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_ANNULE)) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => VracClient::STATUS_CONTRAT_ANNULE)); ?>">Annulé</a><?php else: ?><strong style="padding:10px; margin: 2px; background-color: #86005b; color: #fff;">Annulé</strong><?php endif; ?>
            <?php if($statut !== 'TOUS'): ?><a style="padding:10px; margin: 2px; border: 1px solid #86005b;" href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => 'TOUS')) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => 'TOUS')); ?>">Tous</a><?php else: ?><strong style="padding:10px; margin: 2px; background-color: #86005b; color: #fff;">Tous</strong><?php endif; ?>
        </div>
        <?php if (!count($vracs)): ?>
            <p style="padding-top: 20px;">
                Aucun contrat
                <strong>
                    <?php if($statut === 0||$statut === VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION||$statut === VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?>
                        En attente
                    <?php elseif ($statut === VracClient::STATUS_CONTRAT_NONSOLDE): ?>
                        Non soldé
                    <?php elseif ($statut === VracClient::STATUS_CONTRAT_SOLDE): ?>
                        Soldé
                    <?php elseif ($statut === VracClient::STATUS_CONTRAT_ANNULE): ?>
                        Annulé
                    <?php endif; ?>
                </strong>
            </p>
        <?php else: include_partial('list', array('vracs' => $vracs, 'etablissement' => $etablissement, 'configurationProduit' => $configurationProduit)); endif; ?>
    </div>
</section>
