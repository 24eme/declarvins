<?php
class Douane extends BaseDouane {
	const DOUANE_KEY = 'DOUANE-';
	const STATUT_ACTIF = 'Actif';
	const STATUT_INACTIF = 'Inactif';
	
	public function generateId($id)
	{
		return self::DOUANE_KEY.$id;
	}
}