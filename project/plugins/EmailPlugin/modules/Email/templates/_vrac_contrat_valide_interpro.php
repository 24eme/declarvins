<?php echo include_partial('Email/headerMail') ?>

Le contrat de transaction saisi le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> a été validé par toutes les parties et se voit attribué un VISA/numéro de contrat.<br />
Vous trouverez ci-joint une version pdf avec le numéro de contrat correspondant.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>