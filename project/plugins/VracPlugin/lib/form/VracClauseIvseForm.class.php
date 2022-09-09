<?php

class VracClauseIvseForm extends VracClauseForm
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

        $this->setWidget('clause_initiative_contractuelle_producteur', new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true, 'renderer_options' => array('formatter' => array('VracClauseIvseForm', 'radioClausesFormatter')))));
        $this->setValidator('clause_initiative_contractuelle_producteur', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))));

        $this->setWidget('annexe_file', new sfWidgetFormInputFile(array('label' => 'fichier PDF:')));
        $this->setValidator('annexe_file', new sfValidatorFile(array('required' => false, 'path' => sfConfig::get('sf_cache_dir'), 'mime_types' => array('application/pdf')), array('mime_types' => 'Format PDF obligatoire')));
    }

    public function getChoixOuiNon()
    {
    	return array('1' => 'Oui', '0' =>'Non mais le présent contrat a été négocié dans le respect de la liberté contractuelle du producteur, ce dernier ayant pu faire valoir ses propositions préalablement à la signature du contrat et n\'ayant pas souhaité effectuer une proposition de contrat.');
    }

    public function processValues($values) {
        if (array_key_exists('annexe_file', $values) && !$values['annexe_file']) {
            unset($values['annexe_file']);
        }

        return parent::processValues($values);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $file = $this->getValue('annexe_file');
        if ($file && !$file->isSaved()) {
            $file->save();
        }
        if ($file) {
            try {
                $this->getObject()->storeAnnexe($file->getSavedName());
            } catch (sfException $e) {
                throw new sfException($e);
            }
            unlink($file->getSavedName());
        }
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      if (is_null($this->getObject()->export)) {
          $this->setDefault('clause_initiative_contractuelle_producteur', 0);
      }
    }

    public static function radioClausesFormatter($widget, $inputs) {
        $result = '<ul>';
        foreach ($inputs as $k => $input) {
            $result .= '<li>' . $input ['input'] . '   ' . $input ['label'] . '</li>';
        }
        $result .= '</ul>';
        return $result;
    }
}
