<?php
class EtablissementFamilles 
{
    protected static $familles = array (
    	"producteur" => "Producteur",
    	"negociant" => "Négociant",
    	"courtier" => "Courtier"
    );
    protected static $sous_familles = array (
    	"producteur" => array("cave_particuliere" => "Cave particulière", "cave_cooperative" => "Cave cooperative"),
    	"negociant" => array("regional" => "Régional", "exterieur" => "Extérieur", "etranger" => "Etranger", "union" => "Union", "vinificateur" => "Vinificateur"),
    	"courtier" => array()
    );
    protected static $droits = array (
    	"producteur_cave_particuliere" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_DTI, TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"producteur_cave_cooperative" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_DTI, TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"negociant_regional" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"negociant_exterieur" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"negociant_etranger" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"negociant_union" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"negociant_vinificateur" => array(TiersSecurityUser::CREDENTIAL_DROIT_DRM_DTI, TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER, TiersSecurityUser::CREDENTIAL_DROIT_VRAC),
    	"courtier" => array(TiersSecurityUser::CREDENTIAL_DROIT_VRAC)
    );

    public static function getFamilles() 
    {
    	return self::$familles;
    }

    public static function getSousFamilles() 
    {
    	return self::$sous_familles;
    }

    public static function getSousFamillesByFamille($famille) 
    {
    	if (!in_array($famille, array_keys(self::getFamilles()))) {
    		throw new sfException('La clé famille "'.$famille.'" n\'existe pas');
    	}
    	$sousFamilles = self::getSousFamilles();
    	return $sousFamilles[$famille];
    }

    public static function getDroits() 
    {
    	return self::$droits;
    }

    public static function getDroitsByFamilleAndSousFamille($famille, $sousFamille = null) 
    {
    	if (!in_array($famille, array_keys(self::getFamilles()))) {
    		throw new sfException('La clé famille "'.$famille.'" n\'existe pas');
    	}
    	$index = $famille;
    	if ($sousFamille) {
    		if (!in_array($sousFamille, array_keys(self::getSousFamillesByFamille($famille)))) {
    			throw new sfException('La clé sous famille "'.$sousFamille.'" n\'existe pas pour la famille "'.$famille.'"');
    		}
    		$index .= '_'.$sousFamille;
    	}
    	$droits = self::getDroits();
    	if (!in_array($index, array_keys($droits))) {
    		throw new sfException('Aucun droit défini pour la famille "'.$famille.'" et la sous famille "'.$sousFamille.'"');
    	}
    	return $droits[$index];
    }
}