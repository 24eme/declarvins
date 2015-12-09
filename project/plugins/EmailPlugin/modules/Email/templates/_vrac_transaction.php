<?php echo include_partial('Email/headerMail') ?>

A destination de <?php echo $oioc->nom ?>,<br /><br />
Veuillez trouver en pièce jointe la déclaration de transaction <?php echo $vrac->numero_contrat; ?> validé le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_validation)) ?>.<br />
Ce mail ne vaut pas Accusé de Réception ni Accusé de Traitement. C’est votre OIOC qui vous confirmera la réception de votre déclaration de transaction.<br />
Sans réponse de <?php echo $oioc->nom ?> dans les <strong><u><?php echo $oioc->delai_reponses ?> heures ouvrées</u>, vous devrez prendre directement contact avec votre OIOC afin de leur transmettre vous-même votre déclaration de transaction (« PDF Transaction »)</strong>.<br />
Votre interprofession ne pourra être tenu responsable de la non réception de votre déclaration de transaction par votre OIOC. Pour toute information, vous pouvez contacter votre interprofession ou votre Organisme de Contrôle.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>