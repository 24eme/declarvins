<?php
/**
 * BaseDAIDS
 * 
 * Base model for DAIDS
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property acCouchdbJson $editeurs
 * @property string $raison_rectificative
 * @property string $etape
 * @property string $campagne
 * @property string $periode
 * @property string $version
 * @property string $precedente
 * @property integer $rectificative
 * @property DAIDSDeclaration $declaration
 * @property DRMDeclarant $declarant
 * @property string $identifiant
 * @property string $etablissement_num_interne
 * @property string $mode_de_saisie
 * @property acCouchdbJson $interpros
 * @property acCouchdbJson $valide

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method acCouchdbJson getEditeurs()
 * @method acCouchdbJson setEditeurs()
 * @method string getRaisonRectificative()
 * @method string setRaisonRectificative()
 * @method string getEtape()
 * @method string setEtape()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getPeriode()
 * @method string setPeriode()
 * @method string getVersion()
 * @method string setVersion()
 * @method string getPrecedente()
 * @method string setPrecedente()
 * @method integer getRectificative()
 * @method integer setRectificative()
 * @method DAIDSDeclaration getDeclaration()
 * @method DAIDSDeclaration setDeclaration()
 * @method DRMDeclarant getDeclarant()
 * @method DRMDeclarant setDeclarant()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getEtablissementNumInterne()
 * @method string setEtablissementNumInterne()
 * @method string getModeDeSaisie()
 * @method string setModeDeSaisie()
 * @method acCouchdbJson getInterpros()
 * @method acCouchdbJson setInterpros()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
 
 */
 
abstract class BaseDAIDS extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DAIDS';
    }
    
}