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
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($saisisseur.'_identifiant'));
			if ($compte = $etablissement->getCompteObject()) {
				if ($compte->email) {
					Email::getInstance()->vracSaisieTerminee($vrac, $etablissement, $compte->email);
				}
			}
		}
		unset($acteurs[array_search($saisisseur, $acteurs)]);
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracDemandeValidationInterpro($vrac, $interpro->email_contrat_vrac, $acteur);
					}
				} 
				Email::getInstance()->vracDemandeValidation($vrac, $etablissement, $compte->email, $acteur);
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
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				$interpro = $this->getInterpro($vrac, $etablissement);
				$interpros[$interpro->_id] = $interpro;
				$configurationVrac = $this->getConfigurationVrac($interpro->_id);
				$pdf = new ExportVracPdf($vrac, $configurationVrac);
    			$pdf->generate();
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracContratValide($vrac, $etablissement, $interpro->email_contrat_vrac);
					}
				}
				Email::getInstance()->vracContratValide($vrac, $etablissement, $compte->email);
			}
		}
	}
	
	protected function contratModifie($vrac) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$interpros = array();
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				$interpro = $this->getInterpro($vrac, $etablissement);
				$interpros[$interpro->_id] = $interpro;
				$configurationVrac = $this->getConfigurationVrac($interpro->_id);
				$pdf = new ExportVracPdf($vrac, $configurationVrac);
    			$pdf->generate();
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracContratModifie($vrac, $etablissement, $interpro->email_contrat_vrac);
					}
				}
				Email::getInstance()->vracContratModifie($vrac, $etablissement, $compte->email);
			}
		}
	}
	
	protected function contratValidation($vrac, $acteur) {
		return;
	}
	
	protected function contratAnnulation($vrac, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
	    		$send_mail = true;
				if ($etab && $etab->get('_id') == $etablissement->get('_id')) {
					$send_mail = false;
				}
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						if ($send_mail)
							Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $interpro->email_contrat_vrac);
					}
				}
				if ($send_mail)
					Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $compte->email);
			}
		}	
	}

}