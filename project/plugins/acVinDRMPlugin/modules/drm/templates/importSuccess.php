<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <h1>Déclaration Récapitulative Mensuelle <a href="" class="msg_aide" data-msg="help_popup_monespace" data-doc="/docs/notice.pdf|/docs/correspondances_mouvements.pdf" title="Message aide"></a></h1>


    <div id="flash_message">
		<div class="flash_error">
			L'import de votre DRM a échoué. Nous vous invitons à contacter votre éditeur de logiciel et de l'informer du rapport d'erreur ci-dessous.
		</div>
		<p style="padding: 5px 0;">
		En cas de difficultés temporaires avec votre fichier DRM issu de votre registre de cave, nous vous invitons à contacter votre Fédération de métiers.
		</p>
		<p style="padding: 5px 0;">
		Si le problème persiste et afin de ne pas bloquer votre déclaration, vous pouvez également saisir pour cette fois directement votre DRM sur DeclarVins pour la transférer ensuite en 1 clic sur CIEL !<?php if ($hasnewdrm): ?> <a style="color: #86005b; font-weight: bold;" href="<?php echo url_for('drm_nouvelle', DRMClient::getInstance()->createDoc($etablissement->identifiant)) ?>">Saisir ma DRM sur DeclarVins</a><?php endif; ?>
		</p>
	</div>
	
	<h1 style="margin-top: 20px;">Rapport d'erreur</h1>
	
	<div style="padding: 0 0 20px 0;">
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

