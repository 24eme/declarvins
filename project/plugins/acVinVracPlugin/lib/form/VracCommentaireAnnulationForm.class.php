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
	       'commentaire_annulation' => new sfWidgetFormTextArea()
		));
        $this->getWidget('commentaire_annulation')->setLabel("Commentaire d'annulation");
		$this->setValidators(array(
	       'commentaire_annulation' => new sfValidatorString(array('required' => true))
		));
        $this->widgetSchema->setNameFormat('commentaire_annulation[%s]');
	}

}
