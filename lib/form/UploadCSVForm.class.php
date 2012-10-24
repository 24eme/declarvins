<?php

class UploadCSVForm extends BaseForm {

    /**
     * 
     */
    public function configure() {
      $this->setWidget('file', new sfWidgetFormInputFile(array('label' => 'Fichier')));
      $this->setValidator('file', new ValidatorImportCsv(array('file_path' => sfConfig::get('sf_data_dir').'/upload')));
      $this->widgetSchema->setNameFormat('csv[%s]');
    }

}
