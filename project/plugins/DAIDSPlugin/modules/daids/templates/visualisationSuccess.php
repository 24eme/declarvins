<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php include_partial('global/navTop', array('active' => 'daids', 'etablissement' => $etablissement)); ?>


<section id="contenu">

    <?php include_partial('daids/header', array('daids' => $daids)); ?>
    <?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
		<?php if (!$hide_rectificative && !$daids->getHistorique()->hasDAIDSInProcess() && $daids->isRectifiable()): ?>
	    <form method="get" action="<?php echo url_for('daids_rectificative', $daids) ?>">
	        <button class="btn_passer_etape rectificative" type="submit">Soumettre une DAI/DS rectificative</button>
	    </form>
		<?php endif; ?>
	<?php endif; ?>
    <!-- #principal -->
    <section id="principal">

        <?php if ($daids_suivante && $daids_suivante->isRectificative() && !$daids_suivante->isValidee()): ?>
            <div class="vigilance_list">
                <ul>
                    <li><?php echo MessagesClient::getInstance()->getMessage('msg_version_suivante') ?></li>
                </ul>
            </div>
        <?php endif; ?>
        <div id="contenu_onglet">

            <?php include_partial('daids/recap', array('daids' => $daids, 'etablissement' => $etablissement)) ?>
			<?php include_partial('daids/droits', array('daids' => $daids)) ?>
			
			
        <?php if ($daids->exist('commentaires') && $daids->commentaires): ?>
            <div style="padding: 0 0 30px 0">
                <strong>Commentaires</strong>
                <pre style="background: #fff; border: 1px #E9E9E9; padding: 8px; margin-top: 8px;"><?php echo $daids->commentaires ?></pre>
            </div>
        <?php endif; ?>  
            
            <a id="telecharger_pdf" href="<?php echo url_for('daids_pdf', $daids) ?>">Télécharger le PDF</a>
            
            <div id="btn_etape_dr">
                <?php if ($daids_suivante && $daids_suivante->hasVersion() && !$daids_suivante->isValidee()): ?>
                    <a href="<?php echo url_for('daids_init', array('sf_subject' => $daids_suivante, 'reinit_etape' => 1)) ?>" class="btn_suiv">
                        <span>Passer à la DAI/DS suivante</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo url_for('daids_mon_espace', $etablissement) ?>" class="btn_suiv">
                        <span>Retour à mon espace</span>
                    </a>
                <?php endif; ?>
            </div>

        </div>    
    </section>
	    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$daids->getHistorique()->hasDAIDSInProcess() && $daids->isModifiable()): ?>
	    <form method="get" action="<?php echo url_for('daids_modificative', $daids) ?>">
	        <button style="float:left;" class="btn_passer_etape modificative" type="submit">Faire une DAI/DS modificative</button>
	    </form>
	    <?php endif; ?>
</section>
