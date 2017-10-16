<?php
class DRMProduitValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
        $this->addRequiredOption('drm');
        $this->addMessage('exist', 'Ce produit existe déjà !');
    }

    protected function doClean($values) 
    {
        if (!is_array($values['label'])) {
            $values['label'] = array();
        }
		$label = $values['label'];
		if ($libelle = $values['libelle']) {
			$label = array(md5($libelle));
		}
        if ($this->getDRM()->getProduit($values['hashref'], $label)) {
            throw new sfValidatorError($this, 'exist');
        }

        return $values;
    }

    protected function getDRM() 
    {
        return $this->getOption('drm');
    }
}