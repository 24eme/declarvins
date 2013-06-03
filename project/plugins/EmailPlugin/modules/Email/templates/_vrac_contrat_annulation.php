<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Le contrat de transaction en vrac saisi le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> n'a pas été validé par toutes les parties dans le délai prévu.<br />
<strong>Ce contrat a donc été supprimé et est considéré comme non valable.</strong><br /><br />
Pour mémoire, le contrat portait sur la transaction suivante :<br />
Date de saisie : <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?><br />
Produit : <?php echo $vrac->getLibelleProduit() ?> <br />
Millésime : <?php echo $vrac->millesime ?><br />
Type : <?php echo $vrac->type ?><br />
Quantité : <?php echo $vrac->volume_propose ?>hl<br />
Prix : <?php echo $vrac->prix_unitaire ?>€/hl<br />
<?php if ($vrac->acheteur_identifiant): ?>
Acheteur : <?php echo ($vrac->acheteur->raison_sociale)? $vrac->acheteur->raison_sociale : $vrac->acheteur->nom; ?><br />
<?php endif; ?>
<?php if ($vrac->mandataire_identifiant): ?>
Courtier : <?php echo ($vrac->mandataire->raison_sociale)? $vrac->mandataire->raison_sociale : $vrac->mandataire->nom; ?><br />
<?php endif; ?>
<?php if ($vrac->vendeur_identifiant): ?>
Vendeur : <?php echo ($vrac->vendeur->raison_sociale)? $vrac->vendeur->raison_sociale : $vrac->vendeur->nom; ?><br />
<?php endif; ?>
Commentaire : <?php echo $vrac->commentaires ?><br /><br />
Nous vous invitons à vous rapprocher de vos partenaires afin de régler ce contretemps.<br /><br />
Pour toute information, vous pouvez contacter votre interprofession.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>