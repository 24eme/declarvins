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

    protected static function getMailer() 
    {
        return sfContext::getInstance()->getMailer();
    }

    protected static function getBodyFromPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('Email', 'main')->getPartial('Email/' . $partial, $vars);
    }

}