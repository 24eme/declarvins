<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Pourriez-vous nous préciser, en réponse à cet email, les numéro(s) de contrat(s) sur votre DRM du mois de <?php echo $drm->getMois().'/'.$drm->getAnnee() ?> pour les sorties vrac suivantes :
<ul>
<?php foreach ($details as $detail): ?>
<li><strong><?php echo $detail->sorties->vrac ?></strong> hl, indiqué pour le produit <strong><?php echo $detail->getFormattedLibelle(ESC_RAW); ?></strong> : Contrat interprofessionnel numéro : _____________</li>
<?php endforeach; ?>
</ul>
<br />
Pour rappel, ce numéro de VISA est composé de 11 chiffres. Vous pouvez le retrouver dans votre historique contrat interprofessionnel sur DeclarVins.<br /><br />
Merci par avance.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>