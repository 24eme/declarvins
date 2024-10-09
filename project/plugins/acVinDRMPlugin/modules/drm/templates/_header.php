<?php use_helper('Date') ?>

<h1>Déclaration Récapitulative Mensuelle</h1>
<p id="date_drm">
	DRM de <?php echo format_date($drm->getAnnee().'-'.$drm->getMois().'-01', 'MMMM yyyy', 'fr_FR') ?>
	<?php if($drm->isRectificative()): ?>
		- <strong style="color: #ff0000; text-transform: uppercase;">
			Rectificative n° <?php echo sprintf('%02d', $drm->getRectificative()) ?>
		</strong>
        <?php endif; ?>
    <?php if($drm->isModificative()): ?>
		- <strong style="color: #ff0000; text-transform: uppercase;">
			Modificative n° <?php echo sprintf('%02d', $drm->getModificative()) ?>
		</strong>
    <?php endif; ?>
    <small>(saisie <?php echo $drm->getModeDeSaisieLibelle(); ?>)</small>
    <?php if ($drm->isModeDeSaisie(DRMClient::MODE_DE_SAISIE_DTI_PLUS) && $drm->getDtiPlusCSV() &&  $drm->getDtiPlusCSV()->getFileContent() != false): ?>
        <a href="<?php echo url_for('drm_dtiplusfile_download', $drm) ?>" style="font-style: normal;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16" style="vertical-align: middle; margin-top: -5px;">
            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
        </svg>
        Télécharger le fichier importé
        </a>
    <?php endif; ?>
</p>
