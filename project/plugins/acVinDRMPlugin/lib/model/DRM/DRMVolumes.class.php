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
}
