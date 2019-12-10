<?php
class DRMESDetailCrdForm extends BaseForm {
    
    protected $detail;
    
    public function __construct($detail, $options = array(), $CSRFSecret = null)
    {
        $this->detail = $detail;
        parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
    }
    
    public function getDefaultValues() {
        $defaults = array(
            'volume' => $this->detail->volume,
            'mois' => $this->detail->mois,
            'annee' => $this->detail->annee
        );
        return  $defaults;
    }
	
	public function configure() {
		$this->setWidget('volume', new sfWidgetFormInputFloat(array('float_format' => "%01.05f")));
		$this->setValidator('volume', new sfValidatorNumber(array('required' => false)));
		
		$this->setWidget('mois', new sfWidgetFormChoice(array('choices' => $this->getMois())));
		$this->setValidator('mois', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMois()))));
		
		$this->setWidget('annee', new sfWidgetFormChoice(array('choices' => $this->getAnnees())));
		$this->setValidator('annee', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAnnees()))));
		
		$this->widgetSchema->setNameFormat('[%s]');
	}
	
	
	public function getMois() {
		$mois = array(null => null);
		for($i = 1; $i < 13; $i++) {
			$mois["".sprintf("%02d", $i)] = sprintf("%02d", $i);
		}
		return $mois;
	}
	
	public function getAnnees() {
		$annees = array(null => null);
		for($i = $this->detail->getDocument()->getAnnee(); $i >= ($this->detail->getDocument()->getAnnee() - 10); $i--) {
			$annees[$i] = $i;
		}
		return $annees;
	}
}