<?php
$interproNom = $facture->getConfiguration()->getNomInterproTeledeclaration();
if (!$interproNom) {
    $interproNom = sfConfig::get('app_teledeclaration_interpro');
}
if ($facture->exist('interpro') && $facture->interpro == 'INTERPRO-IVSE'):
?>
Madame, Monsieur,

Pour gagner en efficacité, votre Interprofession s'est engagée dans la dématérialisation des factures.

Dans un premier temps, vous recevrez vos factures par courrier et par mail afin de vous habituer à ce nouveau format et mettre à jour votre adresse mail, si nécessaire.

Vous pouvez retrouver vos factures dans votre espace Déclarvins, onglet « facture ».

Une nouvelle facture est disponible.

Vous pouvez la télécharger directement en cliquant sur le lien : <?php echo ProjectConfiguration::getAppRouting()->generate('facture_pdf_auth', array('id' => $facture->_id, 'auth' => FactureClient::generateAuthKey($facture->_id)), true); ?>


Dans le cas d'une modification à apporter, n'hésitez pas nous contacter au 04 90 42 90 04 ou à contact@intervins-sudest.org.

<?php echo $interproNom ?>
<?php else: ?>
Bonjour,

Une nouvelle facture émise par <?php echo $interproNom ?> est disponible.

Vous pouvez la télécharger directement en cliquant sur le lien : <?php echo ProjectConfiguration::getAppRouting()->generate('facture_pdf_auth', array('id' => $facture->_id, 'auth' => FactureClient::generateAuthKey($facture->_id)), true); ?>


Bien cordialement,

<?php echo $interproNom ?>
<?php endif; ?>
