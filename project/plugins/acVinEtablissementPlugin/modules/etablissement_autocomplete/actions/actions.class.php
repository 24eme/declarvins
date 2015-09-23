<?php

class etablissement_autocompleteActions extends sfActions
{

  	public function executeAll(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$only_actif = $request->getParameter('only_actif');
		if (preg_match('/zone/i', $interpro)) {
			$this->json = array();
			foreach (explode('|', $interpro) as $zone) {
				$this->json = array_merge($this->json,
						$this->matchEtablissements(EtablissementAllView::getInstance()->findByZone($zone)->rows,
								$request->getParameter('q'),
								$request->getParameter('limit', 100),
								$only_actif)
				);
			}
		} else {
	    	$this->json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByZone($this->getZone($interpro))->rows,
	    								   $request->getParameter('q'),
	    								   $request->getParameter('limit', 100),
	    								   $only_actif);
		}
		$this->setTemplate('index');
  	}
  	
	public function executeAllAdmin(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$only_actif = $request->getParameter('only_actif');

		if (preg_match('/zone/i', $interpro)) {
			$this->json = array();
			foreach (explode('|', $interpro) as $zone) {
				$this->json = array_merge($this->json,
						$this->matchEtablissements(EtablissementAllView::getInstance()->findAllByZone($zone),
								$request->getParameter('q'),
								$request->getParameter('limit', 100),
								$only_actif)
				);
			}
		} else {
			$this->json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByZone($this->getZone($interpro)),
					$request->getParameter('q'),
					$request->getParameter('limit', 100),
					$only_actif);
		}
		$this->setTemplate('index');
  	}

 	public function executeByFamilles(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$familles = $request->getParameter('familles');
		$only_actif = $request->getParameter('only_actif');

		if (preg_match('/zone/i', $interpro)) {
			$this->json = array();
			foreach (explode('|', $interpro) as $zone) {
				$this->json = array_merge($this->json,
						$this->matchEtablissements(EtablissementAllView::getInstance()->findByZoneAndFamilles($zone, explode('|', $familles)),
								$request->getParameter('q'),
								$request->getParameter('limit', 100),
								$only_actif)
				);
			}
		} else {
			$this->json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByZoneAndFamilles($this->getZone($interpro), explode('|', $familles)),
					$request->getParameter('q'),
					$request->getParameter('limit', 100),
					$only_actif);
		}
 		$this->setTemplate('index');	
  	}

 	public function executeBySousFamilles(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$famille = explode('|', $request->getParameter('familles'));
		$sous_famille = explode('|', $request->getParameter('sous_familles'));
		$only_actif = $request->getParameter('only_actif');
		$result = array();
		for ($i=0, $nb = count($famille); $i<$nb; $i++) {
			$result[$famille[$i]] = $sous_famille[$i];
		}

		if (preg_match('/zone/i', $interpro)) {
			$this->json = array();
			foreach (explode('|', $interpro) as $zone) {
				$this->json = array_merge($this->json,
						$this->matchEtablissements(EtablissementAllView::getInstance()->findByZoneAndSousFamilles($zone, $result),
								$request->getParameter('q'),
								$request->getParameter('limit', 100),
								$only_actif)
				);
			}
		} else {
			$this->json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByZoneAndSousFamilles($this->getZone($interpro), $result),
					$request->getParameter('q'),
					$request->getParameter('limit', 100),
					$only_actif);
		}
 		$this->setTemplate('index');	
  	}

    protected function matchEtablissements($etablissements, $term, $limit, $only_actif) {
    	$json = array();

	  	foreach($etablissements as $key => $etablissement) {
	  		if ($only_actif && $etablissement->key[EtablissementAllView::KEY_STATUT] != Etablissement::STATUT_ACTIF) {
	  			continue;
	  		}
	      $text = EtablissementAllView::getInstance()->makeLibelle($etablissement->key);
	     
	      if (Search::matchTerm($term, $text)) {
	        $json[EtablissementClient::getInstance()->getIdentifiant($etablissement->id)] = $text;
	      }

	      if (count($json) >= $limit) {
	        break;
	      }
	      
	    }
	    return $json;
	}
	
	protected function getZone($interproId)
	{
		$interpro = InterproClient::getInstance()->find($interproId);
		return $interpro->zone;
	}

}
