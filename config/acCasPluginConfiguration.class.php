<?php

/* This file is part of the acCasPlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acCasPluginConfiguration represents the configuration for acCasPlugin plugin.
 * 
 * @package    acCasPlugin
 * @subpackage config
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @version    0.1
 */
class acCasPluginConfiguration extends sfPluginConfiguration
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