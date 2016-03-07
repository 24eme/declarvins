<?php

class Email {
	
	private static $_instance = null;
	protected $_context;
	
	public function __construct($context = null) { 
		$this->_context = ($context)? $context : sfContext::getInstance();
	}
	
	public static function getInstance($context = null)
    {
       	if(is_null(self::$_instance)) {
       		self::$_instance = new Email($context);
		}
		return self::$_instance;
    }
    
    public function vracSaisieTerminee($vrac, $etablissement, $destinataire) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Confirmation de votre saisie d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        if ($vrac->isRectificative()) {
        	$subject .= ' RECTIFIE';
        }
        $body = $this->getBodyFromPartial('vrac_saisie_terminee', array('vrac' => $vrac, 'etablissement' => $etablissement));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracDemandeValidation($vrac, $etablissement, $destinataire, $acteur) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Demande de validation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        if ($vrac->isRectificative()) {
        	$subject .= ' RECTIFIE';
        }
        $body = $this->getBodyFromPartial('vrac_demande_validation', array('vrac' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracDemandeValidationInterpro($vrac, $destinataire, $acteur) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Demande de validation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        if ($vrac->isRectificative()) {
        	$subject .= ' RECTIFIE';
        }
        $body = $this->getBodyFromPartial('vrac_demande_validation_interpro', array('vrac' => $vrac, 'acteur' => $acteur));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracContratValide($vrac, $etablissement, $destinataire) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Validation du contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        $body = $this->getBodyFromPartial('vrac_contrat_valide', array('vrac' => $vrac, 'etablissement' => $etablissement));
		$message = Swift_Message::newInstance()
  					->setFrom($from)
  					->setTo($to)
  					->setSubject($subject)
  					->setBody($body)
  					->setContentType('text/html')
  					->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$vrac->get('_id').'.pdf'));
		
