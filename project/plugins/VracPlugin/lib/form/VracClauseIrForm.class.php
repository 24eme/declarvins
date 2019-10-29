<?php

class VracClauseIrForm extends VracClauseForm
{
    public function doUpdateObject($values) {
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
