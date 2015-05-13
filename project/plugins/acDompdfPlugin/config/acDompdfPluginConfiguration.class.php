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
  	require_once(dirname(__FILE__).'/../lib/vendor/dompdf/dompdf_config.inc.php');
    sfConfig::set('sf_autoloading_functions', array(array('acDompdfBridge', 'autoload')));
  }
}
