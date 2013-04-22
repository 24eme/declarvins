<?php echo include_partial('Email/headerMail') ?>
 	
Bonjour,<br /><br />
Suite à votre inscription en ligne sur le site Declarvins.net vos données ont été mises à jour et votre compte a bien été créé.<br />
Afin d'activer votre compte, veuillez créer votre identifiant et votre mot de passe en cliquant sur le lien suivant : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('compte_nouveau', array('nocontrat' => $numero_contrat), true); ?>">Création de mes identifiants</a><br /><br />
Pour toute question, n'hésitez pas à contacter votre interprofession :<br />
<ul>
	<li>
		<strong>Inter-Rhône</strong><br /><br />
		6, rue des Trois Faucons<br />
		84024 Avignon Cedex 1<br />
		France<br /><br />
		Tel : +33 (0)4 90 27 24 00<br />
		Fax : +33 (0)4 90 27 24 38<br />
		contact@inter-rhone.com<br /><br />
	</li>
	<li>
		<strong>Conseil Interprofessionnel des Vins de Provence</strong><br /><br />
		MAISON DES VINS<br />
		RN 7 - 83460 LES ARCS SUR ARGENS<br />
		Tel : 04 94 99 50 10<br />
		Fax : 04 94 99 50 19<br />
		civp@provencewines.com<br />
	</li>
</ul>
<br />
Bonne journée<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>