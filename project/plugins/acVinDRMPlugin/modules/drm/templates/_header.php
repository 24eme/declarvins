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
</p>
