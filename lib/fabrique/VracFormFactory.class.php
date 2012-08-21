<?php
class VracFormFactory 
{
	public static function create($step, $configurationVrac, $etablissement, $vrac) 
	{
		$form = null;
		switch ($step){
			case 'soussigne':
				$form = new VracSoussigneForm($configurationVrac, $etablissement, $vrac);
				break;
			case 'marche':
				$form = new VracMarcheForm($configurationVrac, $etablissement, $vrac);
				break;
			case 'condition':
				$form = new VracConditionForm($configurationVrac, $etablissement, $vrac);
				break;
			case 'transaction':
				$form = new VracTransactionForm($configurationVrac, $etablissement,$vrac);
				break;
			case 'validation':
				$form = new VracValidationForm($configurationVrac, $etablissement, $vrac);
				break;
			default:
				throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
		}
		return $form;
	}
}
