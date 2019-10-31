<?php

class VracClauseIrForm extends VracClauseForm
{
    public function configure() {
        parent::configure();

        $this->setWidget('clause_resiliation_cas', new sfWidgetFormInputText());
        $this->getWidget('clause_resiliation_cas')->setLabel('Cas de résiliation:');
        $this->setValidator('clause_resiliation_cas', new sfValidatorString(array('required' => false)));
        
        $this->setWidget('clause_resiliation_preavis', new sfWidgetFormInputText());
        $this->getWidget('clause_resiliation_preavis')->setLabel('Délai de préavis:');
        $this->setValidator('clause_resiliation_preavis', new sfValidatorString(array('required' => false)));
        
        $this->setWidget('clause_resiliation_indemnite', new sfWidgetFormInputText());
        $this->getWidget('clause_resiliation_indemnite')->setLabel('Indemnité:');
        $this->setValidator('clause_resiliation_indemnite', new sfValidatorString(array('required' => false)));
    }
    
     protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->type_transaction != 'vrac') {
            $clauses = explode(',', $this->getObject()->clauses_complementaires);
            $unset = null;
            foreach ($clauses as $k => $clause) {
                if ($clause == 'transfert_propriete') {
                    $unset = $k;
                }
            }
            if (!is_null($unset)) {
                unset($clauses[$unset]);
            }
            $this->getObject()->clauses_complementaires = implode(',', $clauses);
        }
    }
}
