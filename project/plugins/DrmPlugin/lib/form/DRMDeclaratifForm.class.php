<?php

class DRMDeclaratifForm extends BaseForm {
	protected $_label_choices;

    public function configure() {
         $this->setWidgets(array(
            'apurement' => new sfWidgetFormChoice(array(
         												'expanded' => true,
            											'choices' => array(
         																0 => "Pas de défaut d'apurement", 
         																1 => "Défaut d'apurement à déclarer (Joindre relevé de non apurement et copie du DAA)"
         															),
         												//'renderer_options' => array('formatter' => array($this, 'formatter'))
         											)),
         	'daa_debut' => new sfWidgetFormInput(),
         	'daa_fin' => new sfWidgetFormInput(),
         	'dsa_debut' => new sfWidgetFormInput(),
         	'dsa_fin' => new sfWidgetFormInput(),
         	'adhesion_emcs_gamma' => new sfWidgetFormInputCheckbox(),
         	'caution' => new sfWidgetFormChoice(array(
         												'expanded' => true,
            											'choices' => array(
         																0 => "Oui", 
         																1 => "Dispense"
         															),
         												//'renderer_options' => array('formatter' => array($this, 'formatter'))
         											)),
         	'organisme' => new sfWidgetFormInput(),
         	'moyen_paiement' => new sfWidgetFormChoice(array(
         												'expanded' => true,
            											'choices' => array(
         																'Numéraire' => "Numéraire", 
         																'Chèque' => "Chèque",
         																'Virement' => "Virement"
         															),
         												//'renderer_options' => array('formatter' => array($this, 'formatter'))
         											)),
        ));
        
        $this->widgetSchema->setLabels(array(
        		'daa_debut' => 'du',
        		'daa_fin' => 'au',
        		'dsa_debut' => 'du ',
        		'dsa_fin' => 'au',
        		'adhesion_emcs_gamma' => 'Adhésion à EMCS/GAMMA (n° non nécessaires)',
        		'moyen_paiement' => 'Paiement droit circulation',
        ));
        $this->setValidators(array(
            'apurement' => new sfValidatorChoice(array('required' => true, 'choices' => array(0, 1))),
        	'daa_debut' => new sfValidatorString(array('required' => false)),
         	'daa_fin' => new sfValidatorString(array('required' => false)),
         	'dsa_debut' => new sfValidatorString(array('required' => false)),
         	'dsa_fin' => new sfValidatorString(array('required' => false)),
        	'adhesion_emcs_gamma' => new sfValidatorBoolean(array('required' => false)),
            'caution' => new sfValidatorChoice(array('required' => true, 'choices' => array(0, 1))),
        	'organisme' => new sfValidatorString(array('required' => false)),
        	'moyen_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array('Numéraire', 'Chèque', 'Virement'))),
        ));
        $this->widgetSchema->setNameFormat('drm_declaratif[%s]');
    }
	/*public function formatter($widget, $inputs)
	{
	    $rows = array();
	    foreach ($inputs as $input)
	    {
	      $rows[] = $widget->renderContentTag('div', $input['label'].$this->getOption('label_separator').$input['input'], array('class' => 'ligne_form'));
	    }
	
	    return !$rows ? '' : implode($widget->getOption('separator'), $rows);
  	}*/
}