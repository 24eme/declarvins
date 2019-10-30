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

        $this->setWidget('autres_conditions', new sfWidgetFormTextarea(
            [],
            ['placeholder' => 'Saisie libre des autres conditions convenues entre les parties']
        ));
        $this->setValidator('autres_conditions', new sfValidatorString(['required'=>false]));

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

        if (isset($values['autres_conditions'])) {
            $vrac->add('autres_conditions', $values['autres_conditions']);
        }

        $vrac->update();
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject(); 
      $complements = explode(',', $this->getObject()->clauses_complementaires);
      if (!in_array('emission_facture', $complements)) {
          $this->setDefault('emission_facture', 0);
      }
      if (!in_array('agreage_vins', $complements)) {
          $this->setDefault('agreage_vins', 0);
      }
      if (!in_array('transfert_propriete', $complements)) {
          $this->setDefault('transfert_propriete', 0);
      }
    }
}
