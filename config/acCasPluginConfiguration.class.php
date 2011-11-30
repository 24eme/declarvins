<?php

/* This file is part of the acPhpCasPlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acPhpCasPluginConfiguration represents the configuration for acCasPlugin plugin.
 * 
 * @package    acCasPlugin
 * @subpackage config
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @version    0.1
 */
class acPhpCasPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Initializes acCasPlugin.
   * 
   * This method connects a listener on application exceptions
   * @access public
   * 
   */
	public function initialize()
	{
		require_once(dirname(__FILE__).'/../lib/vendor/phpCAS/CAS.class.php');
	}
}