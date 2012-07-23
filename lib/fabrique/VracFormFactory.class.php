<?php
class VracFormFactory 
{
	public static function create($interpro, $step, $configurationVrac, $object) {
		$form = null;
		if ($interpro == 'INTERPRO-civp') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneCivpForm($configurationVrac, $object);
					break;
				case 'marche':
					$form = new VracMarcheCivpForm($configurationVrac, $object);
					break;
				case 'condition':
					$form = new VracConditionCivpForm($configurationVrac, $object);
					break;
				case 'transaction':
					$form = new VracTransactionCivpForm($configurationVrac, $object);
					break;
				case 'validation':
					$form = new VracValidationCivpForm($configurationVrac, $object);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} elseif ($interpro == 'INTERPRO-IR') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneIrForm($configurationVrac, $object);
					break;
				case 'marche':
					$form = new VracMarcheIrForm($configurationVrac, $object);
					break;
				case 'condition':
					$form = new VracConditionIrForm($configurationVrac, $object);
					break;
				case 'transaction':
					$form = new VracTransactionIrForm($configurationVrac, $object);
					break;
				case 'validation':
					$form = new VracValidationIrForm($configurationVrac, $object);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} elseif ($interpro == 'INTERPRO-intervins-sud-est') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneIvseForm($configurationVrac, $object);
					break;
				case 'marche':
					$form = new VracMarcheIvseForm($configurationVrac, $object);
					break;
				case 'condition':
					$form = new VracConditionIvseForm($configurationVrac, $object);
					break;
				case 'transaction':
					$form = new VracTransactionIvseForm($configurationVrac, $object);
					break;
				case 'validation':
					$form = new VracValidationIvseForm($configurationVrac, $object);
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