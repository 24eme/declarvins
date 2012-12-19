<?php

class Email {
	
	private static $_instance = null;
	
	public function __construct() { }
	
	public static function getInstance()
    {
       	if(is_null(self::$_instance)) {
       		self::$_instance = new Email();
		}
		return self::$_instance;
    }
    
    public function vracSaisieTerminee($vrac, $etablissement, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Création du contrat vrac n°'.$vrac->numero_contrat;
        $body = self::getBodyFromPartial('vrac_saisie_terminee', array('vrac' => $vrac, 'etablissement' => $etablissement));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }
    
    public function vracDemandeValidation($vrac, $etablissement, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Demande de validation du contrat vrac n°'.$vrac->numero_contrat;
        $body = self::getBodyFromPartial('vrac_demande_validation', array('vrac' => $vrac, 'etablissement' => $etablissement));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }
    
    public function vracDemandeValidationInterpro($vrac, $destinataire, $acteur) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Demande de validation du contrat vrac n°'.$vrac->numero_contrat;
        $body = self::getBodyFromPartial('vrac_demande_validation_interpro', array('vrac' => $vrac, 'acteur' => $acteur));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }
    
    public function vracContratValide($vrac, $etablissement, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Le contrat vrac n°'.$vrac->numero_contrat.' est validé';
        $body = self::getBodyFromPartial('vrac_contrat_valide', array('vrac' => $vrac, 'etablissement' => $etablissement));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }
    
    public function vracContratValidation($vrac, $etablissement, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Vous avez bien validé le contrat vrac n°'.$vrac->numero_contrat;
        $body = self::getBodyFromPartial('vrac_contrat_validation', array('vrac' => $vrac, 'etablissement' => $etablissement));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }
    
    public function sendContratMandat($contrat, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Votre inscription a bien été prise en compte';
        $body = self::getBodyFromPartial('send_contrat_mandat', array('contrat' => $contrat));
        $message = Swift_Message::newInstance()
  					->setFrom($from)
  					->setTo($to)
  					->setSubject($subject)
  					->setBody($body)
  					->setContentType('text/html')
  					->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$contrat->get('_id').'.pdf'));
		return self::getMailer()->send($message);
    }
    
    public function sendCompteRegistration($compte, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Validation de votre contrat';
    	$numeroContrat = explode('-', $compte->contrat);
    	$numeroContrat = $numeroContrat[1];
        $body = self::getBodyFromPartial('send_compte_registration', array('numero_contrat' => $numeroContrat));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }
    
    public function sendRedefinitionMotDePasse($compte, $destinataire) 
    {
        $from = array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name'));
        $to = array($destinataire);
        $subject = 'Redefinition de votre mot de passe Declarvins';
        $body = self::getBodyFromPartial('send_redefinition_mot_de_passe', array('compte' => $compte));
        $message = self::getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return self::getMailer()->send($message);
    }

    protected static function getMailer() 
    {
        return sfContext::getInstance()->getMailer();
    }

    protected static function getBodyFromPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('Email', 'main')->getPartial('Email/' . $partial, $vars);
    }

}