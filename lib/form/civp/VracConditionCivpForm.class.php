<?php
class VracConditionCivpForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'prix_unitaire',
           'type_prix',
	       'date_limite_retiraison',
           'commentaires_conditions',
           'part_cvo',
           'prix_total',
           'conditions_paiement',
	       'vin_livre',
           'date_debut_retiraison',
           'calendrier_retiraison',
           'contrat_pluriannuel',
           'reference_contrat_pluriannuel',
	       'delai_paiement',
           'echeancier_paiement',
           'clause_reserve_retiraison',
           'retiraisons',
           'paiements'
		));
		$this->widgetSchema->setNameFormat('vrac_condition[%s]');
    }
}