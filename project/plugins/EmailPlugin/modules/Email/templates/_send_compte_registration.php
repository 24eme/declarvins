<?php echo include_partial('Email/headerMail') ?>
 	
Votre contrat numéro <?php echo $numero_contrat; ?> à été validé.<br />
Vous devez créer votre compte en suivant le lien suivant : <a href="<?php echo sfContext::getInstance()->getController()->genUrl(array('sf_route' => 'compte_nouveau', 'nocontrat' => $numero_contrat), false); ?>">Création de mon compte echo sfContext::getInstance()->getController()->genUrl(array('sf_route' => 'compte_nouveau', 'nocontrat' => $numero_contrat), false);</a>

<?php echo include_partial('Email/footerMail') ?>