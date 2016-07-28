<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">
    
    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_monespace" data-doc="<?php echo url_for('drm_notice') ?>" title="Message aide"></a></h1>
    

    <div id="flash_message">
		<div class="flash_error">
			L'import de votre DRM a échoué. Nous vous invitons à contacter votre éditeur de logiciel et de l'informer du rapport d'erreur ci-dessous.
		</div>
	</div>
	
	<h1>Rapport d'erreur</h1>
	
	<div style="padding: 20px;">
	<?php foreach ($logs as $log): ?>
		<?php echo implode(';', $log->getRawValue()); ?><br />
	<?php endforeach; ?>
	</div>
	
	<div id="btn_etape_dr">
		<a class="btn_prec" href="<?php echo url_for('drm_mon_espace', $etablissement) ?>">
		<span>Retour à mon espace</span>
		</a>
	</div>
    
</section>

