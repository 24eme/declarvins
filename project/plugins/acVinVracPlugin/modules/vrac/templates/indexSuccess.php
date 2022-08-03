<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>


<section id="contenu" class="vracs">
    <div id="principal" class="produit">

    <?php if (!$etablissement): ?>
    <div id="mon_compte">
    <?php include_partial('admin/etablissement_login_form', array('form' => $form, 'route' => '@vrac_admin'))?>
    </div>
    <?php endif; ?>

        <h1>
            Contrats
            <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <a class="btn_ajouter" href="<?php echo url_for('interpro_upload_csv_vrac_prix') ?>">Mise à jours des prix</a>
            <?php endif; ?>
        </h1>

        <ul class="nav nav-tabs text-center">
          <li class="<?php if(!$pluriannuel): ?>active<?php endif; ?>" style="float:none;display:inline-block;"><a href="<?php echo (!$etablissement)? url_for('vrac_admin') : url_for('vrac_etablissement', array('sf_subject' => $etablissement)); ?>"><h3 style="margin:0;">Ponctuels</h3></a></li>
          <li class="<?php if($pluriannuel): ?>active<?php endif; ?>" style="float:none;display:inline-block;"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => 'TOUS')) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => 'TOUS')); ?>?pluriannuel=1"><h3 style="margin:0;">Pluriannuels</h3></a></li>
        </ul>


        <ul class="nav nav-pills nav-justified" style="margin: 20px 0;">
            <li style="padding: 0 5px;" class="<?php if($statut !== 0 && $statut !== VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION && $statut !== VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?><?php else: ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin') : url_for('vrac_etablissement', array('sf_subject' => $etablissement)); ?>?pluriannuel=<?php echo $pluriannuel ?>">En attente</a></li>
            <li style="padding: 0 5px;" class="<?php if($statut !== VracClient::STATUS_CONTRAT_NONSOLDE): ?><?php else: ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_NONSOLDE)) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE)); ?>?pluriannuel=<?php echo $pluriannuel ?>">Non soldé</a></li>
            <li style="padding: 0 5px;" class="<?php if($statut !== VracClient::STATUS_CONTRAT_SOLDE): ?><?php else: ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_SOLDE)) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => VracClient::STATUS_CONTRAT_SOLDE)); ?>?pluriannuel=<?php echo $pluriannuel ?>">Soldé</a></li>
            <li style="padding: 0 5px;" class="<?php if($statut !== VracClient::STATUS_CONTRAT_ANNULE): ?><?php else: ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_ANNULE)) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => VracClient::STATUS_CONTRAT_ANNULE)); ?>?pluriannuel=<?php echo $pluriannuel ?>">Annulé</a></li>
            <li style="padding: 0 5px;" class="<?php if($statut !== 'TOUS'): ?><?php else: ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => 'TOUS')) : url_for('vrac_etablissement', array('sf_subject' => $etablissement, 'statut' => 'TOUS')); ?>?pluriannuel=<?php echo $pluriannuel ?>">Tous</a></li>
            <?php if (!$etablissement || $etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
            <li style="padding: 0 5px;"><a style="background-color:#ec971f" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>?pluriannuel=<?php echo $pluriannuel ?>"><span class="glyphicon glyphicon-plus-sign"></span> Nouveau</a></li>
            <?php endif; ?>
        </ul>


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
