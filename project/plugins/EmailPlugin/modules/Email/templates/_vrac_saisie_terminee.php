<?php echo include_partial('Email/headerMail') ?>

Vous avez bien saisi le contrat vrac n°<?php echo $vrac->numero_contrat ?>.<br />
Celui-ci est en attente de validation par les autres acteurs du contrat.<br />
Vous pouvez accéder au contrat en suivant ce lien : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', array('sf_subject' => $vrac, 'etablissement' => $etablissement)	, true); ?>">Contrat n°<?php echo $vrac->numero_contrat ?></a>

<?php echo include_partial('Email/footerMail') ?>