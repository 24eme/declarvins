<?php

/**
 * messages actions.
 *
 * @package    declarvin
 * @subpackage messages
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messagesActions extends sfActions
{

    public function executeMessageAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        return $this->renderText(json_encode(array('titre' => $request->getParameter('title', null),
						   'url_doc' => $request->getParameter('url_doc', '/telecharger_la_notice'),
                'message' => acCouchdbManager::getClient('Messages')->getMessage($request->getParameter('id', null)))));
        
        
    }
    
}
