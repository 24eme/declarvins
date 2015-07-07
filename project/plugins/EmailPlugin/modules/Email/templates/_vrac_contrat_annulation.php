<?php echo include_partial('Email/headerMail') ?>

Entreprise : <?php if($vrac->{$acteur}->nom) { echo $vrac->{$acteur}->nom; } if($vrac->{$acteur}->raison_sociale) { echo ($vrac->{$acteur}->nom)? ' / '.$vrac->{$acteur}->raison_sociale : $vrac->{$acteur}->raison_sociale; } echo ($vrac->{$acteur}->famille)? ' - '.ucfirst($vrac->{$acteur}->famille) : ''; ?><?php if ($vrac->{$acteur}->telephone) {echo ' '.$vrac->{$acteur}->telephone;} if ($vrac->{$acteur}->fax) {echo ' '.$vrac->{$acteur}->fax;} if ($vrac->{$acteur}->email) {echo ' '.$vrac->{$acteur}->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
<?php if ($etablissement): ?>
La demande d'annulation du contrat de transaction saisi le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> par l'entreprise <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?> a été validée par toute les parties.<br />
<strong>Ce contrat a donc été supprimé et est considéré comme non valable.</strong><br /><br />
<?php else: ?>
Votre interprofession a annulé le contrat numéro <?php echo $vrac->numero_contrat; ?> vous concernant.<br /><br />
<strong>Ce contrat a donc été supprimé et est considéré comme non valable.</strong><br /><br />
<?php endif; ?>
Pour mémoire, le contrat portait sur la transaction suivante :<br />
Date de saisie : <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?><br />
Produit : <?php echo $vrac->getLibelleProduit() ?><br />
Millésime : <?php echo $vrac->millesime ?><br />
Type : <?php echo $vrac->type ?><br />
Quantité : <?php echo $vrac->volume_propose ?>hl<br />
Prix : <?php echo $vrac->prix_unitaire ?>€ net HT /hl<br />
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
Nous vous invitons à vous rapprocher de vos partenaires.<br /><br />
Pour toute information, vous pouvez <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>