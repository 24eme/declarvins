<?php

class VracClauseForm extends VracForm
{
    protected $text = 'En cochant la case ci-contre, les Parties renoncent expressément au bénéfice de cette clause';

    public function configure()
    {
        $this->setWidget('emission_facture', new sfWidgetFormInputCheckbox());
        $this->setValidator('emission_facture', new sfValidatorBoolean(
            ['required' => false] 
        ));

        $this->setWidget('agreage_vins', new sfWidgetFormInputCheckbox());
        $this->setValidator('agreage_vins', new sfValidatorBoolean(
            ['required' => false]
        ));

        $this->setWidget('transfert_propriete', new sfWidgetFormInputCheckbox());
        $this->setValidator('transfert_propriete', new sfValidatorBoolean(
            ['required' => false]
        ));

        $this->widgetSchema->setLabels([
            'emission_facture' => $this->text,
            'agreage_vins' => $this->text,
            'transfert_propriete' => $this->text
        ]);
        $this->widgetSchema->setNameFormat('vrac_clause[%s]');
    }

    public function doUpdateObject($values)
    {
        $clauses_complementaires = $this->getConfiguration()->clauses_complementaires;
        $vrac = $this->getObject();
        $vrac->remove('clauses_complementaires');

        $cc = [];
        foreach ($clauses_complementaires as $key => $value) {
            if ($values[$key] === false) {
                $cc[] = $key;
            }
        }

        $vrac->add('clauses_complementaires', implode(',', $cc));
        $vrac->update();
    }
}
