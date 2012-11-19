<?php
class DAIDSDetailStocksMoyenForm extends acCouchdbObjectForm 
{
	public function configure() 
    {
    	$this->vinifie = new DAIDSDetailStocksMoyenDetailForm($this->getObject()->vinifie);
        $this->embedForm('vinifie', $this->vinifie);
        
        $this->non_vinifie = new DAIDSDetailStocksMoyenDetailForm($this->getObject()->non_vinifie);
        $this->embedForm('non_vinifie', $this->non_vinifie);
        
        $this->conditionne = new DAIDSDetailStocksMoyenDetailForm($this->getObject()->conditionne);
        $this->embedForm('conditionne', $this->conditionne);
    		    		
        $this->widgetSchema->setNameFormat('stocks_moyen[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}