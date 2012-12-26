<?php

class etablissement_autocompleteActions extends sfActions
{

  	public function executeAll(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$only_actif = $request->getParameter('only_actif');
	    $this->json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByInterpro($interpro)->rows,
	    								   $request->getParameter('q'),
	    								   $request->getParameter('limit', 100),
	    								   $only_actif);

		$this->setTemplate('index');
  	}

 	public function executeByFamilles(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$familles = $request->getParameter('familles');
		$only_actif = $request->getParameter('only_actif');
		
	    $this->json = $this->matchEtablissements(
	    	EtablissementAllView::getInstance()->findByInterproAndFamilles($interpro, explode('|', $familles)),
		    $request->getParameter('q'),
		   	$request->getParameter('limit', 100),
		   	$only_actif
		);

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
	    $this->json = $this->matchEtablissements(
	    	EtablissementAllView::getInstance()->findByInterproAndSousFamilles($interpro, $result),
		    $request->getParameter('q'),
		   	$request->getParameter('limit', 100),
		   	$only_actif
		);

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

}
