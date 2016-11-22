<?php
class EtablissementEdi 
{
	protected $etablissement;

    public function __construct($etablissement = null) {
    	$this->etablissement = $etablissement;
    }
    
    public function getXmlFormat($ea = null, $context = null) {
    	if (!$this->etablissement && !$ea) {
    		throw new sfException('Export impossible sans l\'objet ni la valeur.');
    	}
    	$val = ($ea)? $ea : $this->etablissement->no_accises;
    	return $this->getPartial('seed', array('ea' => $val), $context);
    }

    protected static function getPartial($partial, $vars = null, $context = null)
    {
    	$sfContext = ($context)? $context : sfContext::getInstance();
    	return $sfContext->getController()->getAction('edi_export', 'main')->getPartial('edi_export/' . $partial, $vars);
    }

}

