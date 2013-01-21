<?php echo include_partial('Email/headerMail') ?>

Entreprise : <?php echo ($vrac->{$vrac->vous_etes}->raison_sociale)? $vrac->{$vrac->vous_etes}->raison_sociale : $vrac->{$vrac->vous_etes}->nom; ?><br /><br />
Madame, Monsieur,<br /><br />
L'entreprise <?php echo ($vrac->{$vrac->vous_etes}->raison_sociale)? $vrac->{$vrac->vous_etes}->raison_sociale : $vrac->{$vrac->vous_etes}->nom; ?> a saisi un contrat de transaction <?php echo strtolower($vrac->type) ?> vous concernant.<br /><br />
Le contrat saisi porte sur la transaction suivante :<br />
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
Ce contrat ne pourra être considéré comme valable que lorsque toutes les parties concernées l'auront validé.<br />
Vous recevrez alors une version du contrat en .pdf avec le numéro de contrat.<br /><br />
Attention si le contrat n'est pas validé dans les 10 jours à compter de sa date de saisie, il sera automatiquement supprimé et non valable.<br /><br />
<strong>Si vous souhaitez valider ce contrat, cliquez sur le lien suivant : </strong><a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur), true); ?>">Valider le contrat</a>.<br /><br />
Pour toute information, vous pouvez contacter votre interprofession.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>