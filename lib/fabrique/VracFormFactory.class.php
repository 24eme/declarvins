<?php
class VracFormFactory 
{
	public static function create($step, $configurationVrac, $etablissement, $user, $vrac) 
	{
		$form = null;
		switch ($step){
			case 'soussigne':
				$form = new VracSoussigneForm($configurationVrac, $etablissement, $user, $vrac);
				break;
			case 'produit':
				$form = new VracProduitForm($configurationVrac, $etablissement, $user, $vrac);
				break;
			case 'marche':
				$form = new VracMarcheForm($configurationVrac, $etablissement, $user, $vrac);
				break;
			case 'condition':
				$form = new VracConditionForm($configurationVrac, $etablissement, $user, $vrac);
				break;
			case 'transaction':
				$form = new VracTransactionForm($configurationVrac, $etablissement, $user, $vrac);
				break;
			case 'validation':
				$form = new VracValidationForm($configurationVrac, $etablissement, $user, $vrac);
				break;
			default:
				throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
		}
		return $form;
	}
}
