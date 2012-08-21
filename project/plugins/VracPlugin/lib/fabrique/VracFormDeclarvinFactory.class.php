<?php
class VracFormDeclarvinFactory 
{
public static function create($interpro, $step, $configurationVrac, $etablissement, $vrac) {
		$form = null;
		if ($interpro == 'INTERPRO-CIVP') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneCivpForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'marche':
					$form = new VracMarcheCivpForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'condition':
					$form = new VracConditionCivpForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'transaction':
					$form = new VracTransactionCivpForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'validation':
					$form = new VracValidationCivpForm($configurationVrac, $etablissement, $vrac);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} elseif ($interpro == 'INTERPRO-IR') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneIrForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'marche':
					$form = new VracMarcheIrForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'condition':
					$form = new VracConditionIrForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'transaction':
					$form = new VracTransactionIrForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'validation':
					$form = new VracValidationIrForm($configurationVrac, $etablissement, $vrac);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} elseif ($interpro == 'INTERPRO-IVSE') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneIvseForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'marche':
					$form = new VracMarcheIvseForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'condition':
					$form = new VracConditionIvseForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'transaction':
					$form = new VracTransactionIvseForm($configurationVrac, $etablissement, $vrac);
					break;
				case 'validation':
					$form = new VracValidationIvseForm($configurationVrac, $etablissement, $vrac);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} else {
			throw new sfException ('Interpro "'.$interpro.'" non gérée');
		}
		return $form;
	}
}
