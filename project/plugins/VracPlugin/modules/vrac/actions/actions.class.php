<?php
class vracActions extends acVinVracActions
{
	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $vrac)
	{
		return VracFormDeclarvinFactory::create($interproId, $etape, $configurationVrac, $etablissement, $vrac);
	}
	
	protected function saisieTerminee($vrac) {
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
				Email::getInstance()->vracDemandeValidation($vrac, $etablissement, $email);
			}
		}
	}
	
	protected function contratValide($vrac) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			if ($email = $vrac->get($acteur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
				Email::getInstance()->vracContratValide($vrac, $etablissement, $email);
			}
		}
	}
	
	protected function contratValidation($vrac, $acteur) {
		$acteurs = VracClient::getInstance()->getActeurs();
      	if (!$acteur || !in_array($acteur, $acteurs)) {
        	throw new sfException('Acteur '.$acteur.' invalide!');
      	}
		if ($email = $vrac->get($acteur)->email) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			Email::getInstance()->vracContratValidation($vrac, $etablissement, $email);
		}
	}

}