<?php
/**
 * Model for ConfigurationVrac
 *
 */

class ConfigurationVrac extends BaseConfigurationVrac {
	
	const FAMILLE_VENDEUR = 'Producteur';
	const FAMILLE_ACHETEUR = 'Negociant';
	const FAMILLE_MANDATAIRE = 'Courtier';
	
	/*
     * @todo 
     */
    public function getVendeurs() {
    	return EtablissementAllView::getInstance()->findByInterproAndFamille($this->getKey(), self::FAMILLE_VENDEUR)->rows;
    }
    /*
     * @todo 
     */
    public function getAcheteurs() {
    	return EtablissementAllView::getInstance()->findByInterproAndFamille($this->getKey(), self::FAMILLE_ACHETEUR)->rows;
    }
    /*
     * @todo 
     */
    public function getMandataires() {
    	return EtablissementAllView::getInstance()->findByInterproAndFamille($this->getKey(), self::FAMILLE_MANDATAIRE)->rows;
    }
    /*
     * @todo 
     */
    public function getMandatants() {
    	return array();
    }
    /*
     * @todo 
     */
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

    public function getLibelles($collection, $node) {
        $libelles = array(); 
        foreach($collection as $key) {
        	if ($this->{$node}->exist($key))
            	$libelles[$key] = $this->{$node}->get($key);
        }
        return $libelles;
    }

}