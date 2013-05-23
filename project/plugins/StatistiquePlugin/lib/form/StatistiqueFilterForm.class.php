<?php
class StatistiqueFilterForm extends BaseForm
{
	public function configure() 
	{
	        $this->setWidgets(array(
	            'query' => new sfWidgetFormInputText(),
	        ));
	        $this->widgetSchema->setLabels(array(
	            'query'  => 'Recherche : ',
	        ));
	        $this->setValidators(array(
	            'query' => new sfValidatorString(array('required' => false)),
	        ));
        
        $this->widgetSchema->setNameFormat('statistique_filter[%s]');
    }
}