        return $this->getMailer()->send($message);
    }
    
    public function vracTransaction($vrac, $etablissement, $oioc, $cc) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($oioc->email_transaction);
        $subject = 'Envoi de votre DECLARATION DE TRANSACTION à votre OIOC';
        $body = $this->getBodyFromPartial('vrac_transaction', array('vrac' => $vrac, 'etablissement' => $etablissement, 'oioc' => $oioc));
		$message = Swift_Message::newInstance()
  					->setFrom($from)
  					->setTo($to)
  					->setCc($cc)
  					->setReplyTo($cc)
  					->setSubject($subject)
  					->setBody($body)
  					->setContentType('text/html')
  					->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$vrac->get('_id').'-TRANSACTION.pdf'));
		
        return $this->getMailer()->send($message);
    }
    
    public function vracContratModifie($vrac, $etablissement, $destinataire) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Modification d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        $body = $this->getBodyFromPartial('vrac_contrat_modifie', array('vrac' => $vrac, 'etablissement' => $etablissement));
		$message = Swift_Message::newInstance()
  					->setFrom($from)
  					->setTo($to)
  					->setSubject($subject)
  					->setBody($body)
  					->setContentType('text/html')
  					->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$vrac->get('_id').'.pdf'));
		
        return $this->getMailer()->send($message);
    }
    
    public function vracContratValideInterpro($vrac, $destinataire) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Validation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        $body = $this->getBodyFromPartial('vrac_contrat_valide_interpro', array('vrac' => $vrac));
		$message = Swift_Message::newInstance()
  					->setFrom($from)
  					->setTo($to)
  					->setSubject($subject)
  					->setBody($body)
  					->setContentType('text/html')
  					->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$vrac->get('_id').'.pdf'));
		
        return $this->getMailer()->send($message);
    }
    
    public function vracContratValidation($vrac, $etablissement, $destinataire) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Votre validation a bien été prise en compte';
        $body = $this->getBodyFromPartial('vrac_contrat_validation', array('vrac' => $vrac, 'etablissement' => $etablissement));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracContratAnnulation($vrac, $etablissement, $acteur, $destinataire) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Annulation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        $body = $this->getBodyFromPartial('vrac_contrat_annulation', array('vrac' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    

    
    public function vracDemandeAnnulation($vrac, $etab, $etablissement, $destinataire, $acteur) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Demande d\'annulation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        if ($vrac->isRectificative()) {
        	$subject .= ' RECTIFIE';
        }
        $body = $this->getBodyFromPartial('vrac_demande_annulation', array('vrac' => $vrac, 'etab' => $etab, 'etablissement' => $etablissement, 'acteur' => $acteur));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    

    
    public function vracRefusAnnulation($vrac, $etab, $etablissement, $destinataire, $acteur) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Refus d\'annulation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        if ($vrac->isRectificative()) {
        	$subject .= ' RECTIFIE';
        }
        $body = $this->getBodyFromPartial('vrac_refus_annulation', array('vrac' => $vrac, 'etab' => $etab, 'etablissement' => $etablissement, 'acteur' => $acteur));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracDemandeAnnulationInterpro($vrac, $etab, $etablissement, $destinataire, $acteur) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Demande d\'annulation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        if ($vrac->isRectificative()) {
        	$subject .= ' RECTIFIE';
        }
        $body = $this->getBodyFromPartial('vrac_demande_annulation_interpro', array('vrac' => $vrac, 'etab' => $etab, 'etablissement' => $etablissement, 'acteur' => $acteur));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracRelanceContrat($vrac, $etablissement, $destinataire, $acteur, $url) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Relance : Demande de validation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true);
        $body = $this->getBodyFromPartial('vrac_contrat_relance', array('vrac' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur, 'url' => $url));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');
		return $message;
        return $this->getMailer()->send($message);
    }
    
    public function vracExpirationContrat($vrac, $etablissement, $destinataire, $acteur, $url) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Suppression d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true).' suite au dépassement du délai';
        $body = $this->getBodyFromPartial('vrac_contrat_expiration', array('vrac' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur, 'url' => $url));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function vracExpirationAnnulationContrat($vrac, $etablissement, $destinataire, $acteur, $url) 
    {
        $interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = 'Suppression de l\'annulation d\'un contrat interprofessionnel '.$vrac->getLibelleProduit("%c% %a%", true).' suite au dépassement du délai';
        $body = $this->getBodyFromPartial('vrac_contrat_expiration_annulation', array('vrac' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur, 'url' => $url));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function sendContratMandat($contrat, $destinataire, $interpros = null) 
    {
        $from = $this->getFromEmailInterpros($interpros,true);
        $to = array($destinataire);
        $subject = 'Contrat d\'inscription DeclarVins';
        $body = $this->getBodyFromPartial('send_contrat_mandat', array('contrat' => $contrat));
        $message = Swift_Message::newInstance()
  					->setFrom($from)
  					->setTo($to)
  					->setSubject($subject)
  					->setBody($body)
  					->setContentType('text/html')
  					->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$contrat->get('_id').'.pdf'));
		return $this->getMailer()->send($message);
    }
    
    public function sendCompteRegistration($compte, $destinataire) 
    {
    	$interpros = array();
    	foreach ($compte->interpro as $id => $values) {
    		$interpros[] = InterproClient::getInstance()->find($id);
    	}
        $from = $this->getFromEmailInterpros($interpros,true);
        $to = array($destinataire);
        $subject = 'Activation de votre compte sur Declarvins.net';
    	$numeroContrat = explode('-', $compte->contrat);
    	$numeroContrat = $numeroContrat[1];
        $body = $this->getBodyFromPartial('send_compte_registration', array('numero_contrat' => $numeroContrat));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }
    
    public function sendRedefinitionMotDePasse($compte, $destinataire, $logins) 
    {
    	$interpros = array();
    	foreach ($compte->interpro as $id => $values) {
    		$interpros[] = InterproClient::getInstance()->find($id);
    	}
        $from = $this->getFromEmailInterpros($interpros,true);
        $to = array($destinataire);
        $subject = 'Redéfinition du mot de passe';
        $body = $this->getBodyFromPartial('send_redefinition_mot_de_passe', array('compte' => $compte, 'logins' => $logins));
        $message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');

        return $this->getMailer()->send($message);
    }

    protected function getMailer() 
    {
        return $this->_context->getMailer();
    }

    protected function getBodyFromPartial($partial, $vars = null) 
    {
        return $this->_context->getController()->getAction('Email', 'main')->getPartial('Email/' . $partial, $vars);
    }

    protected function getFromEmailInterpros($interpros = array(), $isInscription = false) {
    	$referente = InterproClient::getInstance()->getById(InterproClient::INTERPRO_REFERENTE);
        if(!(count($interpros) > 0)){
            return (!$isInscription)? array($referente->email_contrat_vrac => $referente->nom) : array($referente->email_contrat_inscription => $referente->nom);
        }
        $interpro = null;
        if (count($interpros) > 0) {
        	foreach ($interpros as $inter) {
        		if ($inter->_id == InterproClient::INTERPRO_REFERENTE) {
        			$interpro = $inter;
        		}
        	}
        }
        if (!$interpro) {
                foreach($interpros as $inter) {
                		if ($inter->email_contrat_vrac && $inter->email_contrat_inscription) {
                        	$interpro = $inter;
                        	break;
                		}
                }
        }
        if(!$isInscription){
            return ($interpro->email_contrat_vrac)? array($interpro->email_contrat_vrac => $interpro->nom) : array($referente->email_contrat_vrac => $referente->nom);
        }
        return ($interpro->email_contrat_inscription)? array($interpro->email_contrat_inscription => $interpro->nom) : array($referente->email_contrat_inscription => $referente->nom);
    }
}