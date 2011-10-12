<?php

class ValidatorImportCsv extends sfValidatorFile
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('file_path');
    $this->addMessage('invalid_file', "Le fichier fourni ne peut être lu");
    $this->addMessage('invalid_csv_file', "Le fichier fourni n'est pas un CSV");
    $options['mime_types'] = array('text/plain');
    $options['required'] = true;

    return parent::configure($options, $messages);

  }

  protected function doClean($value)
  { 

    $csvValidated = new CsvValidatedFile($value['name'], 'text/csv', $value['tmp_name'], $value['size'], $this->getOption('file_path'));
    
    $errorSchema = new sfValidatorErrorSchema($this);
    
    $csvValidated->save();

    try {
      $csv = new CsvFile($csvValidated->getSavedName());
    }catch(Exception $e) {
      $csv = null;
      $errorSchema->addError(new sfValidatorError($this, $e->getMessage()));
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }

    $csvValidated->setCsv($csv);
    return $csvValidated;
  }

}