<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A symfony database driver for Doctrine.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineDatabase.class.php 28902 2010-03-30 20:57:27Z Jonathan.Wage $
 */
class sfCouchdbDatabase extends sfDatabase
{
    
  public function initialize($parameters = array())
  {
    parent::initialize($parameters);
    sfCouchdbManager::initializeClient($this->getParameter('dsn'), $this->getParameter('dbname'));
  }

  /**
   * Initializes the connection and sets it to object.
   *
   * @return void
   */
  public function connect()
  {
    $this->connection = sfCouchdbManager::getClient();
  }

  /**
   * Execute the shutdown procedure.
   *
   * @return void
   */
  public function shutdown()
  {
    if ($this->connection !== null)
    {
      $this->connection = null;
    }
  }
}