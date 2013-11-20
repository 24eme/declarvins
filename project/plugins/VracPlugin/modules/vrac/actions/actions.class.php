<?php
class vracActions extends acVinVracActions
{
	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $compte, $vrac)
	{
		return VracFormDeclarvinFactory::create($interproId, $etape, $configurationVrac, $etablissement, $compte, $vrac);
	}
	
	protected function saisieTerminee($vrac, $interpro) {
		$acteurs = VracClient::getInstance()->getActeurs();
		$saisisseur = $vrac->vous_etes;
		if ($saisisseur && in_array($saisisseur, $acteurs)) {
			if ($email = $vrac->get($saisisseur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($saisisseur.'_identifiant'));
				Email::getInstance()->vracSaisieTerminee($vrac, $etablissement, $email);
			}
		}
		unset($acteurs[array_search($saisisseur, $acteurs)]);
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			if ($email = $vrac->get($acteur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
				if ($etablissement->compte) {
					if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
						if ($compte->statut == _Compte::STATUT_ARCHIVE) {
							if ($interpro->email_contrat_vrac) {
								Email::getInstance()->vracDemandeValidationInterpro($vrac, $interpro->email_contrat_vrac, $acteur);
							}
						} 
					} 
				} 
				Email::getInstance()->vracDemandeValidation($vrac, $etablissement, $email, $acteur);
			} else {
				if ($email = $interpro->email_contrat_vrac) {
					Email::getInstance()->vracDemandeValidationInterpro($vrac, $email, $acteur);
				}
			}
		}
	}
	
	protected function contratValide($vrac) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$interpros = array();
		foreach ($acteurs as $acteur) {
			if ($email = $vrac->get($acteur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
				$interpro = $this->getInterpro($vrac, $etablissement);
				$interpros[$interpro->_id] = $interpro;
				$configurationVrac = $this->getConfigurationVrac($interpro->_id);
				$pdf = new ExportVracPdf($vrac, $configurationVrac);
    			$pdf->generate();
				if ($etablissement->compte) {
					if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
						if ($compte->statut == _Compte::STATUT_ARCHIVE) {
							if ($interpro->email_contrat_vrac) {
								Email::getInstance()->vracContratValide($vrac, $etablissement, $interpro->email_contrat_vrac);
							}
						}
					}
				}
				Email::getInstance()->vracContratValide($vrac, $etablissement, $email);
			}
		}
		/*foreach ($interpros as $interpro) {
			if ($interpro->email_contrat_vrac) {
				Email::getInstance()->vracContratValideInterpro($vrac, $interpro->email_contrat_vrac);
			}
		}*/
	}
	
	protected function contratValidation($vrac, $acteur) {
		/*$acteurs = VracClient::getInstance()->getActeurs();
      	if (!$acteur || !in_array($acteur, $acteurs)) {
        	throw new sfException('Acteur '.$acteur.' invalide!');
      	}
		if ($email = $vrac->get($acteur)->email) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			if ($etablissement->compte) {
				if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
					if ($compte->statut == _Compte::STATUT_ARCHIVE) {
						if ($interpro->email_contrat_vrac) {
							Email::getInstance()->vracContratValidation($vrac, $etablissement, $interpro->email_contrat_vrac);
						}
					}
				}
			}
			Email::getInstance()->vracContratValidation($vrac, $etablissement, $email);
		}*/
		return;
	}
	
	protected function contratAnnulation($vrac, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		foreach ($acteurs as $acteur) {
			if ($email = $vrac->get($acteur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
	    		$send_mail = true;
				if ($etab && $etab->get('_id') == $etablissement->get('_id')) {
					$send_mail = false;
				}
				if ($etablissement->compte) {
					if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
						if ($compte->statut == _Compte::STATUT_ARCHIVE) {
							if ($interpro->email_contrat_vrac) {
								if ($send_mail)
									Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $interpro->email_contrat_vrac);
							}
						}
					}
				}
				if ($send_mail)
					Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $email);
			}
		}	
	}

}