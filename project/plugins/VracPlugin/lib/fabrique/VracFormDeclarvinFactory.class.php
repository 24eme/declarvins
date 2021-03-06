<?php
class VracFormDeclarvinFactory 
{
public static function create($interpro, $step, $configurationVrac, $etablissement, $user, $vrac) {
		$form = null;
		if ($interpro == 'INTERPRO-CIVP') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'produit':
					$form = new VracProduitCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'marche':
					$form = new VracMarcheCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'condition':
					$form = new VracConditionCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'transaction':
					$form = new VracTransactionCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'clause':
					$form = new VracClauseCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'validation':
					$form = new VracValidationCivpForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} elseif ($interpro == 'INTERPRO-IR') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'produit':
					$form = new VracProduitIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'marche':
					$form = new VracMarcheIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'condition':
					$form = new VracConditionIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'transaction':
					$form = new VracTransactionIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'clause':
					$form = new VracClauseIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'validation':
					$form = new VracValidationIrForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				default:
					throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
			}
		} elseif ($interpro == 'INTERPRO-IVSE') {
			switch ($step){
				case 'soussigne':
					$form = new VracSoussigneIvseForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'produit':
					$form = new VracProduitIvseForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'marche':
					$form = new VracMarcheIvseForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'condition':
					$form = new VracConditionIvseForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'transaction':
					$form = new VracTransactionIvseForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'clause':
					$form = new VracClauseIvseForm($configurationVrac, $etablissement, $user, $vrac);
					break;
				case 'validation':
					$form = new VracValidationIvseForm($configurationVrac, $etablissement, $user, $vrac);
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
