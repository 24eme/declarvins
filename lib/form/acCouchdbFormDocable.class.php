<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class acCouchdbFormDocable
{
	protected $form = null;
	protected $doc = null;
	const FIELDNAME_REVISION = '_revision';

	public function __construct(sfForm $form, acCouchdbDocument $doc)
	{
		$this->doc = $doc;
		$this->form = $form;
	}

	public function init() {
		$this->addRevision();
	}

	public function addRevision() {
		$this->form->setWidget(self::FIELDNAME_REVISION, new sfWidgetFormInputHidden());
		$this->form->setValidator(self::FIELDNAME_REVISION, new sfValidatorPass(array('required' => true)));
		$this->form->setDefault(self::FIELDNAME_REVISION, $this->doc->get('_rev'));
	}

	public function removeRevision()
	{
		unset($this->form[self::FIELDNAME_REVISION]);
	}

	public function updateRevision() {
		if ($this->form->getValue(self::FIELDNAME_REVISION)) {
			//$this->doc->set('_rev', $this->form->getValue(self::FIELDNAME_REVISION));
		}
	}	

	public function beforeEmbedForm($name, sfForm $form, $decorator = null)
	{
		if ($form instanceof acCouchdbFormDocableInterface) {
			$form->getDocable()->removeRevision();
		}
	}

	public function postBind(array $taintedValues = null, array $taintedFiles = null)
	{
		if ($this->form->isValid()) {
			$this->updateRevision();
		}
	}
}