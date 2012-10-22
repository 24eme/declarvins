<?php echo include_partial('Email/headerMail') ?>

Vous devez valider le contrat vrac n° <?php echo $vrac->numero_contrat ?>.<br />
Vous pouvez le valider en suivant ce lien : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_validation', array('sf_subject' => $vrac), true); ?>">Contrat n°<?php echo $vrac->numero_contrat ?></a>

<?php echo include_partial('Email/footerMail') ?>