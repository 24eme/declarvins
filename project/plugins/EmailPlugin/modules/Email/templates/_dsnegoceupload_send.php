<?php echo include_partial('Email/headerMail') ?>

La DS Négoce ci-jointe a été déposée sur DeclarVins le <strong><?php echo strftime('%d/%m/%Y', strtotime($ds->date_depot)) ?></strong> par  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br />

<?php echo include_partial('Email/footerMail') ?>