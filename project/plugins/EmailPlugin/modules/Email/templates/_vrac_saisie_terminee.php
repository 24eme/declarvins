<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Vous avez saisi un contrat interprofessionnel le  <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> à <?php echo strftime('%R', strtotime($vrac->valide->date_saisie)) ?>.<br />
Ce contrat porte sur la transaction suivante :<br /><br />
Date de saisie : <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?><br />
Produit : <?php echo $vrac->getLibelleProduit() ?><br />
Millésime : <?php echo $vrac->millesime ?><br />
Type : <?php echo $vrac->type ?><br />
Quantité : <?php echo $vrac->volume_propose ?>hl<br />
Prix : <?php echo $vrac->prix_unitaire ?>€ net HT /hl<br />
<?php if ($vrac->vendeur_identifiant): ?>
Vendeur :<br />
<ul>
	<li>Nom commercial : <?php echo ($vrac->vendeur->nom)? $vrac->vendeur->nom : $vrac->vendeur->raison_sociale; ?></li>
	<li>Adresse e-mail : <?php echo $vrac->vendeur->email; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->vendeurHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
<?php if ($vrac->acheteur_identifiant): ?>
Acheteur :<br />
<ul>
	<li>Nom commercial : <?php echo ($vrac->acheteur->nom)? $vrac->acheteur->nom : $vrac->acheteur->raison_sociale; ?></li>
	<li>Adresse e-mail : <?php echo $vrac->acheteur->email; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->acheteurHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
<?php if ($vrac->mandataire_identifiant): ?>
Courtier :<br />
<ul>
	<li>Nom commercial : <?php echo ($vrac->mandataire->nom)? $vrac->mandataire->nom : $vrac->mandataire->raison_sociale; ?></li>
	<li>Adresse e-mail : <?php echo $vrac->mandataire->email; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->mandataireHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
Commentaire : <?php echo $vrac->commentaires ?><br /><br />
Votre contrat a bien été enregistré. Il va être envoyé aux autres parties concernées pour validation.<br />
Vous recevrez une version du contrat en .pdf avec le numéro de contrat lorsque toutes les parties auront validé le contrat.<br /> 
Le contrat ne sera valable que lorsque vous aurez reçu cette version pdf faisant figurer le numéro de contrat.<br /><br />
Attention si le contrat n'est pas validé d'ici 10 jours par vos partenaires, il sera automatiquement supprimé et non valable.<br /><br />
Si un des partenaires n'est pas inscrit sur DeclarVins, l'interprofession peut valider un contrat interprofessionnel en back office si elle est en possession d'une contrepartie écrite, signée. Une fois que toutes les parties ont signé, cela crée un numéro de VISA et envoie un mail avec le contrat en pdf à toutes les parties.<br /><br />
Aidez vos partenaires à s'inscrire pour profiter des services de DeclarVins et gagner du temps, notamment signer en ligne les contrats interprofessionnels : affectation d'un numéro de VISA, et envoi d'un mail avec le contrat valide en pdf à toutes les parties IMMEDIAT (dès signature par la dernière partie concernée).<br /><br />
Pour toute information, vous pouvez <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>