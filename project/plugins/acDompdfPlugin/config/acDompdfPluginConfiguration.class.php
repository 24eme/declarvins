<?php
/* This file is part of the acDompdfPlugin package.
 * (c) Actualys
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * acDompdfPluginConfiguration represents the configuration for acDompdfPlugin plugin.
 *
 * @package    acDompdfPlugin
 * @subpackage config
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @version    0.1
 */
class acDompdfPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Initializes acDompdfPlugin.
   * 
   * 
   * @access public
   * 
   */
  public function initialize()
  {
	  require_once dirname(__FILE__).'/../lib/vendor/dompdf/lib/html5lib/Parser.php';
	  require_once dirname(__FILE__).'/../lib/vendor/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
	  require_once dirname(__FILE__).'/../lib/vendor/dompdf/lib/php-svg-lib/src/autoload.php';
	  require_once dirname(__FILE__).'/../lib/vendor/dompdf/src/Autoloader.php';
          Dompdf\Autoloader::register();  
  }
}
