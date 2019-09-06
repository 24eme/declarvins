<?php use_helper('Edi'); ?>
<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<mouvements-balances xsi:schemaLocation="http://douane.finances.gouv.fr/app/ciel/dtiplus/v1 ciel-dti-plus_v1.0.12.xsd" xmlns="http://douane.finances.gouv.fr/app/ciel/dtiplus/v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <periode-taxation>
    <mois><?php echo $drm->getMois() ?></mois>
    <annee><?php echo $drm->getAnnee() ?></annee>
  </periode-taxation>
  <identification-redevable><?php echo $drm->declarant->no_accises ?></identification-redevable>
<?php if ($drm->hasExportableProduitsAcquittes()): ?>
  <droits-acquittes>
<?php if ($drm->hasStocksAcq()): foreach ($drm->getCielProduits() as $produit): if (!$produit->getHasSaisieAcq() || !$produit->getLibelleFiscal()) { continue; } ?>
    <produit>
	  <libelle-personnalise><![CDATA[<?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES, "UTF-8")) ?>]]></libelle-personnalise>
	  <libelle-fiscal><?php echo $produit->getLibelleFiscal() ?></libelle-fiscal>
<?php if ($produit->getTav()): ?>
	  <tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->getObservations()): ?>
	  <observations><![CDATA[<?php echo $produit->getObservations() ?>]]></observations>
<?php endif; ?>
	  <balance-stock>
<?php 
	$xml = '';
	noeudXml($produit, $ciel->get('balance-stocks/lot1/droits-acquittes'), $xml, array('mois', 'annee'));
	echo formatXml($xml, 5);
?>
	  </balance-stock>
    </produit>
<?php endforeach; endif; ?>
  </droits-acquittes>
<?php endif; ?>
  <droits-suspendus>
<?php if ($drm->hasStocks()): foreach ($drm->getCielProduits() as $produit): if (!$produit->getLibelleFiscal()) { continue; } ?>
    <produit>
	  <libelle-personnalise><![CDATA[<?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES, "UTF-8")) ?><?php if($produit->hasLabel()): ?> <?php echo $produit->getLabelKeyString(); ?><?php endif; ?>]]></libelle-personnalise>
	  <libelle-fiscal><?php echo $produit->getLibelleFiscal() ?></libelle-fiscal>
<?php if ($produit->getTav()): ?>
	  <tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->getObservations()): ?>
	  <observations><![CDATA[<?php echo $produit->getObservations() ?>]]></observations>
<?php endif; ?>
	  <balance-stock>
<?php 
    
	$xml = '';
	noeudXml($produit, $ciel->get('balance-stocks/lot1/droits-suspendus'), $xml, array('mois', 'annee'));
	echo formatXml($xml, 5);
?>
	  </balance-stock>
	</produit>
<?php endforeach; endif; ?>
  </droits-suspendus>
<?php if ($drm->exist('crds') && $drm->crds): foreach(drm2CrdCiel($drm) as $gcrds): ?>
  <compte-crd>
    <categorie-fiscale-capsules><?php echo $gcrds[0]->categorie->code ?></categorie-fiscale-capsules>
    <type-capsule><?php echo $gcrds[0]->type->code ?></type-capsule>
<?php foreach($gcrds as $crd) : ?>
    <centilisation volume="<?php echo $crd->centilisation->code ?>"<?php if ($crd->centilisation->centilitre): ?> volumePersonnalise="<?php echo $crd->centilisation->centilitre ?>" bib="<?php echo ($crd->centilisation->bib)? 1 : 0; ?>"<?php endif; ?>>
	  <stock-debut-periode><?php echo $crd->total_debut_mois ?></stock-debut-periode>
<?php if ($crd->entrees->achats || $crd->entrees->excedents || $crd->entrees->retours): ?>
	  <entrees-capsules>
<?php if ($crd->entrees->achats): ?>
	    <achats><?php echo $crd->entrees->achats ?></achats>
<?php endif; ?>
<?php if ($crd->entrees->excedents): ?>
	    <retours><?php echo $crd->entrees->excedents ?></retours>
<?php endif; ?>
<?php if ($crd->entrees->retours): ?>
	    <excedents><?php echo $crd->entrees->retours ?></excedents>
<?php endif; ?>
	  </entrees-capsules>
