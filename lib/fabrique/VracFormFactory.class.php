<?php
class VracFormFactory 
{
	public static function create($step, $configurationVrac, $object) 
	{
		$form = null;
		switch ($step){
			case 'soussigne':
				$form = new VracSoussigneForm($configurationVrac, $object);
				break;
			case 'marche':
				$form = new VracMarcheForm($configurationVrac, $object);
				break;
			case 'condition':
				$form = new VracConditionForm($configurationVrac, $object);
				break;
			case 'transaction':
				$form = new VracTransactionForm($configurationVrac, $object);
				break;
			case 'validation':
				$form = new VracValidationForm($configurationVrac, $object);
				break;
			default:
				throw new sfException ('Fabrique : Etape "'.$step.'" non gérée');
		}
		return $form;
	}
}
