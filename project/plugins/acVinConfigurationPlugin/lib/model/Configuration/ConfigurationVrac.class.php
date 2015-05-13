<?php
/**
 * Model for ConfigurationVrac
 *
 */

class ConfigurationVrac extends BaseConfigurationVrac {
	
	const FAMILLE_VENDEUR = 'Producteur';
	const FAMILLE_ACHETEUR = 'Negociant';
	const FAMILLE_MANDATAIRE = 'Courtier';
	
	const REPARTITION_CVO_ACHETEUR = 0.5;
	
	const CAS_PARTICULIER_DEFAULT_KEY = 'aucune';
	const CONDITION_PAIEMENT_ECHEANCIER = 'echeancier_paiement';
	
    public function getVendeurs() {
    	return EtablissementAllView::getInstance()->findByZoneAndFamille($this->getInterpro()->zone, self::FAMILLE_VENDEUR)->rows;
    }
    
    public function getAcheteurs() {
    	return EtablissementAllView::getInstance()->findByZoneAndFamille($this->getInterpro()->zone, self::FAMILLE_ACHETEUR)->rows;
    }
    
    public function getMandataires() {
    	return EtablissementAllView::getInstance()->findByZoneAndFamille($this->getInterpro()->zone, self::FAMILLE_MANDATAIRE)->rows;
    }
    /*
     * @todo 
     */
    public function getMandatants() {
    	return array();
    }
    
    public function getConfig() {
    	return $this->getDocument();
    }

    public function formatLabelsLibelle($labels, $format = "%la%", $separator = ", ") {
      $libelles = $this->getLibelles($labels, 'labels');
      return str_replace("%la%", implode($separator, $libelles), $format);
    }

    public function formatMentionsLibelle($mentions, $format = "%me%", $separator = ", ") {
      $libelles = $this->getLibelles($mentions, 'mentions');
      return str_replace("%me%", implode($separator, $libelles), $format);
    }

    public function formatConditionsPaiementLibelle($conditions, $format = "%co%", $separator = ", ") {
      $libelles = $this->getLibelles($conditions, 'conditions_paiement');
      return str_replace("%co%", implode($separator, $libelles), $format);
    }

    public function formatTypesTransactionLibelle($types, $format = "%tr%", $separator = ", ") {
      $libelles = $this->getLibelles($types, 'types_transaction');
      return str_replace("%tr%", implode($separator, $libelles), $format);
    }

    public function formatTypesPrixLibelle($types, $format = "%pr%", $separator = ", ") {
      $libelles = $this->getLibelles($types, 'types_prix');
      return str_replace("%pr%", implode($separator, $libelles), $format);
    }

    public function formatNaturesDocumentLibelle($natures, $format = "%na%", $separator = ", ") {
      $libelles = $this->getLibelles($natures, 'natures_document');
      return str_replace("%na%", implode($separator, $libelles), $format);
    }

    public function formatDelaisPaiementLibelle($delais, $format = "%de%", $separator = ", ") {
      $libelles = $this->getLibelles($delais, 'delais_paiement');
      return str_replace("%de%", implode($separator, $libelles), $format);
    }

    public function formatCommentairesLotLibelle($commentaires, $format = "%com%", $separator = ", ") {
      $libelles = $this->getLibelles($commentaires, 'commentaires_lot');
      return str_replace("%com%", implode($separator, $libelles), $format);
    }

    public function formatCasParticulierLibelle($cas, $format = "%ca%", $separator = ", ") {
      $libelles = $this->getLibelles($cas, 'cas_particulier');
      return str_replace("%ca%", implode($separator, $libelles), $format);
    }
    
    public function getKeyAndLibelle($node, $libelle) {
    	$result = array();
    	foreach ($this->{$node} as $k => $v) {
    		if (preg_match('/'.$this->cleanForCompare($v).'/i', $this->cleanForCompare($libelle)) || preg_match('/'.$this->cleanForCompare($libelle).'/i', $this->cleanForCompare($v))) {
    			$result[] = array('key' => $k, 'libelle' => $v);
    		}
    	}
    	return $result;
    }
    
    private function cleanForCompare($str = null)
    {
    	if (!$str) {
    		return null;
    	}
    	return strtolower(KeyInflector::slugify(trim($str)));
    }

    public function getLibelles($collection, $node) {
        $libelles = array(); 
        foreach($collection as $key) {
        	if ($this->{$node}->exist($key))
            	$libelles[$key] = $this->{$node}->get($key);
        }
        return $libelles;
    }
    
    public function getInterproId()
    {
    	return $this->getKey();
    }
    
    public function getInterpro()
    {
    	return InterproClient::getInstance()->find($this->getKey());
    }

    public function formatVracProduitsByZones($zones = array(), $date = null) {
        if (!$zones) {
                $zones = array($this->getInterpro()->zone => ConfigurationZoneClient::getInstance()->find($this->getInterpro()->zone));
        }
        return $this->getConfig()->getFormattedProduits(null, $zones, true, "%g% %a% %m% %l% %co% %ce%", false,  $date);
    }
    
}
