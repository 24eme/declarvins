<?php
class DRMESDetailsCrdForm extends acCouchdbObjectForm {
	
	public function configure() {
	    $details = new DRMESCollectionDetailCrdForm('DRMESDetailCrdForm', $this->getObject()->crd_details);
	    $this->embedForm('details', $details);
		$this->widgetSchema->setNameFormat('drm_es_detail_crd[%s]');
	}
}