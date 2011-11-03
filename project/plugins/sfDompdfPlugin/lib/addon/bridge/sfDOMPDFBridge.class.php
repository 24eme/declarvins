<?php
require_once(sfConfig::get('sf_plugins_dir').'/sfDompdfPlugin/lib/vendor/dompdf/dompdf_config.inc.php');

class sfDOMPDFBridge
{
  public static function autoload($class)
  {
    require_once(sfConfig::get('sf_plugins_dir').'/sfDompdfPlugin/lib/vendor/dompdf/dompdf_config.inc.php');
    DOMPDF_autoload($class);
    return true;
  }
}
