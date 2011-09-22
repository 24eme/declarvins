<?php
class CompteTiersAjoutForm extends CompteForm {

    public function configure() {
        parent::configure();
		$this->setWidget('nom', new sfWidgetFormInputText());
		$this->setWidget('prenom', new sfWidgetFormInputText());
		$this->setWidget('fonction', new sfWidgetFormInputText());
		$this->setWidget('telephone', new sfWidgetFormInputText());
		$this->setWidget('fax', new sfWidgetFormInputText());
		
		$this->getWidget('nom')->setLabel('Nom*: ');
		$this->getWidget('prenom')->setLabel('Prénom*: ');
		$this->getWidget('fonction')->setLabel('Fonction*: ');
		$this->getWidget('telephone')->setLabel('Téléphone*: ');
		$this->getWidget('fax')->setLabel('Fax: ');
		
		$this->setValidator('nom', new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')));
		$this->setValidator('prenom', new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')));
		$this->setValidator('fonction', new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')));
		$this->setValidator('telephone', new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')));
		$this->setValidator('fax', new sfValidatorString(array('required' => false)));
        
        $this->widgetSchema->setNameFormat('comptetiers[%s]');

    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->isNew())
        {
            $this->getObject()->set('_id', 'COMPTE-'.$this->getObject()->getLogin());
            $this->getObject()->setPasswordSSHA($values['mdp1']);
        }
    }

}