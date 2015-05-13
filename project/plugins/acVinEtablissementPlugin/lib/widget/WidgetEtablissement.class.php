<?php 

class WidgetEtablissement extends sfWidgetFormChoice
{
    protected $identifiant = null;

    public function __construct($options = array(), $attributes = array())
    {
        parent::__construct($options, $attributes);

        $this->setAttribute('data-ajax', $this->getUrlAutocomplete());
        $this->setOption('choices', $this->getChoices());
    }

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->setOption('choices', array());
        $this->addOption('familles', array());
        $this->addOption('sous_familles', array());
        $this->addOption('only_actif', 0);
        $this->addOption('admin', 0);
        $this->addRequiredOption('interpro_id', null);
        $this->setAttribute('class', 'autocomplete'); 
    }

    public function setOption($name, $value) {
        parent::setOption($name, $value);

        if($name == 'familles') {
            $this->setAttribute('data-ajax', $this->getUrlAutocomplete());
        }

        return $this;
    }

    public function getUrlAutocomplete() {
        $admin = $this->getOption('admin');
    	$familles = $this->getOption('familles');
        $sous_familles = $this->getOption('sous_familles');
		$interpro_id = $this->getOption('interpro_id');
		$only_actif = $this->getOption('only_actif');
        if (!is_array($familles) && $familles) {
            $familles = array($familles);
        }

        if (is_array($familles) && count($familles) > 0) {
            
            return sfContext::getInstance()->getRouting()->generate('etablissement_autocomplete_byfamilles', array('interpro_id' => $interpro_id, 'familles' => implode("|",$familles), 'only_actif' => $only_actif));
        }

        if (is_array($sous_familles) && count($sous_familles) > 0) {
            return sfContext::getInstance()->getRouting()->generate('etablissement_autocomplete_bysousfamilles', array('interpro_id' => $interpro_id, 'familles' => implode("|",array_keys($sous_familles)), 'sous_familles' => implode("|",$sous_familles), 'only_actif' => $only_actif));
        }
        if ($admin) {
        	return sfContext::getInstance()->getRouting()->generate('etablissement_autocomplete_all_admin', array('interpro_id' => $interpro_id, 'only_actif' => $only_actif));
        }        

        return sfContext::getInstance()->getRouting()->generate('etablissement_autocomplete_all', array('interpro_id' => $interpro_id, 'only_actif' => $only_actif));
    }

    public function getChoices() {
        if(!$this->identifiant) {
            return array();
        }
        $etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        if (!$etablissement) {
            return array();
        }

        $choices = array();
		$choices[$this->identifiant] = $etablissement->makeLibelle();

        return $choices; 
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $this->identifiant = $value;
        return parent::render($name, $value, $attributes, $errors);
    }

}