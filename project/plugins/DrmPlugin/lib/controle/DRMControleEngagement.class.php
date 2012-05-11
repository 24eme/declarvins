<?php
class DRMControleEngagement extends DRMControle
{
	protected $messages = array(
		'export' => "Je m'engage à ... pour l'export",
		'declassement' => "Je m'engage à ... pour le déclassement",
		'repli' => "Je m'engage à ... pour le repli",
		'pertes' => "Je m'engage à ... pour les pertes exceptionnelles",
		'odg' => "Je m'engage à ... pour l'odg"
	);
	
	public function __construct($code) 
	{
		$messages = array(
			'export' => "Je m'engage à ... pour l'export",
			'declassement' => "Je m'engage à ... pour le déclassement",
			'repli' => "Je m'engage à ... pour le repli",
			'pertes' => "Je m'engage à ... pour les pertes exceptionnelles",
			'odg' => "Je m'engage à ... pour l'odg"
		);
		
		parent::__construct('engagement', $code, null, $messages);
	}
}