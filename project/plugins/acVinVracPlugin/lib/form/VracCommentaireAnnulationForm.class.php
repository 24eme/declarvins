<?php
class VracCommentaireAnnulationForm extends acCouchdbObjectForm
{
	protected $commentaire;

	public function __construct(acCouchdbJson $object, $commentaire, $options = array(), $CSRFSecret = null)
	{
      	$this->commentaire = $commentaire;
        parent::__construct($object, $options, $CSRFSecret);
    }
	public function configure()
	{
		$this->setWidgets(array(
	       'commentaire_refus' => new sfWidgetFormTextArea()
		));
        $this->getWidget('commentaire_refus')->setLabel("Commentaire de refus");
		$this->setValidators(array(
	       'commentaire_refus' => new sfValidatorString(array('required' => true))
		));
        $this->widgetSchema->setNameFormat('commentaire_refus[%s]');
	}

}
