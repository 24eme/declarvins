<?php
class StatistiqueEtablissementForm extends BaseForm
{
	protected $interpro;
	protected $etablissement_options;
	
	public function __construct($interpro, $etablissementOptions = array(), $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro = $interpro;
  		$this->etablissement_options = $etablissementOptions;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}
  	
	public function configure() 
	{
		$options = array_merge(array('interpro_id' => $this->interpro), $this->getOptions());
		if ($this->etablissement_options) {
			$options = array_merge($options, $this->etablissement_options);
		}
        $this->setWidget('identifiant', new WidgetEtablissement($options));
        $this->widgetSchema->setLabel('identifiant', 'Etablissement :');
        $this->setValidator('identifiant', new ValidatorEtablissement(array('required' => false)));
        
    }
}