<?php
class DRMControleWarning extends DRMControle
{
	public function __construct($code, $lien) {
		$messages = array(
			'mouvement' => "Attention ... mouvement",
			'declassement' => "Attention ... declassement",
		);
		parent::__construct('warning', $code, $lien, $messages);
	}
}