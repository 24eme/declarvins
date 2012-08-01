<?php
class EtablissementFamilles 
{

    const FAMILLE_PRODUCTEUR = "producteur";
    const FAMILLE_NEGOCIANT = "negociant";
    const FAMILLE_COURTIER = "courtier";

    const SOUS_FAMILLE_CAVE_PARTICULIERE = "cave_particuliere";
    const SOUS_FAMILLE_CAVE_COOPERATIVE = "cave_cooperative";
    const SOUS_FAMILLE_REGIONAL = "regional";
    const SOUS_FAMILLE_EXTERIEUR = "exterieur";
    const SOUS_FAMILLE_ETRANGER = "etranger";
    const SOUS_FAMILLE_UNION = "union";
    const SOUS_FAMILLE_VINIFICATEUR = "vinificateur";

    protected static $familles = array (
    	self::FAMILLE_PRODUCTEUR => "Producteur",
    	self::FAMILLE_NEGOCIANT => "Négociant",
    	self::FAMILLE_COURTIER => "Courtier"
    );
    protected static $sous_familles = array (
    	self::FAMILLE_PRODUCTEUR => array(self::SOUS_FAMILLE_CAVE_PARTICULIERE => "Cave particulière", 
                                          self::SOUS_FAMILLE_CAVE_COOPERATIVE => "Cave coopérative"),
    	self::FAMILLE_NEGOCIANT => array(self::SOUS_FAMILLE_REGIONAL => "Régional", 
                                         self::SOUS_FAMILLE_EXTERIEUR => "Extérieur", 
                                         self::SOUS_FAMILLE_ETRANGER => "Etranger", 
                                         self::SOUS_FAMILLE_UNION => "Union", 
                                         self::SOUS_FAMILLE_VINIFICATEUR => "Vinificateur"),
    	self::FAMILLE_COURTIER => array()
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

    public static function getFamillesForJs() 
    {
    	$sousFamilles =  self::getSousFamilles();
    	$result = array();
    	foreach ($sousFamilles as $key => $value) {
    		$result[$key] = $value;
    	}
    	return $result;
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
    
    public static function getFamilleLibelle($famille = null)
    {
    	$familles = self::getFamilles();
    	if (!in_array($famille, array_keys($familles))) {
    		throw new sfException('La clé famille "'.$famille.'" n\'existe pas');
    	}
    	return $familles[$famille];
    }

    
    public static function getSousFamilleLibelle($famille = null, $sousFamille = null)
    {
    	$sousFamilles = self::getSousFamillesByFamille($famille);
    	if (!in_array($sousFamille, array_keys($sousFamilles))) {
    		throw new sfException('La clé sous famille "'.$sousFamille.'" n\'existe pas');
    	}
    	return $sousFamilles[$sousFamille];
    }
}