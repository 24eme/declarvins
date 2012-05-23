<?php

class UploadCSVForm extends BaseForm {

    /**
     * 
     */
    public function configure() {
      $this->setWidgets(array(
			      'file'    => new sfWidgetFormInputFile(array('label' => 'Fichier'))
			      ));
      $this->widgetSchema->setNameFormat('csv[%s]');
      
      $this->setValidators(array(
				 'file'    => new ValidatorImportCsv(array('file_path' => sfConfig::get('sf_data_dir').'/upload'))
				 ));
    }

}
