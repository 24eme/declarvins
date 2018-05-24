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


    public function vracTransactionAnnulation($vrac, $etablissement, $oioc, $destinataire)
    {
    	$interpros = array(InterproClient::getInstance()->getById($vrac->interpro));
    	$from = $this->getFromEmailInterpros($interpros);
    	$to = array($destinataire);
    	$subject = 'Annulation d\'une DECLARATION DE TRANSACTION à votre OIOC';
    	$body = $this->getBodyFromPartial('vrac_transaction_annulation', array('vrac' => $vrac, 'etablissement' => $etablissement, 'oioc' => $oioc));
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
    
    public function vracRelanceFromDRM($drm, $details, $destinataire) 
    {
        $interpros = array();
        foreach ($details as $detail) {
        	$interpros[$detail->interpro] = InterproClient::getInstance()->getById($detail->interpro);
        }
        $from = $this->getFromEmailInterpros($interpros);
        $to = array($destinataire);
        $subject = (count($details) > 1)? 'Contrats interprofessionnel manquants sur la DRM '.$drm->getMois().'/'.$drm->getAnnee() : 'Contrat interprofessionnel manquant sur la DRM '.$drm->getMois().'/'.$drm->getAnnee();
        $body = $this->getBodyFromPartial('vrac_relance_from_drm', array('drm' => $drm, 'details' => $details));
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
  		if ($contrat->needAvenant()) {
  			$message->attach(Swift_Attachment::fromPath(sfConfig::get('sf_data_dir').'/inscription/avenant-oco.pdf'));
  		}
		return $this->getMailer()->send($message);
    }

    public function sendConventionCiel($convention, $destinataire, $interpros = null, $contrat = null)
    {
    	$from = $this->getFromEmailInterpros($interpros,true);
    	$to = array($destinataire);
    	$subject = 'Convention d\'adhésion à l\'échange de données CIEL-Declarvins.net';
    	$body = $this->getBodyFromPartial('send_convention_ciel', array('convention' => $convention));
    	$message = Swift_Message::newInstance()
    	->setFrom($from)
    	->setTo($to)
    	->setSubject($subject)
    	->setBody($body)
    	->setContentType('text/html')
    	->attach(Swift_Attachment::fromPath(sfConfig::get('sf_data_dir').'/convention-ciel/pdf/'.$convention->get('_id').'.pdf'));
    	if ($contrat) {
    		$message->attach(Swift_Attachment::fromPath(sfConfig::get('sf_cache_dir').'/pdf/'.$contrat->get('_id').'_avenant.pdf'));
    	}
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
    
    public function sendCielAssistance($datas, $etablissement, $interpro = null)
    {
    	$from = $this->getFromEmailInterpros(array($interpro),true);
    	if ($interpro->identifiant == 'CIVP') {
    		$to = $interpro->email_contrat_inscription;
    	} else {
    		$to = $interpro->email_assistance_ciel;
    	}
    	$subject = $etablissement->identifiant.' | '.$datas['sujet'];
    	$body = $this->getBodyFromPartial('send_assistance_ciel', array('datas' => $datas));
    	$message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');
    	
    	return $this->getMailer()->send($message);
    	
    }
    
    public function cielSended($drm)
    {
    	$etablissement = $drm->getEtablissement();
    	$compte = $etablissement->getCompteObject();
    	if (!$compte->email) {
    		return null;
    	}
    	$from = $this->getFromEmailInterpros(array($etablissement->getInterproObject()),true);
    	$to = array($compte->email);
    	$subject = "Confirmation de l'envoi de votre DRM à CIEL";
    	$body = $this->getBodyFromPartial('ciel_sended', array('drm' => $drm, 'etablissement' => $etablissement));
    	$message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');
    	return $this->getMailer()->send($message);
    }
    
    public function cielRappel($drm)
    {
    	$etablissement = $drm->getEtablissement();
    	$compte = $etablissement->getCompteObject();
    	if (!$compte->email) {
    		return null;
    	}
    	$interpro = $etablissement->getInterproObject();
    	$from = $this->getFromEmailInterpros(array($interpro),true);
    	$to = array($compte->email, $interpro->email_contrat_inscription);
    	$subject = "Rappel validation de votre DRM sur CIEL";
    	$body = $this->getBodyFromPartial('ciel_rappel', array('drm' => $drm, 'etablissement' => $etablissement));
    	$message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');
    	$message->addBcc(sfConfig::get('app_email_to_notification'));
    	return $this->getMailer()->send($message);
    }
    
    public function cielRectificative($drm, $diffs, $interpro)
    {
    	$etablissement = $drm->getEtablissement();
    	$compte = $etablissement->getCompteObject();
    	if (!$compte->email) {
    		return null;
    	}
    	$from = $this->getFromEmailInterpros(array($etablissement->getInterproObject()),true);
    	$to = array($compte->email);
    	$subject = "Modification de votre DRM sur CIEL";
    	$body = $this->getBodyFromPartial('ciel_rectificative', array('drm' => $drm, 'diffs' => $diffs, 'etablissement' => $etablissement));
    	$message = $this->getMailer()->compose($from, $to, $subject, $body)->setContentType('text/html');
    	if ($interpro) {
    		$cc = array($interpro->email_contrat_inscription);
    		if ($interpro->identifiant == 'CIVP') {
    			$cc[] = $interpro->email_assistance_ciel;
    		}
    		$message->setCc($cc);
    	}
    	return $this->getMailer()->send($message);
    }
    
    public function cielValide($drm)
    {
    	$etablissement = $drm->getEtablissement();
    	$compte = $etablissement->getCompteObject();
    	if (!$compte->email) {
    		return null;
    	}
    	$from = $this->getFromEmailInterpros(array($etablissement->getInterproObject()),true);
    	$to = array($compte->email);
    	$subject = "Validation de votre DRM sur CIEL";
    	$body = $this->getBodyFromPartial('ciel_valide', array('drm' => $drm, 'etablissement' => $etablissement));
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