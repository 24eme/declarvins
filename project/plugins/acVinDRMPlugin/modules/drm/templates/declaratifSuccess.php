<?php include_component('global', 'navTop', array('active' => 'drm')); ?>
<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'declaratif', 'pourcentage' => '80')); ?>
	<?php include_partial('drm/controlMessage'); ?>
    <section id="principal">
        <div id="application_dr">
            <form id="declaratif_info" action="<?php echo url_for('drm_declaratif', $drm) ?>" method="post">
            
				
                <div id="btn_etape_dr">
                	<?php if ($sf_user->getCompte()->isTiers() && (!$sf_user->getCompte()->exist('dematerialise_ciel') || !$sf_user->getCompte()->dematerialise_ciel)): ?>
                	<a href="<?php echo url_for('drm_vrac', array('sf_subject' => $drm, 'precedent' => '1')) ?>" class="btn_prec"><span>Précédent</span></a>
                	<?php else: ?>
                    <a href="<?php echo url_for('drm_crd', $drm) ?>" class="btn_prec"><span>Précédent</span></a>
                    <?php endif; ?>
                    <button type="submit" class="btn_suiv"><span>suivant</span></button>
                </div>
                
                <?php echo $form->renderHiddenFields() ?>
                
                <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <?php if ($form->getObject()->isRectificative()): ?>
                <ul class="onglets_declaratif">
                    <li><strong>Administrateur</strong></li>
                </ul>

                <div class="contenu_onglet_declaratif ">
                    <p class="intro"><?php echo $form['raison_rectificative']->renderLabel() ?><a href="" class="msg_aide" data-msg="help_popup_declaratif_raison_rectificative" title="Message aide"></a></p>
                    <div class="ligne_form alignes">
                        <?php echo $form['raison_rectificative']->renderError() ?>
                        <?php echo $form['raison_rectificative']->render() ?>
                    </div>
                </div>
                <?php endif; ?>
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
                    <div class="ligne_form ligne_entiere ecart_check">
                        <?php echo $form['adhesion_emcs_gamma']->render() ?><?php echo $form['adhesion_emcs_gamma']->renderLabel() ?><?php echo $form['adhesion_emcs_gamma']->renderError() ?>
                    </div>
                </div>

                <ul class="onglets_declaratif">
                    <li><strong>Caution</strong><a href="" class="msg_aide" data-msg="help_popup_declaratif_caution" title="Message aide"></a></li>
                </ul>

                <div class="contenu_onglet_declaratif">
                    <p class="intro">Veuillez indiquer si vous disposez d'une caution, si oui merci de préciser l'organisme :</p>
                    <div class="ligne_form alignes" id="caution_accepte">
                        <?php echo $form['caution']->renderError() ?>
                        <?php echo $form['caution']->render() ?>
                    </div>

                    <div class="ligne_form alignes" id="numero" style="display:<?php echo ($form['caution']->getValue() === 1 || $form['numero']->hasError()) ? 'block' : 'none' ?>;">
                        <?php echo $form['numero']->renderError() ?>
                        <?php echo $form['numero']->renderLabel() ?>
                        <?php echo $form['numero']->render() ?>
                    </div>

                    <div class="ligne_form alignes" id="organisme" style="display:<?php echo ($form['caution']->getValue() === 0 || $form['organisme']->hasError()) ? 'block' : 'none' ?>;">
                        <?php echo $form['organisme']->renderError() ?>
                        <?php echo $form['organisme']->renderLabel() ?>
                        <?php echo $form['organisme']->render() ?>
                    </div>
                </div>
				
				<?php if (1==2): ?>
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
                    <p class="intro"><?php echo $form['moyen_paiement']->renderLabel() ?><p>
                    <div class="ligne_form alignes">
                        <?php echo $form['moyen_paiement']->renderError() ?>
                        <?php echo $form['moyen_paiement']->render() ?>
                    </div>
                </div>
                <?php endif; ?>
                

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
                
                <ul class="onglets_declaratif">
                    <li><strong>Observations</strong><a href="" class="msg_aide" data-msg="help_popup_drm_observations" title="Message aide"></a></li>
                </ul>
                <div class="contenu_onglet_declaratif">
                    <div class="tableau_ajouts_liquidations">
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
                    	</table>
                    	250 caractères max.
                    </div>
                </div>
				
                <div id="btn_etape_dr">
                	<?php if ($sf_user->getCompte()->isTiers() && (!$sf_user->getCompte()->exist('dematerialise_ciel') || !$sf_user->getCompte()->dematerialise_ciel)): ?>
                	<a href="<?php echo url_for('drm_vrac', array('sf_subject' => $drm, 'precedent' => '1')) ?>" class="btn_prec"><span>Précédent</span></a>
                	<?php else: ?>
                    <a href="<?php echo url_for('drm_crd', $drm) ?>" class="btn_prec"><span>Précédent</span></a>
                    <?php endif; ?>
                    <button type="submit" class="btn_suiv"><span>suivant</span></button>
                </div>

                <div class="ligne_btn">
                    <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
                </div>

            </form>
        </div>
    </section>
</section>

<script type="text/javascript">

$(document).ready( function()
	{
        $('#drm_declaratif_caution_0').click(function() { $('#organisme').css('display', 'block'); $('#numero').css('display', 'none') });
        $('#drm_declaratif_caution_1').click(function() { $('#organisme').css('display', 'none'); $('#numero').css('display', 'block') });
        $('#drm_declaratif_frequence_Mensuelle').click(function() { $('#reports').css('display', 'none'); });
        $('#drm_declaratif_frequence_Annuelle').click(function() { $('#reports').css('display', 'block'); });
        
    });
</script>