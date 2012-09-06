<?php

class etablissement_autocompleteActions extends sfActions
{

  	public function executeAll(sfWebRequest $request) {
  		if($this->getUser()->getInterpro()) {
	    	$interpro = $this->getUser()->getInterpro()->get('_id');
	    } else {
	    	$interpro = 'INTERPRO-CIVP';
	    }

	    $json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByInterpro($interpro->get('_id'))->rows,
	    								   $request->getParameter('q'),
	    								   $request->getParameter('limit', 100));

	    return $this->renderText(json_encode($json));
  	}

 	public function executeByFamilles(sfWebRequest $request) {
	    
	    if($this->getUser()->getInterpro()) {
	    	$interpro = $this->getUser()->getInterpro()->get('_id');
	    } else {
	    	$interpro = 'INTERPRO-CIVP';
	    }
		$familles = $request->getParameter('familles');
		
	    $json = $this->matchEtablissements(
	    	EtablissementAllView::getInstance()->findByInterproAndFamilles($interpro, explode('|', $familles)),
		    $request->getParameter('q'),
		   	$request->getParameter('limit', 100)
		);
	    
 		return $this->renderText(json_encode($json));	
  	}

    protected function matchEtablissements($etablissements, $term, $limit) {
    	$json = array();

	  	foreach($etablissements as $key => $etablissement) {
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
