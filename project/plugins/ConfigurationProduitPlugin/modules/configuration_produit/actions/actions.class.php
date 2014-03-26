<?php

class configuration_produitActions extends sfActions
{
  	public function executeIndex(sfWebRequest $request)
  	{
  		$this->getUser()->setAttribute('pile_noeud', null);
  		$this->configurationProduits = $this->getConfigurationProduit();
	}
  
	public function executeImport(sfWebRequest $request)
  	{
  		$configurationProduits = $this->getConfigurationProduit();
  		$this->form = new UploadCSVForm();
  		$this->erreurs = array();
  		if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
			$this->form->bind($request->getParameter('csv'), $request->getFiles('csv'));
	    	if ($this->form->isValid()) {
	    		$file = $this->form->getValue('file');
	    		$csv = new ConfigurationProduitCsvFile($configurationProduits, $file->getSavedName());
	    		$configurationProduits = $csv->importProduits();
	    		$this->erreurs = $csv->getErrors();
	    		unlink($file->getSavedName());
	    		if (!$csv->hasErrors()) {
	    			$configurationProduits->save();
	    			if ($cache = $this->getContext()->getViewCacheManager()) {
    					$cache->remove('configuration_produit/index');
	    			}
	    			$this->getUser()->setFlash('notice', 'Catalogue produit mis à jour avec succès');
	    			$this->redirect('configuration_produit');
	    		}
	    	}
  		}
	}
	
	public function executeExport(sfWebRequest $request)
	{
		$configurationProduits = $this->getConfigurationProduit();
		$catalogue = new ConfigurationProduitCsvFile($configurationProduits);
		$csv = $catalogue->exportProduits();
		return $this->renderCsv($csv);
	}
	
	private function renderCsv($items, $date = null) 
  	{
	    $this->setLayout(false);
	    $csv_file = '';
	  	foreach ($items as $item) {
	      		$csv_file .= implode(';', $item)."\n";
	    }
	    $lastDate = ($date)? $date : date('Ymd');
	    if (!$csv_file) {
			$this->response->setStatusCode(204);
			return $this->renderText(null);
	    }
	    $this->response->setContentType('text/csv');
	    $this->response->setHttpHeader('md5', md5($csv_file));
	    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$lastDate."_Catalogue_Produit.csv");
	    $this->response->setHttpHeader('LastDocDate', $lastDate);
	    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
	    return $this->renderText(utf8_decode($csv_file));
  	}
	
	public function executeNouveau(sfWebRequest $request)
	{
		$configurationProduits = $this->getConfigurationProduit();
	  	$this->form = new ConfigurationProduitNouveauForm($configurationProduits);
	  	if ($request->isMethod(sfWebRequest::POST)) {
	        $this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$result = $this->form->getProduit();
				$hash = $result['hash'];
				$noeud = $result['noeud'];
				$values = $result['values'];
				if ($values) {
					$this->getUser()->setAttribute('pile_noeud', $values);
				}
				$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
				$this->redirect('configuration_produit_modification', array('noeud' => $noeud, 'hash' => str_replace('/', '-', $hash)));
			}
	    }
	}
	

  	public function executeModification(sfWebRequest $request)
  	{
  		$this->forward404Unless($noeud = $request->getParameter('noeud', null));
  		$this->forward404Unless($hash = $request->getParameter('hash', null));
	  	$this->nbDepartement = $request->getParameter('nb_departement', null);
	  	$this->nbDouane = $request->getParameter('nb_douane', null);
	  	$this->nbCvo = $request->getParameter('nb_cvo', null);
	  	$this->nbLabel = $request->getParameter('nb_label', null);
	  	$this->nbOrganisme = $request->getParameter('nb_organisme', null);
  		$hash = str_replace('-', '/', $hash);
  		$configurationProduits = $this->getConfigurationProduit();
  		$object = $configurationProduits->getOrAdd($hash);
  		$object = $object->__get($noeud);
  		$isNew = ($this->getUser()->hasAttribute('pile_noeud'))? true : false;
  		if ($isNew && !$request->isMethod(sfWebRequest::POST)) {
  			$pile = $this->getUser()->getAttribute('pile_noeud');
  			$correspondance = array_flip(ConfigurationProduit::getCorrespondanceNoeuds());
  			if (isset($correspondance[$noeud]) && isset($pile[$correspondance[$noeud]])) {
  				$object->set('libelle', $pile[$correspondance[$noeud]]);
  				if ($noeud == ConfigurationProduitCouleur::TYPE_NOEUD) {
  					$codeCouleurs = ConfigurationProduit::getCorrespondanceCodeCouleurs();
  					$libelleCouleurs = ConfigurationProduit::getCorrespondanceLibelleCouleurs();
  					$object->set('libelle', $libelleCouleurs[$pile[$correspondance[$noeud]]]);
  					$object->set('code', $codeCouleurs[$pile[$correspondance[$noeud]]]);
  				}
  			}
  		}
  		$this->noeud = $noeud;
  		$this->form = new ConfigurationProduitModificationForm($object, array('isNew' => $isNew, 'nbDepartement' => $this->nbDepartement, 'nbDouane' => $this->nbDouane, 'nbCvo' => $this->nbCvo, 'nbLabel' => $this->nbLabel, 'nbOrganisme' => $this->nbOrganisme));
  		$this->form->setHash($hash);
  	
	  	if ($request->isMethod(sfWebRequest::POST)) {
	        $this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$object = $this->form->save();
				if ($isNew) {
	  				$pile = $this->getUser()->getAttribute('pile_noeud');
			  		$arborescence = ConfigurationProduit::getArborescence();
			  		foreach ($arborescence as $produit) {
			  			if (isset($pile[$produit])) {
			  				unset($pile[$produit]);
			  				if (count($pile) > 0) {
			  					$this->getUser()->setAttribute('pile_noeud', $pile);
			  					$nextHash = $this->getNextHash($object);
			  					$object = $object->getDocument()->get($nextHash);
			  					foreach ($arborescence as $p) {
			  						if (isset($pile[$p])) {
			  							$object = $configurationProduits->getOrAdd($hash);
			  							$correspondance = ConfigurationProduit::getCorrespondanceNoeuds();
			  							if (isset($correspondance[$p])) {
  											$object = $object->__get($correspondance[$p]);
			  								$this->redirect('configuration_produit_modification', array('noeud' => $object->getTypeNoeud(), 'hash' => str_replace('/', '-', $nextHash)));
			  							}
			  						}
			  					}
			  					
			  				}
		  					if ($cache = $this->getContext()->getViewCacheManager()) {
			    				$cache->remove('configuration_produit/index');
				    		}
							$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
		  					$this->redirect('configuration_produit');
			  			}
			  		}
			  	}
	    		if ($cache = $this->getContext()->getViewCacheManager()) {
    				$cache->remove('configuration_produit/index');
	    		}
				$this->getUser()->setFlash("notice", 'Le produit a été modifié avec success.');
			  	$this->redirect('configuration_produit');
			}
	    }
  	}

	

  	public function executeSuppression(sfWebRequest $request)
  	{
  		$this->forward404Unless($hash = $request->getParameter('hash', null));
  		$hash = str_replace('-', '/', $hash);
  		$configurationProduits = $this->getConfigurationProduit();
  		$object = $configurationProduits->getOrAdd($hash);
	  	while ($object->getParent()->count() == 1) {
	  		$object = $object->getParentNode();
	  	}
  		$doc = $object->getDocument();
  		$object->delete();
  		$doc->save();
	    if ($cache = $this->getContext()->getViewCacheManager()) {
    		$cache->remove('configuration_produit/index');
	    }
		$this->getUser()->setFlash("notice", 'Le produit a été supprimé avec success.');
		$this->redirect('configuration_produit');
  	}
  
  	protected function getNextHash($object) 
  	{
	  	$hash = $object->getHash();
	  	$nodes = ConfigurationProduit::getArborescence();
	  	$noeud = $object->getTypeNoeud();
	  	$nextNoeuds = false;
	  	foreach ($nodes as $node) {
	  		if ($nextNoeuds) {
	  			$hash = $hash.'/'.$node.'/'.ConfigurationProduit::DEFAULT_KEY;
	  		}
	  		if (preg_match('/^'.$noeud.'[a-z]?$/', $node)) {
	  			$nextNoeuds = true;
	  		}
	  	}
	  	return $hash;
  	}
	
	protected function getConfigurationProduit()
	{
		$interpro = $this->getInterpro()->_id;
		return ConfigurationProduitClient::getInstance()->getOrCreate($interpro);
	}
	
	protected function getInterpro()
	{
		return $this->getUser()->getCompte()->getGerantInterpro();
	}
  
}
