<?php echo include_partial('Email/headerMail') ?>

Nom : <strong><?php echo $datas['nom'] ?></strong><br />
Prénom : <strong><?php echo $datas['prenom'] ?></strong><br />
Téléphone : <strong><?php echo $datas['telephone'] ?></strong><br />
Email : <strong><?php echo $datas['email'] ?></strong><br /><br />
<strong><?php echo $datas['sujet'] ?></strong><br />
<?php echo $datas['message'] ?>

<?php echo include_partial('Email/footerMail') ?>