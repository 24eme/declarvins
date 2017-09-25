<?php echo include_partial('Email/headerMail') ?>

Entreprise :  <?php if($vrac->{$acteur}->nom) { echo $vrac->{$acteur}->nom; } if($vrac->{$acteur}->raison_sociale) { echo ($vrac->{$acteur}->nom)? ' / '.$vrac->{$acteur}->raison_sociale : $vrac->{$acteur}->raison_sociale; } echo ($vrac->{$acteur}->famille)? ' - '.ucfirst($vrac->{$acteur}->famille) : ''; ?><?php if ($vrac->{$acteur}->telephone) {echo ' '.$vrac->{$acteur}->telephone;} if ($vrac->{$acteur}->fax) {echo ' '.$vrac->{$acteur}->fax;} ?><br /><br />
Madame, Monsieur,<br /><br />
Vous avez reçu une demande de validation d'un contrat de transaction en vrac le <strong><?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?></strong>.<br />
A ce jour,  vous n'avez toujours pas <strong>validé ou refusé ce contrat</strong>.<br /><br />
Pour mémoire, le contrat portait sur la transaction suivante :<br />
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
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->vendeurHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
<?php if ($vrac->acheteur_identifiant): ?>
Acheteur :<br />
<ul>
	<li><?php if($vrac->acheteur->nom) { echo $vrac->acheteur->nom; } if($vrac->acheteur->raison_sociale) { echo ($vrac->acheteur->nom)? ' / '.$vrac->acheteur->raison_sociale : $vrac->acheteur->raison_sociale; } echo ($vrac->acheteur->famille)? ' - '.ucfirst($vrac->acheteur->famille) : ''; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->acheteurHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
<?php if ($vrac->mandataire_identifiant): ?>
Courtier :<br />
<ul>
	<li><?php if($vrac->mandataire->nom) { echo $vrac->mandataire->nom; } if($vrac->mandataire->raison_sociale) { echo ($vrac->mandataire->nom)? ' / '.$vrac->mandataire->raison_sociale : $vrac->mandataire->raison_sociale; } echo ($vrac->mandataire->famille)? ' - '.ucfirst($vrac->mandataire->famille) : ''; ?></li>
	<li>Inscrit sur DeclarVins.net : <?php echo ($vrac->mandataireHasCompteActif())? 'oui' : 'non'; ?></li>
</ul> 
<br />
<?php endif; ?>
Observations : <?php echo $vrac->observations ?><br /><br />
<strong>Si vous souhaitez valider ou refuser ce contrat, cliquez sur le lien suivant : <a href="<?php echo $url['lien']; ?>">Valider ou refuser le contrat</a></strong><br /><br />
Si vous n'êtes pas inscrit sur DeclarVins.net, nous vous invitons à vous inscrire en suivant le lien : <a href="<?php echo $url['home']; ?>">Inscription à DeclarVins.net</a><br />
Le contrat ne sera valable que lorsque vous aurez reçu la version pdf faisant figurer le numéro de contrat.<br /><br />
Attention si le contrat n'est pas validé dans les 10 jours à compter de sa date de saisie, il sera automatiquement supprimé et non valable.<br />
Si un des partenaires n'est pas inscrit sur DeclarVins, l'interprofession peut valider un contrat interprofessionnel en back office si elle est en possession d'une contrepartie écrite, signée. Une fois que toutes les parties ont signé, cela crée un numéro de VISA et envoie un mail avec le contrat en pdf à toutes les parties.<br /><br />
Aidez vos partenaires à s'inscrire pour profiter des services de DeclarVins et gagner du temps, notamment signer en ligne les contrats interprofessionnels : affectation d'un numéro de VISA, et envoi d'un mail avec le contrat valide en pdf à toutes les parties IMMEDIAT (dès signature par la dernière partie concernée).<br /><br />
Pour toute information, vous pouvez <a href="<?php echo $url['contact']; ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>