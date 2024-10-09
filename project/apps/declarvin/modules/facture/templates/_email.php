<?php
$interproNom = $facture->getConfiguration()->getNomInterproTeledeclaration();
if (!$interproNom) {
    $interproNom = sfConfig::get('app_teledeclaration_interpro');
}
if ($facture->exist('interpro') && $facture->interpro == 'INTERPRO-IVSE'):
?>
Madame, Monsieur,

Pour gagner en efficacité, votre Interprofession s'est engagée dans la dématérialisation des factures, de ce fait, merci de nous transmettre vos adresses mail de facturation, à jour.

Vous pouvez retrouver vos factures dans votre espace Déclarvins, onglet « facture ».

Une nouvelle facture est disponible.

Vous pouvez la télécharger directement en cliquant sur le lien : <?php echo ProjectConfiguration::getAppRouting()->generate('facture_pdf_auth', array('id' => $facture->_id, 'auth' => FactureClient::generateAuthKey($facture->_id)), true); ?>

Dans le cas d'une modification à apporter, n'hésitez pas nous contacter au 04 90 42 90 04 ou à contact@intervins-sudest.org.

<?php echo $interproNom ?>
<?php elseif($facture->exist('interpro') && $facture->interpro == 'INTERPRO-CIVP'): ?>
Bonjour,

Une nouvelle facture émise par le <?php echo $interproNom ?> est disponible.

Vous pouvez la télécharger directement en cliquant sur le lien : <?php echo ProjectConfiguration::getAppRouting()->generate('facture_pdf_auth', array('id' => $facture->_id, 'auth' => FactureClient::generateAuthKey($facture->_id)), true); ?>

En cas de question concernant les volumes de cette facture, contactez declarvins@provenwines.com

En cas de question sur le montant, les échéances ou sur les moyens de paiements, contactez compta@provencewines.com

Bien cordialement,

Le <?php echo $interproNom ?>
<?php else: ?>
Bonjour,

Une nouvelle facture émise par <?php echo $interproNom ?> est disponible.

Vous pouvez la télécharger directement en cliquant sur le lien : <?php echo ProjectConfiguration::getAppRouting()->generate('facture_pdf_auth', array('id' => $facture->_id, 'auth' => FactureClient::generateAuthKey($facture->_id)), true); ?>

Bien cordialement,

<?php echo $interproNom ?>
<?php endif; ?>
