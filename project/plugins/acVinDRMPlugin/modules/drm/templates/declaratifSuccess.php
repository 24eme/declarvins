<?php include_component('global', 'navTop', array('active' => 'drm')); ?>
<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'declaratif', 'pourcentage' => '80')); ?>
	<?php include_partial('drm/controlMessage'); ?>
    <section id="principal">
        <div id="application_dr">
            <form id="declaratif_info" action="<?php echo url_for('drm_declaratif', $drm) ?>" method="post">


                <div id="btn_etape_dr">
                    <button type="submit" name="prev" value="1" class="btn_prec"><span>Précédent</span></button>
                    <button type="submit" class="btn_suiv"><span>suivant</span></button>
                </div>

                <?php echo $form->renderHiddenFields() ?>

                <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <?php if ($form->getObject()->isValidee()): ?>
                <ul class="onglets_declaratif">
                    <li><strong>Date signature</strong><a href="" class="msg_aide" data-msg="help_popup_declaratif_date_signee" title="Message aide"></a></li>
                </ul>

                <div class="contenu_onglet_declaratif ">
                    <p class="intro"><?php echo $form['date_signee']->renderLabel() ?><a href="" class="msg_aide" data-msg="help_popup_declaratif_date_signee" title="Message aide"></a></p>
                    <div class="ligne_form alignes">
                        <?php echo $form['date_signee']->renderError() ?>
                        <?php echo $form['date_signee']->render() ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <ul class="onglets_declaratif">
                    <li><strong>Défaut d'apurement</strong><a href="" class="msg_aide" data-msg="help_popup_declaratif_defaut_apurement" title="Message aide"></a></li>
                </ul>

                <div class="contenu_onglet_declaratif ">
                    <p class="intro">Veuillez séléctionner un défaut d'apurement :</p>
                    <div class="ligne_form alignes bloc_condition" data-condition-cible="#bloc_rna">

                        <?php echo $form['apurement']->renderError() ?>
                        <?php echo $form['apurement']->render() ?>

                    </div>
                </div>

                <ul class="onglets_declaratif">
                    <li><strong>Mouvements au cours du mois</strong><a href="" class="msg_aide" data-msg="help_popup_declaratif_mouvement" title="Message aide"></a></li>
                </ul>

                <div class="contenu_onglet_declaratif">
                	<div id="bloc_rna" class="bloc_conditionner" data-condition-value="1">
	                    <p class="intro">Relevé des documents d'accompagnement non apurés (RNA)</p>

	                    <div class="tableau_ajouts_liquidations">
							<table id="rna" class="tableau_recap" style="width: auto;">
								<thead>
	                    			<tr>
	                    				<th style="width: 225px; text-align:center;">Numéro DAA/DAC/DAE</th>
	                    				<th style="width: 260px;">Numéro d'accises du destinataire</th>
	                    				<th style="width: 260px;">Date d'expédition</th>
	                    			</tr>
	                    		</thead>
	                    		<tbody>
	                    			<?php foreach ($form['rna'] as $key => $subform): ?>
	                        			<?php echo include_partial('form_rna_item', array('form' => $subform)); ?>
	                    			<?php endforeach; ?>
	                    		</tbody>
	                    	</table>
	                    	<div style="text-align: right; padding-right: 45px;">
	                    		<a class="btn_ajouter_ligne_template" data-container="#rna tbody" data-template="#template_form_detail_rna_item" href="#">Ajouter un RNA</a>
	                    	</div>
	                    	<script id="template_form_detail_rna_item" class="template_form" type="text/x-jquery-tmpl">
                        <?php echo include_partial('form_rna_item', array('form' => $form->getFormTemplateRna())); ?>
                        </script>
	                    </div>
					</div>
                    <p class="intro">Références des documents d'accompagnement emis durant le mois précédent</p>

                    <div class="tableau_ajouts_liquidations">
						<table class="tableau_recap" style="width: auto;">
							<thead>
                    			<tr>
                    				<th style="width: 220px;"></th>
                    				<th style="width: 175px;">Début de période</th>
                    				<th style="width: 175px;">Fin de période</th>
                    				<th style="width: 175px;">Nb de document</th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    			<tr>
                    				<td>N° d'empreintes utilisées</td>
                    				<td><?php echo $form['empreinte_debut']->renderError() ?><?php echo $form['empreinte_debut']->render() ?></td>
                    				<td><?php echo $form['empreinte_fin']->renderError() ?><?php echo $form['empreinte_fin']->render() ?></td>
                    				<td><?php echo $form['empreinte_nb']->renderError() ?><?php echo $form['empreinte_nb']->render() ?></td>
                    			</tr>
                    			<tr>
                    				<td>N° DAA/DCA</td>
                    				<td><?php echo $form['daa_debut']->renderError() ?><?php echo $form['daa_debut']->render() ?></td>
                    				<td><?php echo $form['daa_fin']->renderError() ?><?php echo $form['daa_fin']->render() ?></td>
                    				<td><?php echo $form['daa_nb']->renderError() ?><?php echo $form['daa_nb']->render() ?></td>
                    			</tr>
                    			<tr>
                    				<td>N° DSA/DSAC</td>
                    				<td><?php echo $form['dsa_debut']->renderError() ?><?php echo $form['dsa_debut']->render() ?></td>
                    				<td><?php echo $form['dsa_fin']->renderError() ?><?php echo $form['dsa_fin']->render() ?></td>
                    				<td><?php echo $form['dsa_nb']->renderError() ?><?php echo $form['dsa_nb']->render() ?></td>
                    			</tr>
                    		</tbody>
                    	</table>
                    </div>
                </div>

				<?php if ($sf_user->getCompte()->isTiers() && !$etablissement->isTransmissionCiel() && !$drm->isNegoce()): ?>
                <ul class="onglets_declaratif">
                    <li><strong>Paiement des droits de circulation</strong><a href="" class="msg_aide" data-msg="help_popup_declaratif_paiement" title="Message aide"></a></li>
                </ul>
                <div class="contenu_onglet_declaratif">
                    <?php if (!$form->hasWidgetFrequence()): ?>
                            <div class="ligne_form alignes">
                                Vous payez par échéance <strong><?php echo strtolower($drm->declaratif->paiement->douane->frequence) ?></strong>
                                - <a href="<?php echo url_for('drm_declaratif_frequence_form', $drm) ?>" class="btn_popup" data-popup="#popup_ajout_frequence" data-popup-config="configForm">Modifier l'échéance de paiement</a>
                            </div>
                    <?php else: ?>
                        <p class="intro"><?php echo $form['frequence']->renderLabel() ?></p>
                        <div class="ligne_form alignes">
                            <?php echo $form['frequence']->renderError() ?>
                            <?php echo $form['frequence']->render() ?>
                        </div>
                    <?php endif; ?>
                    <br />
                    <?php if (isset($form['reports'])): ?>
                    <div id="reports" style="display: <?php if((isset($form['frequence']) && $form['frequence']->getValue() == 'Annuelle')): ?>block<?php else: ?>none<?php endif; ?>;">
                    	<p class="intro">Veuillez saisir le cumul de vos droits de circulation depuis le début de campagne par code taxe :<p>
                    		<?php  foreach ($form['reports'] as $formReport): ?>
                    		<div class="ligne_form alignes">
                        		<?php echo $formReport->renderError() ?>
                        		<?php echo $formReport->renderLabel() ?>
                        		<?php echo $formReport->render() ?>
                        	</div>
                    		<?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (($sf_user->getCompte()->isTiers()) || $drm->isNegoce()): ?>
                <ul class="onglets_declaratif">
                    <li><strong>Observations</strong><a href="" class="msg_aide" data-msg="help_popup_drm_observations" title="Message aide"></a></li>
                </ul>
                <div class="contenu_onglet_declaratif">
                    <div class="tableau_ajouts_liquidations">
                        <p>
                            <strong>/!\ Merci de préciser le volume de vin correspondant à l'observation en detaillant explicitement le mouvement du produit /!\</strong>
                        </p>
                        <p style="text-align: right; padding-right: 20px;">
                            250 caractères max.
                        </p>
                		<table class="tableau_recap">
                		<?php $i=0; foreach ($form['observationsProduits'] as $formObservations): ?>
                			<tr<?php if($i%2): ?> class="alt"<?php endif; ?>>
                				<td style="width: 332px;"><?php echo $formObservations['observations']->renderLabel() ?></td>
                				<td>
                        			<?php echo $formObservations['observations']->renderError() ?>
                        			<?php echo $formObservations['observations']->render(array("maxlength" => "250", "style" => "width: 95%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                        		</td>
                    		</tr>
                        <?php $i++; endforeach; ?>
                        <?php foreach ($form['observationsCrds'] as $formObservationsCrd): ?>
                          <tr<?php if($i%2): ?> class="alt"<?php endif; ?>>
                            <td style="width: 332px;"><?php echo $formObservationsCrd['observations']->renderLabel() ?></td>
                            <td>
                                  <?php echo $formObservationsCrd['observations']->renderError() ?>
                                  <?php echo $formObservationsCrd['observations']->render(array("maxlength" => "250", "style" => "width: 95%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                                </td>
                            </tr>
                          <?php $i++; endforeach; ?>
                    	</table>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (($sf_user->getCompte()->isTiers() && $etablissement->isTransmissionCiel()) || $drm->isNegoce()): ?>
                <ul class="onglets_declaratif">
                    <li><strong>Statistiques européennes</strong><a href="" class="msg_aide" data-msg="help_popup_drm_stats_euro" title="Message aide"></a></li>
                </ul>
                <div class="contenu_onglet_declaratif">
                	<div class="tableau_ajouts_liquidations">
						<table class="tableau_recap">
							<thead>
                    			<tr>
                    				<th style=" width: auto;"></th>
                    				<th>Volume</th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    			<tr>
                    				<td><?php echo $form['statistiques_jus']->renderLabel() ?></td>
                    				<td><?php echo $form['statistiques_jus']->render() ?>&nbsp;<span class="unite">hl</span><br /><?php echo $form['statistiques_jus']->renderError() ?></td>
                    			</tr>
                    			<tr>
                    				<td><?php echo $form['statistiques_mcr']->renderLabel() ?></td>
                    				<td><?php echo $form['statistiques_mcr']->render() ?>&nbsp;<span class="unite">hl</span><br /><?php echo $form['statistiques_mcr']->renderError() ?></td>
                    			</tr>
                    			<tr>
                    				<td><?php echo $form['statistiques_vinaigre']->renderLabel() ?></td>
                    				<td><?php echo $form['statistiques_vinaigre']->render() ?>&nbsp;<span class="unite">hl</span><br /><?php echo $form['statistiques_vinaigre']->renderError() ?></td>
                    			</tr>
                    		</tbody>
                    	</table>
                    </div>
                </div>
                <?php endif; ?>

                <div id="btn_etape_dr">
                	<button type="submit" name="prev" value="1" class="btn_prec"><span>Précédent</span></button>
                    <button type="submit" class="btn_suiv"><span>suivant</span></button>
                </div>

				<?php if($drm->isRectificative() && $drm->exist('ciel') && $drm->ciel->transfere && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
				<?php else: ?>
                <div class="ligne_btn">
                    <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
                </div>
				<?php endif; ?>
            </form>
        </div>
    </section>
</section>

<script type="text/javascript">

$(document).ready( function()
	{
        $('#drm_declaratif_frequence_Mensuelle').click(function() { $('#reports').css('display', 'none'); });
        $('#drm_declaratif_frequence_Annuelle').click(function() { $('#reports').css('display', 'block'); });

    });
</script>
