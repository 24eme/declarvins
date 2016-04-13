<?php
class DRMVolumes
 {
  const DROIT_CVO = 'cvo';
  const DROIT_DOUANE = 'douane';

  static $entrees_nettes = array('entrees/achat', 'entrees/recolte');
  public static function getEntreesNettes($merge = array()) {
    return array_merge(self::$entrees_nettes, $merge);
  }
  static $entrees_reciproque = array('entrees/repli', 'entrees/declassement', 'entrees/mouvement', 'entrees/crd');
  public static function getEntreesReciproque($merge = array()) {
    return array_merge(self::$entrees_reciproque, $merge);
  }
  static $sorties_nettes = array('sorties/vrac', 'sorties/export', 'sorties/factures', 'sorties/crd', 'sorties/consommation', 'sorties/pertes');
  public static function getSortiesNettes($merge = array()) {
    return array_merge(self::$sorties_nettes, $merge);
  }
  static $sorties_reciproque = array('sorties/declassement', 'sorties/repli', 'sorties/mouvement', 'sorties/distillation', 'sorties/lies');
  public static function getSortiesReciproque($merge = array()) {
    return array_merge(self::$sorties_reciproque, $merge);
  }
  
  static $entrees_suspendus = array('entrees/achat', 'entrees/recolte', 'entrees/repli', 'entrees/declassement', 'entrees/mouvement', 'entrees/crd', 'entrees/manipulation', 'entrees/vci', 'entrees/embouteillage', 'entrees/travail', 'entrees/distillation', 'entrees/excedent');
  public static function getEntreesSuspendus($merge = array()) {
  	return array_merge(self::$entrees_suspendus, $merge);
  }
  static $sorties_suspendus = array('sorties/vrac', 'sorties/export', 'sorties/factures', 'sorties/crd', 'sorties/crd_acquittes', 'sorties/consommation', 'sorties/pertes', 'sorties/declassement', 'sorties/repli', 'sorties/mouvement', 'sorties/distillation', 'sorties/lies', 'sorties/mutage', 'sorties/vci', 'sorties/embouteillage', 'sorties/travail', 'sorties/autres', 'sorties/autres_interne');
  public static function getSortiesSuspendus($merge = array()) {
  	return array_merge(self::$sorties_suspendus, $merge);
  }
  static $entrees_acquittes = array('entrees/acq_achat', 'entrees/acq_autres');
  public static function getEntreesAcquittes($merge = array()) {
  	return array_merge(self::$entrees_acquittes, $merge);
  }
  static $sorties_acquittes = array('sorties/acq_crd', 'sorties/acq_replacement', 'sorties/acq_autres');
  public static function getSortiesAcquittes($merge = array()) {
  	return array_merge(self::$sorties_acquittes, $merge);
  }
}
