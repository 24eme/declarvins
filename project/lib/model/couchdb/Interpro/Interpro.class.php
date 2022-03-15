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
      if($this->identifiant == self::INTERVINS_SUD_EST_ID) {
          return $this->getEtablissementsDelegues($this->identifiant);
      }
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
  private function getEtablissementsDelegues($interpro) {
      $etablissements = array_merge(
          InterproClient::getInstance()->retrieveById(self::INTER_RHONE_ID)->getEtablissementsArrayFromGrcFile(),
          InterproClient::getInstance()->retrieveById(self::CIVP_ID)->getEtablissementsArrayFromGrcFile()
      );
      foreach($etablissements as $k => $etablissement) {
          $zones = explode('|', $etablissement[EtablissementCsv::COL_ZONES]);
          $inZone = false;
      	  foreach ($zones as $zone) {
      		if (strtoupper(trim($zone)) == $interpro) {
                $inZone = true;
                break;
            }
      	  }
          if (!$inZone) {
              unset($etablissements[$k]);
          }
      }
      return $etablissements;
  }
}
