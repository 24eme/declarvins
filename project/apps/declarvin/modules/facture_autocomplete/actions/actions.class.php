<?php

class facture_autocompleteActions extends sfActions
{

  	public function executeAll(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro');
        $term = $request->getParameter('q');
        $factures = FactureEtablissementView::getInstance()->getFactureNonPaye();
        $this->json = [];
        $nb = 0;
        foreach ($factures as $facture) {
            if ($interpro && $facture->key[FactureEtablissementView::KEYS_INTERPRO] != $interpro) {
                continue;
            }
            $idEtablissement = self::getEtablissementIdentifiant($facture->id);
  	        $text = self::makeFactureLibelleForAutocomplete($facture->value, $idEtablissement);
  	        if (Search::matchTerm($term, $text)) {
  	            $this->json[$idEtablissement] = $text;
                $nb++;
  	        }
  	        if ($nb >= 10) {
  	            break;
  	         }
        }
		$this->setTemplate('index');
  	}

    public static function getEtablissementIdentifiant($factureId) {
        return substr(str_replace('FACTURE-', '', $factureId), 0, -11);
    }

    public static function makeFactureLibelleForAutocomplete($datas, $idEtablissement) {
        $libelle = 'Facture n° ';

        if ($num = $datas[FactureEtablissementView::VALUE_NUMERO_ARCHIVE]) {
            $libelle .= $num;
        } else {
            $libelle .= '-';
        }

        if ($date = $datas[FactureEtablissementView::VALUE_DATE_FACTURATION]) {
            $libelle .= ' du '.Date::francizeDate($date);
        }

        if ($montant = $datas[FactureEtablissementView::VALUE_TOTAL_TTC]) {
            $libelle .= ' de '.$montant.'€ HT';
        }

        if ($declarant = $datas[FactureEtablissementView::VALUE_DECLARANT]) {
            $libelle .= ' – '.$declarant;
        }

        if ($idEtablissement) {
            $libelle .= ' ('.$idEtablissement.')';
        }

        return $libelle;
    }

}
