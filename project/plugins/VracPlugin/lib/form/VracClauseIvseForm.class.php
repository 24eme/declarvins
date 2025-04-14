<?php

class VracClauseIvseForm extends VracClauseForm
{
    public function configure() {
        parent::configure();
        unset($this['clause_initiative_contractuelle_producteur']);

        $this->setWidget('clause_resiliation_cas', new sfWidgetFormInputText());
        $this->getWidget('clause_resiliation_cas')->setLabel('Cas de résiliation:');
        $this->setValidator('clause_resiliation_cas', new sfValidatorString(array('required' => false)));

        $this->setWidget('clause_resiliation_preavis', new sfWidgetFormInputText());
        $this->getWidget('clause_resiliation_preavis')->setLabel('Délai de préavis:');
        $this->setValidator('clause_resiliation_preavis', new sfValidatorString(array('required' => false)));

        $this->setWidget('clause_resiliation_indemnite', new sfWidgetFormInputText());
        $this->getWidget('clause_resiliation_indemnite')->setLabel('Indemnité:');
        $this->setValidator('clause_resiliation_indemnite', new sfValidatorString(array('required' => false)));

        $this->setWidget('annexe_file', new sfWidgetFormInputFile(array('label' => 'fichier PDF:')));
        $this->setValidator('annexe_file', new sfValidatorFile(array('required' => false, 'path' => sfConfig::get('sf_cache_dir'), 'mime_types' => array('application/pdf')), array('mime_types' => 'Format PDF obligatoire')));
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
}
