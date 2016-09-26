<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
<?php if ($vrac->isRectificative()): ?>
Vous avez rectifié le contrat numéro <?php echo $vrac->numero_contrat; ?> le  <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?>.<br />
<?php else: ?>
Vous avez saisi un contrat interprofessionnel le  <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> à <?php echo strftime('%R', strtotime($vrac->valide->date_saisie)) ?>.<br />
<?php endif; ?>
Ce contrat porte sur la transaction suivante :<br /><br />
Date de saisie : <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?><br />
Produit : <?php echo $vrac->getLibelleProduit() ?><br />
Millésime : <?php echo $vrac->millesime ?><br />
Type : <?php echo $vrac->type ?><br />
Quantité : <?php echo $vrac->volume_propose ?> <?php if($vrac->type_transaction != 'raisin'): ?>hl<?php else: ?>kg<?php endif;?><br />
Prix : <?php echo $vrac->prix_unitaire ?> <?php if($vrac->type_transaction != 'raisin'): ?>€(HT)/hl<?php else: ?>€/kg (Hors Taxes / Net)<?php endif;?><br />
<?php if ($vrac->vendeur_identifiant): ?>
Vendeur :<br />
<ul>
	<li><?php if($vrac->vendeur->nom) { echo $vrac->vendeur->nom; } if($vrac->vendeur->raison_sociale) { echo ($vrac->vendeur->nom)? ' / '.$vrac->vendeur->raison_sociale : $vrac->vendeur->raison_sociale; } echo ($vrac->vendeur->famille)? ' - '.ucfirst($vrac->vendeur->famille) : ''; ?></li>
	<li>Adresse e-mail : <?php echo $vrac->vendeur->email; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->vendeurHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
<?php if ($vrac->acheteur_identifiant): ?>
Acheteur :<br />
<ul>
	<li><?php if($vrac->acheteur->nom) { echo $vrac->acheteur->nom; } if($vrac->acheteur->raison_sociale) { echo ($vrac->acheteur->nom)? ' / '.$vrac->acheteur->raison_sociale : $vrac->acheteur->raison_sociale; } echo ($vrac->acheteur->famille)? ' - '.ucfirst($vrac->acheteur->famille) : ''; ?></li>
	<li>Adresse e-mail : <?php echo $vrac->acheteur->email; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->acheteurHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
<?php if ($vrac->mandataire_identifiant): ?>
Courtier :<br />
<ul>
	<li><?php if($vrac->mandataire->nom) { echo $vrac->mandataire->nom; } if($vrac->mandataire->raison_sociale) { echo ($vrac->mandataire->nom)? ' / '.$vrac->mandataire->raison_sociale : $vrac->mandataire->raison_sociale; } echo ($vrac->mandataire->famille)? ' - '.ucfirst($vrac->mandataire->famille) : ''; ?></li>
	<li>Adresse e-mail : <?php echo $vrac->mandataire->email; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->mandataireHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
Observations : <?php echo $vrac->observations ?><br /><br />
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