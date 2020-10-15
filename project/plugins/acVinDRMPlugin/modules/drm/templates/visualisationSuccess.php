<?php use_helper('Float'); ?>
<?php use_helper('Date'); ?>
<?php use_helper('Version'); ?>
<?php use_helper('Link'); ?>
<?php include_component('global', 'navTop', array('active' => 'drm')); ?>


<section id="contenu">
    <?php if ($sf_user->hasFlash('erreur_drm')): ?>
        <div id="flash_message">
            <div class="flash_error"><?php echo $sf_user->getFlash('erreur_drm') ?></div>
        </div>
    <?php endif; ?>
    <?php if($masterVersion->_id != $drm->_id): ?>
    <div id="flash_message">
        <div class="flash_warning">La version de DRM que vous visualisez n'est pas la version la plus récente. <a href="<?php echo url_for('drm_visualisation', array('sf_subject' => $masterVersion)) ?>">Cliquez ici</a> pour visualiser la dernière version.</div>
        </div>
<?php endif; ?>
    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
        <?php if (!$hide_rectificative && !$drm->getHistorique()->hasDRMInProcess() && $drm->isRectifiable()): ?>
            <form method="get" action="<?php echo url_for('drm_rectificative', $drm) ?>">
                <button class="btn_passer_etape rectificative" type="submit">Soumettre une DRM rectificative</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
    <!-- #principal -->
    <section id="principal">

    	<?php $drmCiel = $drm->getOrAdd('ciel');  ?>

        <?php if ($drm->isValidee()): ?>

            <?php if(!$drm->isRectificative() && $drmCiel->isTransfere() && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <p style="text-align: right; margin-bottom: 10px;"><a href="<?php echo url_for('drm_reouvrir', $drm) ?>" style="background-color: #FF9F00; padding: 6px; color: #fff;">Ré-ouvir la DRM</a></p>
            <?php endif; ?>
            <div style="background: none repeat scroll 0 0 #ECFEEA;border: 1px solid #359B02;color: #1E5204;font-weight: bold;margin: 0 0 10px 0;padding: 5px 10px;">
                <ul>
                    <li>
                    <?php if ($drm->isTeledeclare()): ?>
                    Votre DRM a bien été validée et transmise à votre interprofession.<br />
                    <?php if(!$drm->isRectificative() && $drmCiel->isTransfere()): ?>
                    Votre DRM a été transmise correctement au service CIEL, le <?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?> à <?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?> sous le numéro <?php echo $drmCiel->identifiant_declaration ?>.<br />
                    Vous devez terminer votre déclaration en la vérifiant et la validant ("déposer la DRM") sur le site prodouanes via le lien suivant : <a href="https://pro.douane.gouv.fr/">pro.douane.gouv.fr</a><br />
					en vous connectant et en allant sur l'interface CIEL (menu de gauche).
                    <?php elseif ($drm->isRectificative() && $drmCiel->isTransfere()): ?>
                    Votre DRM a bien été corrigée afin de correspondre à celle transmise au service CIEL, le <?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?> à <?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?> sous le numéro <?php echo $drmCiel->identifiant_declaration ?>.
                    <?php elseif (!$etablissement->isTransmissionCiel() && !$drm->isNegoce()): ?>
					Vous devez par contre imprimer le PDF et le signer puis l'envoyer à votre service des douanes habituel.
                    <?php endif; ?>
                    <?php else: ?>
                    Votre DRM a bien été saisie et validée.<br />
                    <?php if(!$drm->isRectificative() && $drmCiel->isTransfere()): ?>
                    Votre DRM a été transmise correctement au service CIEL, le <?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?> à <?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?> sous le numéro <?php echo $drmCiel->identifiant_declaration ?>.<br />
                    Vous devez terminer votre déclaration en la vérifiant et la validant ("déposer la DRM") sur le site prodouanes via le lien suivant : <a href="https://pro.douane.gouv.fr/">pro.douane.gouv.fr</a><br />
					en vous connectant et en allant sur l'interface CIEL (menu de gauche).
                    <?php elseif ($drm->isRectificative() && $drmCiel->isTransfere()): ?>
                    Votre DRM a bien été corrigée afin de correspondre à celle transmise au service CIEL, le <?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?> à <?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?> sous le numéro <?php echo $drmCiel->identifiant_declaration ?>.
                    <?php else: ?>
					Vous devez par contre imprimer le PDF et le signer puis l'envoyer à votre service des douanes habituel.
                    <?php endif; ?>
                    <?php endif; ?>
                    </li>
                </ul>
            </div>

            <?php if ($drm->isNegoce()): ?>
            <div style="background: none repeat scroll 0 0 #d9e0ed; border: 1px solid #182188; color: #182188; font-weight: bold;margin: 0 0 10px 0;padding: 5px 10px;">
                <ul>
                    <li>
                    	<img src="/images/visuels/prodouane.png" /><br />
                    	Vous devez à présent télécharger votre DRM au format XML pour la déposer ensuite et la valider sur CIEL à partir de votre compte ProDouane via le lien suivant : <a href="https://pro.douane.gouv.fr/">pro.douane.gouv.fr</a><br />
            			<a id="telecharger_xml" style="margin-left:0;float: right; position: inherit; font-weight: normal;" target="_blank" href="<?php echo link_to_edi('testDRMEdi', array('id_drm' => $drm->_id, 'format' => 'xml')); ?>">Télécharger le XML</a><br />
            			&nbsp;
            		</li>
            	</ul>
            </div>
			<?php endif; ?>


            <?php if($drmCiel->isTransfere() && !$drmCiel->isValide() && ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) || $sf_user->isUsurpationMode())): ?>
            <p>Aucun retour de la part de proDou@ne n'a été effectué : <a href="<?php echo url_for('drm_retour_refresh', $drm); ?>"  class="pull-right btn btn-xs btn-default" >Ré-interroger</a></p>
            <?php elseif($drmCiel->isTransfere() && $drmCiel->isValide()): ?>
            <p>DRM conforme proDou@ne</p>
            <?php endif; ?>
            <?php if($drmCiel->isTransfere() && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <p style="text-align: right; margin-bottom: 10px;"><a href="<?php echo url_for('drm_retransfer_ciel', $drm) ?>" style="background-color: #9e9e9e; padding: 6px; color: #fff;">Re-transmettre la DRM</a></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($drm_generate_version || $drm_next_version): ?>
            <div class="vigilance_list">
                <ul>
                	<?php if ($drm_generate_version > 0): ?>
                	<li><?php echo $drm_generate_version ?> DRM<?php if($drm_generate_version > 1): ?>s<?php endif; ?> modificative<?php if($drm_generate_version > 1): ?>s<?php endif; ?> générée<?php if($drm_generate_version > 1): ?>s<?php endif; ?> automatiquement</li>
                	<?php endif; ?>
                	<?php if ($drm_next_version && $drm_next_version->isModificative() && !$drm_next_version->isValidee()): ?>
                    <li>Vous devez modifier la DRM <?php echo $drm_next_version->periode ?></li>
                    <?php endif; ?>
                    <?php if ($drm_next_version && $drm_next_version->isRectificative() && !$drm_next_version->isValidee()): ?>
                    <li>Vous devez rectifier la DRM <?php echo $drm_next_version->periode ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div id="contenu_onglet">
            <?php if (($drm->declaration->hasMouvement() && !$drm->declaration->hasStockEpuise()) || $drm->hasMouvementsCrd()): ?>
                <?php include_partial('drm/recap', array('drm' => $drm)) ?>
                <?php include_partial('drm/droits', array('drm' => $drm, 'circulation' => $droits_circulation)) ?>
            <?php else: ?>
                <?php include_partial('drm/pasDeMouvement', array('drm' => $drm)) ?>
            <?php endif; ?>


            <?php if ($drm->isIncomplete()): ?>
                <div class="vigilance_list">
                    <ul>
                        <li style="padding-bottom: 5px;">DRM incomplète :</li>
                        <?php if ($drm->manquants->igp): ?>
                            <li style="font-weight: normal;">Produit(s) IGP manquant(s)</li>
                        <?php endif; ?>
                        <?php if ($drm->manquants->contrats): ?>
                            <li style="font-weight: normal;">Contrat(s) manquant(s)</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
			<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $drm->hasVersion()): ?>
            <?php include_partial('drm/mouvements', array('interpro' => $interpro, 'configurationProduits' => $configurationProduits, 'mouvements' => $mouvements, 'etablissement' => $etablissement, 'hamza_style' => false, 'no_link' => null)) ?>
            <?php endif; ?>

        <?php if ($drm->exist('observations') && $drm->observations): ?>
            <div style="padding: 0 0 30px 0" class="tableau_ajouts_liquidations">
                <h2>
					<strong>Observations</strong>
				</h2>
                <table class="tableau_recap">
                	<?php $i=0; foreach ($drm->getDetails() as $detail): if (!$detail->observations) {continue;} ?>
                			<tr<?php if($i%2): ?> class="alt"<?php endif; ?>>
                				<td style="width: 332px;"><?php echo $detail->getLibelle() ?></td>
                				<td style="text-align: left;">
                        			<pre><?php echo $detail->observations ?></pre>
                        		</td>
                    		</tr>
                    	<?php $i++; endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($drm->exist('commentaires') && $drm->commentaires && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <div style="padding: 0 0 30px 0">
                <strong>Commentaires BO</strong>
                <pre style="background: #fff; border: 1px #E9E9E9; padding: 8px; margin-top: 8px;"><?php echo $drm->commentaires ?></pre>
            </div>
        <?php endif; ?>
            <?php if ($etablissement->isTransmissionCiel() && $drmCiel->isTransfere() && !$drmCiel->isValide() && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <?php else: ?>
            <a id="telecharger_pdf" href="<?php echo url_for('drm_pdf', $drm) ?>">Télécharger le PDF</a>
            <?php endif; ?>
            <?php if ($drm->isNegoce() || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <a id="telecharger_xml" target="_blank" href="<?php echo link_to_edi('testDRMEdi', array('id_drm' => $drm->_id, 'format' => 'xml')); ?>">Télécharger le XML</a>
			<?php endif; ?>
            <div id="btn_etape_dr">
                <?php if ($drm_next_version && $drm_next_version->hasVersion() && !$drm_next_version->isValidee()): ?>
                    <a href="<?php echo url_for('drm_init', array('sf_subject' => $drm_next_version, 'reinit_etape' => 1)) ?>" class="btn_suiv">
                        <span>Passer à la DRM suivante</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>" class="btn_suiv">
                        <span>Retour à mon espace</span>
                    </a>
                <?php endif; ?>
            </div>

        </div>

    </section>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$drm->getHistorique()->hasDRMInProcess() && $drm->isModifiable()): ?>
        <form method="get" action="<?php echo url_for('drm_modificative', $drm) ?>">
            <button style="float:left;" class="btn_passer_etape modificative" type="submit">Faire une DRM Modificative</button>
        	 <?php if (!$drmCiel->isTransfere() && !$historique->hasDRMInProcess() && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <a id="telecharger_pdf" onclick="return confirm('Vous allez dévalider cette DRM, êtes vous sûr ?')" style="font-weight: bold; float: right; margin-left: 0; position: relative; padding: 3px; background: #9e9e9e;" href="<?php echo url_for('drm_devalide', $drm); ?>">[X] DEVALIDER</a>
			<?php endif; ?>
        </form>
    <?php endif; ?>
</section>
