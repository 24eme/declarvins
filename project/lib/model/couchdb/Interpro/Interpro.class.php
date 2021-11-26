<?php
class Interpro extends BaseInterpro {
	const INTERPRO_KEY = 'INTERPRO-';
	const INTER_RHONE_ID = 'IR';
	const CIVP_ID = 'CIVP';
	const INTERVINS_SUD_EST_ID = 'IVSE';
    const GRC_FILENAME = 'etablissements.csv';
  public function __toString() {
    return $this->nom;
  }
  public function getKey() {
    return $this->_id;
  }
  public function getEtablissementsArrayFromGrcFile() {
      $file = $this->getAttachmentUri(self::GRC_FILENAME);
      $content = file_get_contents($file);
      $arr = array();
      if ($content) {
          $lines = explode(PHP_EOL, $content);
          foreach ($lines as $line) {
              $lineTrimed = trim($line);
              if (!$lineTrimed||$lineTrimed[0] == '#') {
                  continue;
              }
              $lineArr = str_getcsv($lineTrimed, ';');
              $arr[$lineArr[0]] = $lineArr;
          }
      }
      return $arr;
  }
}
