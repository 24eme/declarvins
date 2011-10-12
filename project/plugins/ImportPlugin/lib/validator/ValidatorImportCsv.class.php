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

  protected function doClean($values)
  {
    $csvValidated = new CsvValidatedFile(parent::doClean($values));
    
    $errorSchema = new sfValidatorErrorSchema($this);

    //Conversion UTF8
    $fc = htmlentities(utf8_decode(file_get_contents($csvValidated->getTempName())),ENT_NOQUOTES);
    $md5 = md5($fc);
    $file = $this->getOption('file_path').'/'.$md5;
    $csvValidated->setMd5($md5);
    $handle=fopen($file, "w+");
    fwrite($handle, $fc);
    fseek($handle, 0);
    fclose($handle);

    try {
      $csv = new CsvFile($file);
    }catch(Exception $e) {
      $csv = null;
      $errorSchema->addError(new sfValidatorError($this, $e->getMessage()));
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }

    $csvValidated->setCsv($csv);
    return $csvValidated;
  }

}