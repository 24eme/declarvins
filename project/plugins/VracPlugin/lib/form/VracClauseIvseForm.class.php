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

        $this->setWidget('annexe_precontractuelle', new sfWidgetFormInputFile(array('label' => 'Document précontractuel : <a href="" class="msg_aide" data-msg="help_popup_vrac_annexe_precontractuelle" title="Message aide"></a>')));
        $this->setValidator('annexe_precontractuelle', new sfValidatorFile(array('required' => false, 'path' => sfConfig::get('sf_cache_dir'), 'mime_types' => array('application/pdf')), array('mime_types' => 'Format PDF obligatoire')));

        $this->setWidget('annexe_autre', new sfWidgetFormInputFile(array('label' => 'Autres annexes :')));
        $this->setValidator('annexe_autre', new sfValidatorFile(array('required' => false, 'path' => sfConfig::get('sf_cache_dir'), 'mime_types' => array('application/pdf')), array('mime_types' => 'Format PDF obligatoire')));
    }

    public function processValues($values) {
        if (array_key_exists('annexe_autre', $values) && !$values['annexe_autre']) {
            unset($values['annexe_autre']);
        }
        if (array_key_exists('annexe_precontractuelle', $values) && !$values['annexe_precontractuelle']) {
            unset($values['annexe_precontractuelle']);
        }

        return parent::processValues($values);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach (['annexe_autre','annexe_precontractuelle'] as $annexe) {
            $file = $this->getValue($annexe);
            if ($file && !$file->isSaved()) {
                $file->save();
            }
            if ($file) {
                try {
                    $this->getObject()->storeAnnexe($file->getSavedName(), $annexe);
                } catch (sfException $e) {
                    throw new sfException($e);
                }
                unlink($file->getSavedName());
            }
        }
    }
}
