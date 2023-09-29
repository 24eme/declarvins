<?php

class VracMarcheValidator extends sfValidatorBase {

    protected $ivse;
    protected $civp;
    protected $vrac;

    public function __construct($vrac, $options = array(), $messages = array())
    {
        if ($vrac) {
            $this->vrac = $vrac;
            $this->ivse = $vrac->isConditionneIvse();
            $this->civp = $vrac->isConditionneCivp();
        } else {
            $this->vrac = null;
            $this->ivse = null;
            $this->civp = null;
        }

        parent::__construct($options, $messages);
    }

    public function configure($options = array(), $messages = array()) {
        $today = date('Y-m-d');
        $annee = (($today >= date('Y').'-10-01' && $today <= date('Y').'-12-31') || $this->vrac->type_transaction != 'vrac')? (date('Y')+1) : date('Y');
        $this->addOption('determination_prix_field', 'determination_prix');
        $this->addOption('determination_prix_date_field', 'determination_prix_date');
        $this->addMessage('impossible_volume', "La somme des volumes ne correspond pas au volume total proposé");
        $this->addMessage('impossible_date', "La date limite doit être supérieur ou égale aux dates de l'échéancier");
        $this->addMessage('impossible_date_retiraison', "La date limite doit être supérieur ou égale à la date de debut de retiraison");
        $this->addMessage('echeancier_date', "Vous devez saisir les dates de votre échéancier");
        $this->addMessage('echeancier_montant', "Vous devez saisir les montants de votre échéancier");
        $this->addMessage('echeancier_max_date_generique', "Vos échéances ne peuvent s'étaler au dela du cadre légal (date limite de retiraison + delai de paiement)");
        if ($this->vrac->contrat_pluriannuel) {
            $this->addMessage('echeancier_max_date', "Vos échéances ne peuvent s'étaler au dela du 15/12/$annee");
            $this->addMessage('echeancier_moitie_montant', "Au moins la moitié du montant total de la transaction doit être réglée au 30/06/$annee");
        } else {
            $this->addMessage('echeancier_max_date', "Vos échéances ne peuvent s'étaler au dela du 30/09/$annee");
            $this->addMessage('echeancier_moitie_montant', "Au moins la moitié du montant total de la transaction doit être réglée à la moitié de la période d'aujourd'hui au 30/09/$annee");
        }
        $this->addMessage('echeancier_montant_total', "Le prix total de l'échéancier ne correspond pas au montant total du contrat");
        $this->addMessage('impossible_tranche_prix', "Incohérence dans la fourchette de prix déclarée");
    }

