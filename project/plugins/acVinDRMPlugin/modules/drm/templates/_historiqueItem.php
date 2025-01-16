<tr class="<?php if ($alt): ?>alt<?php endif; ?>">
    <td style="padding: 5px;">
        <?php if ($drm->isMaster()): ?><strong><?php endif; ?>
            <!--
    if($drm->getRectificative() > 0):
    echo sprintf('%s R%02d', $drm->periode, $drm->getRectificative())
    else:
    echo sprintf('%s', $drm->periode)
    endif;
            -->
            <?php echo sprintf('%s %s', $drm->periode, $drm->version); ?>

            <?php if ($drm->isMaster()): ?></strong><?php endif; ?>
        <!--
if($drm->getModificative() > 0):
echo sprintf('(M%02d)', $drm->getModificative())
endif;
        -->
    </td>
    <td style="padding: 5px;">
    <?php echo $drm->getLibelleBilan(); ?>
    </td>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    <td style="padding: 5px;">
    <?php echo $drm->commentaires; ?>
    <?php if ($drm->ciel->diff): ?>Il existe une différence entre la DRM Declarvins et sa version <a href="<?php echo url_for('drm_xml', $drm) ?>">XML Ciel</a>.<?php endif; ?>
    </td>
    <?php endif; ?>
        <td style="padding: 5px;">
            <?php echo $drm->getModeDeSaisieLibelle() ?>
        </td>
    <td style="padding: 5px;">
        <?php if ($drm->isNew()): ?>
            <?php if (1 == 2 && $drm->isDebutCampagne()): ?>
                Vous devez saisir votre <strong>DRM <?php echo $drm->getCampagnePrecedente() ?></strong>
            <?php else: ?>
                <a data-popup="#popup_selection_dti" class="btn_popup" href="">Démarrer la DRM</a>
		        <div id="popup_selection_dti" class="popup_contenu popup_form" style="display:none;">
		        	<div id="principal">
		        		<div id="recap_drm" style="float: none; width: auto;">
		        		<table>
							<thead>
								<tr>
									<th>Saisie en ligne sur DeclarVins (DTI)</th>
									<th>Import depuis votre logiciel (DTI+)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding: 5px 0;">
										<div class="btn ligne_form_btn" style="text-align: center; background: none;">
											<input type="text" style="visibility:hidden;">
											<a href="<?php echo url_for('drm_nouvelle', $drm) ?>" class="btn_valider" style="margin-left: 0px;"><span>Saisir la DRM</span></a>
										</div>
									</td>
									<td style="padding: 5px 0;">
										<form action="<?php echo url_for('drm_import', $drm) ?>" method="post" enctype="multipart/form-data">
										    <?php echo $formImport->renderHiddenFields(); ?>
										    <?php echo $formImport->renderGlobalErrors(); ?>

										    <?php echo $formImport['file']->render(array('id' => 'uploadFileInput', 'required' => 'required')) ?>
										    <?php echo $formImport['file']->renderError() ?>

											<div id="submit-drm-edi" class="btn ligne_form_btn" style="text-align: center;">
												<button class="btn_valider" type="submit">Importer la DRM</button>
											</div>
											<div id="loading-drm-edi" class="btn ligne_form_btn" style="text-align: center; display:none;">
												<img src="/images/pictos/pi_loader.gif" alt="chargement..." /> L'import peut prendre du temps
											</div>
										</form>
									</td>
								</tr>
							</tbody>
						</table>
						</div>
					</div>
					<script type="text/javascript">
						$("#submit-drm-edi button").click(function() {
							if( document.getElementById("uploadFileInput").files.length != 0 ){
								$("#submit-drm-edi").css("display","none");
								$("#loading-drm-edi").css("display","block");
							}
						});
					</script>
				</div>
            <?php endif; ?>
        <?php elseif ($drm->isValidee()): ?>
            <a href="<?php echo url_for('drm_visualisation', $drm) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
        <?php else: ?>
            <?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_init', $drm); ?>">Accéder à la déclaration en cours</a><br />
            <?php endif; ?>
        <?php endif; ?>
    </td>
    <?php if (!$drm->isNew() && !$drm->isValidee()): ?>
		<?php if($drm->isRectificative() && $drm->exist('ciel') && $drm->ciel->transfere && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
		<?php else: ?>
        <td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
            <a href="<?php echo url_for('drm_delete_one', $drm); ?>" class="btn_reinitialiser" onclick="return confirm('Etes vous surs de vouloir supprimer cette DRM ?')"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
        </td>
        <?php endif; ?>
    <?php endif; ?>
</tr>
