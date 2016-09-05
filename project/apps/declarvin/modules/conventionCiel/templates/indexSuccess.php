<?php use_helper("Date"); ?>
<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>

<section id="contenu"  class="vracs">
    <div id="creation_compte">
        <h1>Convention d'adhésion à l'échange de données CIEL-Declarvins.net</h1>
        <?php if ($convention && $convention->valide): ?>
        <p>Vous avez saisi une convention d'ahésion CIEL le <?php echo format_date($convention->date_saisie,'dd MMM yyyy'); ?>.</p>
        <p>Celle ci est en cours de traitement par votre service des douanes.</p>
        <br />
		<p>Nos équipes se tiennent à votre disposition pour toute question.</p>
        <?php else: ?>
        <p>Afin de pouvoir activer la dématérialisation de la DRM entre DeclarVins.net et CIEL, vous devez remplir la convention en suivant les prochaines étapes.<br />Une fois votre inscription terminée, vous recevrez par mail la &laquo;convention&raquo; résumant les informations que vous avez remplies.</p>
		<p>Vous devez la signer et la renvoyer à votre service des douanes pour valider définitivement votre accès aux services.</p>
		<p>Une fois votre convention validée et l'échange de données activé, vous pourrez déclarer vos DRM d'un seul geste déclaratif. Votre DRM saisie sur DeclarVins.net sera transmise informatiquement sur l'interface CIEL des douanes sur laquelle vous devrez enregistrer définitivement votre e.DRM et finaliser votre paiement.</p>
		<br />
		<p>Nos équipes se tiennent à votre disposition pour toute question.</p>
		
		<div class="ligne_form_btn">
			<a href="<?php echo url_for('convention_nouveau', $etablissement) ?>" class="valider_etape"><span>J'adhère au service CIEL</span></a>
			<a class="annuler_saisie" href="<?php echo url_for('drm_mon_espace', $etablissement)?>"><span>Plus tard</span></a>
		</div>
		<?php endif; ?>
    </div>
</section>