	protected function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }

    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;

    	if ($values['type_prix_1'] == 'non_definitif') {
    		if (!isset($values['type_prix_2']) || !$values['type_prix_2']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'type_prix_2');
    			$hasError = true;
    		}
    		if (!$values['determination_prix']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'determination_prix');
    			$hasError = true;
    		}
    		if (!$values['determination_prix_date']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'determination_prix_date');
    			$hasError = true;
    		}
    	}
        if ($this->vrac->isPluriannuel()) {
            	if ((isset($values['volume_propose']) && $values['volume_propose'] <= 0)&&(isset($values['pourcentage_recolte']) && $values['pourcentage_recolte'] <= 0)&&(isset($values['surface']) && $values['surface'] <= 0)) {
            			$errorSchema->addError(new sfValidatorError($this, 'required'), 'volume_propose');
                        $errorSchema->addError(new sfValidatorError($this, 'required'), 'pourcentage_recolte');
                        $errorSchema->addError(new sfValidatorError($this, 'required'), 'surface');
            			$hasError = true;
            	}
        } else {
        	if (isset($values['volume_propose']) && $values['volume_propose'] <= 0) {
        			$errorSchema->addError(new sfValidatorError($this, 'required'), 'volume_propose');
        			$hasError = true;
        	}
        }
        if (isset($values['prix_unitaire']) && $values['prix_unitaire'] <= 0) {
                $errorSchema->addError(new sfValidatorError($this, 'required'), 'prix_unitaire');
                $hasError = true;
        }

    	if (isset($values['conditions_paiement']) && $values['conditions_paiement'] == 'cadre_reglementaire') {

    	    if (!$values['delai_paiement'] && !$this->vrac->isPluriannuel()) {
    	        $errorSchema->addError(new sfValidatorError($this, 'required'), 'delai_paiement');
    	        $hasError = true;
    	    }
    	    if ($values['delai_paiement'] == 'autre' && (!isset($values['delai_paiement_autre']) || !$values['delai_paiement_autre'])) {
    	        $errorSchema->addError(new sfValidatorError($this, 'required'), 'delai_paiement_autre');
    	        $hasError = true;
    	    }
    	}
        if (($values['conditions_paiement'] == VracClient::ECHEANCIER_PAIEMENT||(is_array($values['conditions_paiement']) && in_array(VracClient::ECHEANCIER_PAIEMENT, $values['conditions_paiement']))) && !$this->vrac->isPluriannuel()) {
            $montantTotal = 0;
            $maxd = null;
            if (is_array($values['paiements'])) {
                if (!$values['paiements']) {
                    $errorSchema->addError(new sfValidatorError($this, 'echeancier_date'), 'conditions_paiement');
                    $hasError = true;
                }
                foreach ($values['paiements'] as $key => $paiement) {
                    if (!$paiement['date']) {
                        $errorSchema->addError(new sfValidatorError($this, 'echeancier_date'), 'conditions_paiement');
                        $hasError = true;
                    }
                    if (!$paiement['montant']) {
                        $errorSchema->addError(new sfValidatorError($this, 'echeancier_montant'), 'conditions_paiement');
                        $hasError = true;
                    }
                    if (!$maxd || $paiement['date'] > $maxd) {
                        $maxd = $paiement['date'];
                    }
                    $montantTotal += $paiement['montant'];
                }
                if (!$this->ivse&&!$this->civp) {
                    $today = date('Y-m-d');
                    $annee = (($today >= date('Y').'-10-01' && $today <= date('Y').'-12-31') || $this->vrac->type_transaction != 'vrac')? (date('Y')+1) : date('Y');
                    $limite = "$annee-09-30";
                    $date1 = new DateTime();
                    if ($this->vrac->contrat_pluriannuel||$this->vrac->isAdossePluriannuel()) {
                        $limite = "$annee-12-15";
                        if ($ref = $this->vrac->getContratPluriannelReferent()) {
                            if ($this->vrac->getCampagne() == $ref->pluriannuel_campagne_fin) {
                                $limite = "$annee-09-30";
                            }
                        }
                        if (date('Ymd') > $annee.'0630') {
                            $date1 = new DateTime(date("Y-m-t"));
                        } else {
                            $date1 = new DateTime("$annee-06-30");
                        }
                        $date2 = new DateTime($limite);
                        $nbJour = ceil($date2->diff($date1)->format("%a"));
                    } else {
                        $date2 = new DateTime($limite);
                        $nbJour = ceil($date2->diff($date1)->format("%a") / 2);
                    }
                    $date1->modify("+$nbJour day");

                    $moitie = $date1->format('Y-m-d');
                    $montantMoitie = 0;
                    foreach ($values['paiements'] as $key => $paiement) {
                        if ($paiement['date'] <= $moitie) {
                            $montantMoitie += $paiement['montant'];
                        }
                    }

                    if ($montantMoitie < round($montantTotal/2, 2)) {
                        $errorSchema->addError(new sfValidatorError($this, 'echeancier_moitie_montant'), 'conditions_paiement');
                        $hasError = true;
                    }

                    if ($maxd > $limite) {
                        $errorSchema->addError(new sfValidatorError($this, 'echeancier_max_date'), 'conditions_paiement');
                        $hasError = true;
                    }
                }
                if ($this->civp) {
                    $limite =  (isset($values['date_limite_retiraison']) && $values['date_limite_retiraison'])? $values['date_limite_retiraison'] : null;
                    $delai =  (isset($values['delai_paiement']) && $values['delai_paiement'])? str_replace('_jours', '', $values['delai_paiement']) : null;
                    $limite = ($limite && $delai && ctype_digit(strval($delai)))? date('Y-m-d', strtotime($limite. "+$delai days")) : null;
                    if ($limite && $maxd && $maxd > $limite && !$this->vrac->version) {
                        $errorSchema->addError(new sfValidatorError($this, 'echeancier_max_date_generique'), 'conditions_paiement');
                        $hasError = true;
                    }
                }

            }
            $vol = (isset($values['volume_propose']))? $values['volume_propose'] : 0;
            $cvo = (isset($values['part_cvo']))? $values['part_cvo'] : 0;
            $prix = (isset($values['prix_unitaire']))? $values['prix_unitaire'] : 0;
            $totalMax = ceil(($prix + $cvo) * $vol);
            $totalMin = floor($prix * $vol);
            if ($montantTotal < $totalMin || $montantTotal > $totalMax) {
                if ($this->civp && $this->vrac->version) {
                    // non bloquant pour saisie prix definitif
                } else {
                    $errorSchema->addError(new sfValidatorError($this, 'echeancier_montant_total'), 'conditions_paiement');
                    $hasError = true;
                }
            }
        }
        if (isset($values['date_limite_retiraison']) && $values['date_limite_retiraison']) {
            $date_limite_retiraison = new DateTime($values['date_limite_retiraison']);
            if (isset($values['date_debut_retiraison']) && $values['date_debut_retiraison']) {
                $date_debut_retiraison = new DateTime($values['date_debut_retiraison']);
                if ($date_debut_retiraison->format('Ymd') > $date_limite_retiraison->format('Ymd')) {
                    $errorSchema->addError(new sfValidatorError($this, 'impossible_date_retiraison'), 'date_limite_retiraison');
                    $hasError = true;
                }
            }
        }
        if (isset($values['pluriannuel_prix_plancher']) && isset($values['pluriannuel_prix_plafond'])) {
            if ($values['pluriannuel_prix_plancher'] > $values['pluriannuel_prix_plafond']) {
                $errorSchema->addError(new sfValidatorError($this, 'impossible_tranche_prix'), 'pluriannuel_prix_plancher');
                $hasError = true;
            }
        }

    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }

}
