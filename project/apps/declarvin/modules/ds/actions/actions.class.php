<?php
class dsActions extends acVinDSActions {

    public function executeIndex(sfWebRequest $request) {
			$compte = $this->getUser()->getCompte();
			$etablissement = null;
			$this->forward404Unless($compte->exist('tiers'));
			foreach($compte->tiers as $id => $tier) {
				$e = EtablissementClient::getInstance()->find($id);
				if ($e->hasZone(ConfigurationZoneClient::ZONE_PROVENCE) && $e->hasDroit(EtablissementDroit::DROIT_DS)) {
					$etablissement = $e;
					break;
				}
			}
			if ($etablissement) {
				return $this->redirect('ds_etablissement', array('identifiant' => $etablissement->getIdentifiant()));
			}
			$this->forward404Unless(false);
    }

}
