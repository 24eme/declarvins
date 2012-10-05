<?php echo include_partial('Email/headerMail') ?>

Le contrat vrac n° <?php echo $vrac->numero_contrat ?> est validée.<br />
Vous pouvez le visualiser et télécharger le PDF en suivant ce lien : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', array('sf_subject' => $vrac, 'etablissement' => $etablissement)	, true); ?>">Contrat n°<?php echo $vrac->numero_contrat ?></a>

<?php echo include_partial('Email/footerMail') ?>