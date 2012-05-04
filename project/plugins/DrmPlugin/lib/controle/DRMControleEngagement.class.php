<?php
class DRMControleEngagement extends DRMControle
{
	protected $messages = array(
		'export' => "Je m'engage à ... pour l'export",
		'declassement' => "Je m'engage à ... pour le déclassement",
		'repli' => "Je m'engage à ... pour le repli",
		'pertes' => "Je m'engage à ... pour les pertes exceptionnelles"
	);
	
	public function __construct($code) 
	{
		$messages = array(
			'export' => "Je m'engage à ... pour l'export",
			'declassement' => "Je m'engage à ... pour le déclassement",
			'repli' => "Je m'engage à ... pour le repli",
			'pertes' => "Je m'engage à ... pour les pertes exceptionnelles"
		);
		
		parent::__construct('engagement', $code, null, $messages);
	}
}