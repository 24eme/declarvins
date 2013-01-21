<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Vous avez saisi un <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', array('sf_subject' => $vrac, 'etablissement' => $etablissement), true); ?>">contrat interprofessionnel vrac</a> le  <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> à <?php echo strftime('%R', strtotime($vrac->valide->date_saisie)) ?>.<br />
Ce contrat porte sur la transaction suivante :<br /><br />
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
Votre contrat a bien été enregistré. Il va être envoyé aux autres parties concernées pour validation.<br /> 
Vous recevrez une version du contrat en .pdf avec le numéro de contrat lorsque toutes les parties auront validé le contrat.<br />
Le contrat ne pourra être considéré comme valable que lorsque vous aurez reçu cette version pdf faisant figurer le numéro de contrat.<br /><br />
Attention si le contrat n'est pas validé d'ici 10 jours par vos partenaires, il sera automatiquement supprimé et non valable.<br /><br />
Pour toute information, vous pouvez contacter votre interprofession.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>