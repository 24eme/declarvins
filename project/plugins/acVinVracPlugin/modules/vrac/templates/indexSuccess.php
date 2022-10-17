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

        <?php if ($configurationVrac->isContratPluriannuelActif()): ?>
        <ul class="nav nav-tabs text-center">
          <li class="<?php if(!$pluriannuel): ?>active<?php endif; ?>" style="float:none;display:inline-block;"><a href="<?php echo (!$etablissement)? url_for('vrac_admin') : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant)); ?>"><h3 style="margin:0;">Ponctuels</h3></a></li>
          <li class="<?php if($pluriannuel): ?>active<?php endif; ?>" style="float:none;display:inline-block;"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => 'TOUS')) : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant, 'statut' => 'TOUS')); ?>?pluriannuel=1"><h3 style="margin:0;">Pluriannuels cadres</h3></a></li>
        </ul>
        <?php endif; ?>

        <ul class="nav nav-pills text-center" style="margin: 20px 0; justify-content: right; display: flex;">
            <?php if(!$pluriannuel): ?>
            <li style="padding: 0 5px; width: 120px;" class="<?php if(!$statut||$statut === VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION||$statut === VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin') : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant)); ?>?pluriannuel=<?php echo $pluriannuel ?>">En attente</a></li>
            <li style="padding: 0 5px; width: 120px;" class="<?php if($statut === VracClient::STATUS_CONTRAT_NONSOLDE): ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_NONSOLDE)) : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE)); ?>?pluriannuel=<?php echo $pluriannuel ?>">Non soldé</a></li>
            <li style="padding: 0 5px; width: 120px;" class="<?php if($statut === VracClient::STATUS_CONTRAT_SOLDE): ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_SOLDE)) : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant, 'statut' => VracClient::STATUS_CONTRAT_SOLDE)); ?>?pluriannuel=<?php echo $pluriannuel ?>">Soldé</a></li>
            <li style="padding: 0 5px; width: 120px;" class="<?php if($statut === VracClient::STATUS_CONTRAT_ANNULE): ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => VracClient::STATUS_CONTRAT_ANNULE)) : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant, 'statut' => VracClient::STATUS_CONTRAT_ANNULE)); ?>?pluriannuel=<?php echo $pluriannuel ?>">Annulé</a></li>
            <li style="padding: 0 5px; width: 120px;" class="<?php if($statut === 'TOUS'): ?>active<?php endif; ?>"><a href="<?php echo (!$etablissement)? url_for('vrac_admin', array('statut' => 'TOUS')) : url_for('vrac_etablissement', array('identifiant' => $etablissement->identifiant, 'statut' => 'TOUS')); ?>?pluriannuel=<?php echo $pluriannuel ?>">Tous</a></li>
            <?php endif; ?>
            <?php if (!$etablissement || $etablissement->statut != Etablissement::STATUT_ARCHIVE): ?>
            <li style="padding: 0 5px; width: 235px;">
                <?php if($pluriannuel): ?>
                    <a style="padding: 5px; background-color:#ec971f; font-weight: bold;" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>?pluriannuel=1"><span class="glyphicon glyphicon-plus-sign"></span> Nouveau contrat cadre pluriannuel</a>
                <?php else: ?>
                    <a style="padding: 5px; background-color:#ec971f; font-weight: bold;" class="btn_popup" data-popup="#popup_vrac_ponctuel_nouveau" href=""><span class="glyphicon glyphicon-plus-sign"></span> Nouveau contrat ponctuel</a>
                <?php endif; ?>
            </li>
            <?php endif; ?>
        </ul>


        <?php if (!count($vracs)): ?>
            <p style="padding-top: 20px;">
                Aucun contrat <?php if ($configurationVrac->isContratPluriannuelActif()) echo ($pluriannuel)? 'cadre pluriannuel' : 'ponctuel';  ?>
                <strong>
                    <?php if(!$statut||$statut === VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION||$statut === VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION): ?>
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
        <?php else: include_partial('list', array('vracs' => $vracs, 'etablissement' => $etablissement, 'configurationProduit' => $configurationProduit, 'pluriannuel' => $pluriannuel)); endif; ?>

    </div>
</section>
<div id="popup_vrac_ponctuel_nouveau" class="popup_contenu popup_form" style="display:none;">
    <div class="bloc_condition" data-condition-cible="#bloc_vrac_pluriannuel_choices|#bloc_vrac_ponctuel">
        <ul class="radio_list">
            <li><input name="creation_type" type="radio" value="vierge" id="creation_type_vierge" checked="checked">&nbsp;<label for="creation_type_vierge" style="font-weight:normal;">Création vierge</label></li>
            <li><input name="creation_type" type="radio" value="pluriannuel" id="creation_type_pluriannuel">&nbsp;<label for="creation_type_pluriannuel" style="font-weight:normal;">Création à partir d'un <strong>contrat pluriannuel cadre</strong></label></li>
        </ul>
    </div>
    <div id="bloc_vrac_pluriannuel_choices" class="bloc_conditionner" data-condition-value="pluriannuel">
        <form action="<?php echo url_for('vrac_pluriannuel', array('identifiant' => ($etablissement)? $etablissement->identifiant : VracRoute::ETABLISSEMENT_IDENTIFIANT_ADMIN)) ?>" method="post">
            <select name="contrat" required>
                <option value="">Selectionner un contrat</option>
                <?php foreach($pluriannuels as $pluriannuel): ?>
                <option value="<?php echo $pluriannuel->id ?>"><?php echo $pluriannuel->value[VracHistoryView::VRAC_VIEW_NUM] ?> - <?php echo substr($pluriannuel->value[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE], strpos($pluriannuel->value[VracHistoryView::VRAC_VIEW_PRODUIT_LIBELLE], ' ')) ?> <?php echo $pluriannuel->value[VracHistoryView::VRAC_VIEW_MILLESIME] ?> - <?php echo $pluriannuel->value[VracHistoryView::VRAC_VIEW_VOLPROP]; echo ($pluriannuel->value[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] == 'raisin')? 'kg' : 'hl'; ?></option>
            <?php endforeach; ?>
            </select>
    		<ul class="nav nav-pills text-center" style="margin: 20px 0; justify-content: right; display: flex;">
                <li style="padding: 0 5px; width: 235px;">
    			    <button style="color: #fff; width:100%; padding: 5px; background-color:#ec971f; font-weight: bold;" type="submit"><span class="glyphicon glyphicon-plus-sign"></span> Créer le contrat ponctuel</button>
                </li>
    		</ul>
        </form>
    </div>
    <div id="bloc_vrac_ponctuel" class="bloc_conditionner" data-condition-value="vierge">
        <ul class="nav nav-pills text-center" style="margin: 20px 0; justify-content: right; display: flex;">
            <li style="padding: 0 5px; width: 235px;">
                <a style="padding: 5px; background-color:#ec971f; font-weight: bold;" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissement)) ?>"><span class="glyphicon glyphicon-plus-sign"></span> Créer le contrat ponctuel</a>
            </li>
        </ul>
    </div>
</div>
