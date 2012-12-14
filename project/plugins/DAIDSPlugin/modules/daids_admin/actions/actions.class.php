<?php

/**
 * daids actions.
 *
 * @package    declarvin
 * @subpackage daids
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class daids_adminActions extends sfActions
{

  /**
   *
   * @param sfWebRequest $request 
   */
	public function executeIndex(sfWebRequest $request) 
	{
	      $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
	      $this->configuration_daids = $this->getConfigurationDAIDS($this->interpro->_id);
	}
	
	public function executeEditTaux(sfWebRequest $request) 
	{
	      $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
	      $this->configuration_daids = $this->getConfigurationDAIDS($this->interpro->_id);
	      $this->form = new DAIDSAdminReserveBloqueTauxForm($this->configuration_daids->reserve_bloque);
		  if ($request->isMethod(sfWebRequest::POST)) {
	    	$this->form->bind($request->getParameter($this->form->getName()));
	  	    if ($this->form->isValid()) {
	  			$this->form->save();
		        return $this->redirect('admin_daids');
	    	}
	      }
	}
	
	public function getConfigurationDAIDS($interpro_id = null)
	{
		return ConfigurationClient::getCurrent()->getConfigurationDAIDSByInterpro($interpro_id);
	}
}
