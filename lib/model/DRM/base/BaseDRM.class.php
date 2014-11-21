<?php
/**
 * BaseDRM
 * 
 * Base model for DRM
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property acCouchdbJson $editeurs
 * @property string $apurement_possible
 * @property string $raison_rectificative
 * @property string $etape
 * @property string $campagne
 * @property string $periode
 * @property string $version
 * @property string $precedente
 * @property string $referente
 * @property integer $rectificative
 * @property acCouchdbJson $droits
 * @property DRMDeclaration $declaration
 * @property DRMDeclarant $declarant
 * @property acCouchdbJson $declaratif
 * @property string $identifiant
 * @property string $identifiant_ivse
 * @property string $identifiant_drm_historique
 * @property string $etablissement_num_interne
 * @property string $mode_de_saisie
 * @property string $commentaires
 * @property acCouchdbJson $interpros
 * @property acCouchdbJson $valide
 * @property acCouchdbJson $manquants
 * @property acCouchdbJson $douane
 * @property acCouchdbJson $mouvements

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method acCouchdbJson getEditeurs()
 * @method acCouchdbJson setEditeurs()
 * @method string getApurementPossible()
 * @method string setApurementPossible()
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
 * @method string getReferente()
 * @method string setReferente()
 * @method integer getRectificative()
 * @method integer setRectificative()
 * @method acCouchdbJson getDroits()
 * @method acCouchdbJson setDroits()
 * @method DRMDeclaration getDeclaration()
 * @method DRMDeclaration setDeclaration()
 * @method DRMDeclarant getDeclarant()
 * @method DRMDeclarant setDeclarant()
 * @method acCouchdbJson getDeclaratif()
 * @method acCouchdbJson setDeclaratif()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getIdentifiantIvse()
 * @method string setIdentifiantIvse()
 * @method string getIdentifiantDrmHistorique()
 * @method string setIdentifiantDrmHistorique()
 * @method string getEtablissementNumInterne()
 * @method string setEtablissementNumInterne()
 * @method string getModeDeSaisie()
 * @method string setModeDeSaisie()
 * @method string getCommentaires()
 * @method string setCommentaires()
 * @method acCouchdbJson getInterpros()
 * @method acCouchdbJson setInterpros()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 * @method acCouchdbJson getManquants()
 * @method acCouchdbJson setManquants()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
 * @method acCouchdbJson getMouvements()
 * @method acCouchdbJson setMouvements()
 
 */
 
abstract class BaseDRM extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DRM';
    }
    
}