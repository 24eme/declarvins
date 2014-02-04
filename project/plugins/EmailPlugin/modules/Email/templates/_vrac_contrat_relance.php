<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Vous avez reçu une demande de validation d'un contrat de transaction en vrac le <strong><?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?></strong>.<br />
A ce jour,  vous n'avez toujours pas <strong>validé ou refusé ce contrat</strong>.<br /><br />
Ce contrat porte sur la transaction suivante :<br />
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
<strong>Si vous souhaitez valider ou refuser ce contrat, cliquez sur le lien suivant : <a href="<?php echo url_for('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur), true); ?>">Valider ou refuser le contrat</a></strong><br /><br />
Si vous n'êtes pas inscrit sur DeclarVins.net, nous vous invitons à vous inscrire en suivant le lien : <a href="<?php echo url_for('homepage', array(), true); ?>">Inscription à DeclarVins.net</a><br />
Le contrat ne sera valable que lorsque vous aurez reçu la version pdf faisant figurer le numéro de contrat.<br /><br />
Attention si le contrat n'est pas validé dans les 10 jours à compter de sa date de saisie, il sera automatiquement supprimé et non valable.<br />
Si un des partenaires n'est pas inscrit sur DeclarVins, l'interprofession peut valider un contrat interprofessionnel en back office si elle est en possession d'une contrepartie écrite, signée. Une fois que toutes les parties ont signé, cela crée un numéro de VISA et envoie un mail avec le contrat en pdf à toutes les parties.<br /><br />
Aidez vos partenaires à s'inscrire pour profiter des services de DeclarVins et gagner du temps, notamment signer en ligne les contrats interprofessionnels : affectation d'un numéro de VISA, et envoi d'un mail avec le contrat valide en pdf à toutes les parties IMMEDIAT (dès signature par la dernière partie concernée).<br /><br />
Pour toute information, vous pouvez <a href="<?php echo url_for('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>