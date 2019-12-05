<?php
class DRMESDetailsCrdForm extends acCouchdbObjectForm {
    
    const SUB_FORM = 'DRMESDetailCrdForm';
	
	public function configure() {
	    $details = new DRMESCollectionDetailCrdForm(self::SUB_FORM, $this->getObject()->crd_details);
	    $this->embedForm('details', $details);
		$this->widgetSchema->setNameFormat('drm_es_detail_crd[%s]');
	}

    public function getFormTemplateDetails() {
        $form_dynamique = self::SUB_FORM;
        $form_embed = new $form_dynamique($this->getObject()->crd_details->add());
        $form = new VracCollectionTemplateForm($this, 'details', $form_embed);

        return $form->getFormTemplate();
    }
}