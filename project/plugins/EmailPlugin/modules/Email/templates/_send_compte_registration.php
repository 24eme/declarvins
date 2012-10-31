<?php echo include_partial('Email/headerMail') ?>
 	
Votre contrat numéro <?php echo $numero_contrat; ?> à été validé.<br />
Vous devez créer votre compte en suivant le lien suivant : <a href="<?php echo url_for('compte_nouveau', array('nocontrat' => $numero_contrat)); ?>">Création de mon compte</a>

<?php echo include_partial('Email/footerMail') ?>