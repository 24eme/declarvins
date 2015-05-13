<?php
class AlerteFiltersForm extends sfForm
{
	protected $interpro_id;
	
  	public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro_id = $interpro_id;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}
	public function configure() {    
        $this->setWidgets(array(
            'etablissement'   => new WidgetEtablissement(array('interpro_id' => $this->interpro_id)),
            'campagne'   => new WidgetFormCampagne()
        ));
		
		$this->widgetSchema->setLabels(array(
			'etablissement' => 'Etablissement: ',
			'campagne' => 'Campagne: : '
		));
        $this->setValidators(array(
            'etablissement'   => new ValidatorEtablissement(array('required' => false)),
        	'campagne'   => new ValidatorCampagne()
        ));
        
        $this->widgetSchema->setNameFormat('alerte_filters[%s]');
    }
    
    public function getFormattedValuesForUrlParameters()
    {
    	$values = $this->getValues();
    	$parameters = array();
    	if (isset($values['etablissement']) && $values['etablissement']) {
    		$parameters['etablissement'] = $values['etablissement'];
    	}
    	if (isset($values['campagne']) && $values['campagne']) {
    		$parameters['campagne'] = $values['campagne'];
    	}
    	return $parameters;
    }
}