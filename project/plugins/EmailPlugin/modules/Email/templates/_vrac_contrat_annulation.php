<?php echo include_partial('Email/headerMail') ?>

Entreprise : <?php echo ($vrac->{$acteur}->raison_sociale)? $vrac->{$acteur}->raison_sociale : $vrac->{$acteur}->nom; ?><?php if ($vrac->{$acteur}->telephone) {echo ' '.$vrac->{$acteur}->telephone;} if ($vrac->{$acteur}->fax) {echo ' '.$vrac->{$acteur}->fax;} if ($vrac->{$acteur}->email) {echo ' '.$vrac->{$acteur}->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
Le contrat de transaction saisi le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> a été refusé par l'entreprise <?php echo ($etablissement->raison_sociale)? $etablissement->raison_sociale : $etablissement->nom; ?>.<br />
<strong>Ce contrat a donc été supprimé et est considéré comme non valable.</strong><br /><br />
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
Nous vous invitons à vous rapprocher de vos partenaires.<br /><br />
Pour toute information, vous pouvez <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>