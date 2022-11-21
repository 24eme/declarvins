<?php

class VracClauseForm extends VracForm
{
    protected $text = 'En cochant la case ci-contre, les Parties renoncent expressément au bénéfice de cette clause';

    const COMPLEMENTS_TITLE = 'Informations complémentaires';

    public function configure()
    {
        $clauses_complementaires = $this->getConfiguration()->clauses_complementaires;
        foreach ($clauses_complementaires as $key => $value) {
            $this->setWidget($key, new sfWidgetFormInputCheckbox());
            $this->setValidator($key, new sfValidatorBoolean(
                ['required' => false]
                ));
        }

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
        if ($this->getConfiguration()->isContratPluriannuelActif() && $this->getObject()->isPluriannuel()) {
            $this->configurePluriannuel();
        }

        $this->setWidget('clause_initiative_contractuelle_producteur', new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNonClauseInitiativeContractuelleProducteur(),'expanded' => true, 'renderer_options' => array('formatter' => array('VracClauseForm', 'radioClausesFormatter')))));
        $this->setValidator('clause_initiative_contractuelle_producteur', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNonClauseInitiativeContractuelleProducteur()))));

        $this->widgetSchema->setNameFormat('vrac_clause[%s]');
    }

    public function getChoixOuiNonClauseInitiativeContractuelleProducteur()
    {
    	return array('1' => 'Oui', '0' =>'Non mais le présent contrat a été négocié dans le respect de la liberté contractuelle du producteur, ce dernier ayant pu faire valoir ses propositions préalablement à la signature du contrat et n\'ayant pas souhaité effectuer une proposition de contrat.');
    }

    public static function radioClausesFormatter($widget, $inputs) {
        $result = '<ul>';
        foreach ($inputs as $k => $input) {
            $result .= '<li>' . $input ['input'] . '   ' . $input ['label'] . '</li>';
        }
        $result .= '</ul>';
        return $result;
    }

    public function configurePluriannuel() {
        if (isset($this['agreage_vins'])) {
            unset($this['agreage_vins']);
        }
    }

    protected function doUpdateObject($values)
    {
        parent::doUpdateObject($values);
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
      $clauses_complementaires = $this->getConfiguration()->clauses_complementaires;
      $complements = explode(',', $this->getObject()->clauses_complementaires);
        foreach ($clauses_complementaires as $key => $value) {
            if (!in_array($key, $complements)) {
              $this->setDefault($key, 0);
          }
        }
        if (is_null($this->getObject()->clause_initiative_contractuelle_producteur)) {
            $this->setDefault('clause_initiative_contractuelle_producteur', 0);
        }
    }

    public function getComplementsTitle() {
        return self::COMPLEMENTS_TITLE;
    }
}
