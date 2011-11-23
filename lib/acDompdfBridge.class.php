<?php
/* This file is part of the acDompdfPlugin package.
 * (c) Actualys
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * acDompdfBridge allows you to handle the dompdf autoload
 *
 * @package    acDompdfPlugin
 * @subpackage lib
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @version    0.1
 */
class acDompdfBridge
{

  /**
   * Handles the dompdf autoload for the given class name 
   *
   * @param sfEvent $event
   * @access public
   * @static
   */
  public static function autoload($className)
  {
    require_once(dirname(__FILE__).'/vendor/dompdf/dompdf_config.inc.php');
    DOMPDF_autoload($className);
    return true;
  }
}