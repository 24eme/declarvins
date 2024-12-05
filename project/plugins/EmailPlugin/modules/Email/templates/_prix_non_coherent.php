<?php echo include_partial('Email/headerMail') ?>

Un contrat vrac au prix non cohérent vient d'être validé : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', array('sf_subject' => $vrac), true); ?>"><?php echo $vrac->_id ?></a><br /><br />
Le contrat porte sur le produit <strong><?php echo trim($vrac->getLibelleProduit()) ?></strong>, <?php echo ($vrac->millesime)? 'millésime '.$vrac->millesime : 'Non millésimé'; ?>, le volume proposé est de <strong><?php echo $vrac->volume_propose; ?></strong> HL et le prix unitaire est de <strong><?php echo $vrac->prix_unitaire; ?></strong> €/HL.<br />

<?php echo include_partial('Email/footerMail') ?>
