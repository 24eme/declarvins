<?php
class DAIDSCampagneForm extends CampagneForm
{
    protected $etablissement = null;

    public function __construct($etablissement, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        $this->etablissement = $etablissement;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

	public function configure() 
	{    
        parent::configure();
        $this->validatorSchema->setPostValidator(new DAIDSCampagneValidator(array('etablissement' => $this->etablissement)));
    }
}