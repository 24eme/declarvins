<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EtablissementChoiceForm extends baseForm {

    public function configure()
    {
        $this->setWidget('identifiant', new WidgetEtablissement());

        $this->widgetSchema->setLabel('identifiant', 'Sélectionner un établissement&nbsp;:');
        
        $this->setValidator('identifiant', new ValidatorEtablissement(array('required' => true)));
        
        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'un etablissement est obligatoire');        
        
        $this->widgetSchema->setNameFormat('etablissement[%s]');
    }

    public function configureFamilles($familles) {
        $this->getWidget('identifiant')->setOption('familles', $familles);
        $this->getValidator('identifiant')->setOption('familles', $familles);
    }

    public function getEtablissement() {

        return $this->getValidator('identifiant')->getDocument();
    }
    
}

