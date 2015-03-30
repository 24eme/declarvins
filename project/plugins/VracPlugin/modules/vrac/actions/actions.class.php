<?php
class vracActions extends acVinVracActions
{
	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $compte, $vrac)
	{
		return VracFormDeclarvinFactory::create($interproId, $etape, $configurationVrac, $etablissement, $compte, $vrac);
	}
	
	protected function saisieTerminee($vrac, $interpro) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
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
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracDemandeValidationInterpro($vrac, $interpro->email_contrat_vrac, $acteur);
					}
				} else {
					Email::getInstance()->vracDemandeValidation($vrac, $etablissement, $compte->email, $acteur);
				}
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
				} else {
					Email::getInstance()->vracContratValide($vrac, $etablissement, $compte->email);
				}
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
				} else {
					Email::getInstance()->vracContratModifie($vrac, $etablissement, $compte->email);
				}
			}
		}
	}
	
	protected function contratValidation($vrac, $acteur) {
		return;
	}
	
	protected function contratAnnulation($vrac, $interpro, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$etab = null;
		if ($this->annulation->etablissement) {
			$etab = EtablissementClient::getInstance()->find($this->annulation->etablissement);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
							Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $interpro->email_contrat_vrac);
					}
				} else {
					Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $compte->email);
				}
			}
		}	
	}
	

	
	protected function contratDemandeAnnulation($vrac, $interpro, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$etab = null;
		if ($vrac->annulation->etablissement) {
			$etab = EtablissementClient::getInstance()->find($vrac->annulation->etablissement);
		}
		if ($etab) {
			unset($acteurs[array_search($vrac->getTypeByEtablissement($etab->identifiant), $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro && $interpro->email_contrat_vrac) {
						Email::getInstance()->vracDemandeAnnulationInterpro($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
					}
				} else {
					Email::getInstance()->vracDemandeAnnulation($vrac, $etab, $etablissement, $compte->email, $acteur);
				}
			} else {
				if ($interpro && $interpro->email_contrat_vrac) {
					Email::getInstance()->vracDemandeAnnulationInterpro($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
				}
			}
		}
	}
	

	
	protected function contratRefusAnnulation($vrac, $interpro, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		if ($etab) {
			unset($acteurs[array_search($vrac->getTypeByEtablissement($etab->identifiant), $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = $etablissement->getCompteObject();
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro && $interpro->email_contrat_vrac) {
						Email::getInstance()->vracRefusAnnulation($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
					}
				} else {
					Email::getInstance()->vracRefusAnnulation($vrac, $etab, $etablissement, $compte->email, $acteur);
				}
			} else {
				if ($interpro && $interpro->email_contrat_vrac) {
					Email::getInstance()->vracRefusAnnulation($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
				}
			}
		}
	}

}