<?php endif; ?>
<?php if ($crd->sorties->utilisees || $crd->sorties->detruites || $crd->sorties->manquantes): ?>
	  <sorties-capsules>
<?php if ($crd->sorties->utilisees): ?>
	    <utilisations><?php echo $crd->sorties->utilisees ?></utilisations>
<?php endif; ?>
<?php if ($crd->sorties->detruites): ?>
		<destructions><?php echo $crd->sorties->detruites ?></destructions>
<?php endif; ?>
<?php if ($crd->sorties->manquantes): ?>
		<manquants><?php echo $crd->sorties->manquantes ?></manquants>
<?php endif; ?>
	  </sorties-capsules>
<?php endif; ?>
	  <stock-fin-periode><?php echo $crd->total_fin_mois ?></stock-fin-periode>
    </centilisation>
<?php endforeach; ?>
  </compte-crd>
<?php endforeach; endif; ?>
<?php if (($drm->declaratif->empreinte->debut && $drm->declaratif->empreinte->fin) || ($drm->declaratif->daa->debut && $drm->declaratif->daa->fin) || ($drm->declaratif->dsa->debut && $drm->declaratif->dsa->fin)): ?>
  <document-accompagnement>
<?php if ($drm->declaratif->empreinte->debut && $drm->declaratif->empreinte->fin): ?>
	<numero-empreintes>
	  <debut-periode><?php echo $drm->declaratif->empreinte->debut ?></debut-periode>
	  <fin-periode><?php echo $drm->declaratif->empreinte->fin ?></fin-periode>
	  <nombre-document-empreinte><?php echo $drm->declaratif->empreinte->nb ?></nombre-document-empreinte>
	</numero-empreintes>
<?php endif; ?>
<?php if ($drm->declaratif->daa->debut && $drm->declaratif->daa->fin): ?>
	<daa-dca>
	  <debut-periode><?php echo $drm->declaratif->daa->debut ?></debut-periode>
	  <fin-periode><?php echo $drm->declaratif->daa->fin ?></fin-periode>
	  <nombre-document-empreinte><?php echo $drm->declaratif->daa->nb ?></nombre-document-empreinte>
	</daa-dca>
<?php endif; ?>
<?php if ($drm->declaratif->dsa->debut && $drm->declaratif->dsa->fin): ?>
	<dsa-dsac>
	  <debut-periode><?php echo $drm->declaratif->dsa->debut ?></debut-periode>
	  <fin-periode><?php echo $drm->declaratif->dsa->fin ?></fin-periode>
	  <nombre-document-empreinte><?php echo $drm->declaratif->dsa->nb ?></nombre-document-empreinte>
	</dsa-dsac>
<?php endif; ?>
  </document-accompagnement>
<?php endif; ?>
<?php if ($rnas = $drm->getExportableRna()): foreach($rnas as $rna): ?>
  <releve-non-apurement>
	<numero-daa-dac-dae><?php echo $rna[DRMCsvEdi::CSV_ANNEXE_NUMERODOCUMENT] ?></numero-daa-dac-dae>
	<date-expedition><?php echo $rna[DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTDATEEMISSION] ?></date-expedition>
	<numero-accise-destinataire><?php echo $rna[DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTACCISEDEST] ?></numero-accise-destinataire>
  </releve-non-apurement>
<?php endforeach; endif; ?>
<?php if ($drm->declaratif->exist('statistiques') && ($drm->declaratif->statistiques->jus || $drm->declaratif->statistiques->mcr || $drm->declaratif->statistiques->vinaigre)): ?>
  <statistiques>
<?php if ($drm->declaratif->statistiques->jus): ?>
	<quantite-mouts-jus><?php echo sprintf("%.2f", $drm->declaratif->statistiques->jus) ?></quantite-mouts-jus>
<?php endif; ?>
<?php if ($drm->declaratif->statistiques->mcr): ?>
	<quantite-mouts-mcr><?php echo sprintf("%.2f", $drm->declaratif->statistiques->mcr) ?></quantite-mouts-mcr>
<?php endif; ?>
<?php if ($drm->declaratif->statistiques->vinaigre): ?>
	<quantite-vins-vinaigre><?php echo sprintf("%.2f", $drm->declaratif->statistiques->vinaigre) ?></quantite-vins-vinaigre>
<?php endif; ?>
  </statistiques>
<?php endif; ?>
</mouvements-balances>