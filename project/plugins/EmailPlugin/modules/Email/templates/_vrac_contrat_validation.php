<?php echo include_partial('Email/headerMail') ?>

Votre validation a bien été prise en compte.<br /><br />
Vous recevrez prochainement le <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', array('sf_subject' => $vrac, 'etablissement' => $etablissement), true); ?>">contrat</a> validé en pdf (après validation des éventuelles autres parties) ou un message d'annulation de contrat (en cas de non validation par l'une des parties).<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>