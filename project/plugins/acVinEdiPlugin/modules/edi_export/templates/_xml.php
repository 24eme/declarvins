<?php use_helper('Edi'); ?>
<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<message-interprofession xmlns="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0 echanges-interprofession-1.7.xsd">
	<siren-interprofession><?php echo $drm->getEtablissement()->getInterproObject()->siren ?></siren-interprofession>
	<declaration-recapitulative>
		<identification-declarant>
			<numero-agrement><?php echo $drm->declarant->no_accises ?></numero-agrement>
<?php if($drm->declarant->cvi): ?>
			<numero-cvi><?php echo $drm->declarant->cvi ?></numero-cvi>
<?php endif; ?>
		</identification-declarant>
		<periode>
			<mois><?php echo $drm->getMois() ?></mois>
			<annee><?php echo $drm->getAnnee() ?></annee>
		</periode>
		<declaration-neant><?php echo ($drm->isNeant())? "true" : "false"; ?></declaration-neant>
<?php if (!$drm->isNeant()): ?>
		<droits-suspendus>
<?php if ($drm->hasStocks()): foreach ($drm->getCielProduits() as $produit): if ($produit->isCleanable() && $drm->canSetStockDebutMois()) continue; ?>
			<produit>
<?php if ($produit->getLibelleFiscal()): ?>
                <libelle-fiscal><?php echo $produit->getLibelleFiscal() ?></libelle-fiscal>
<?php elseif ($produit->isInao()): ?>
				<code-inao><?php echo $produit->getInao() ?></code-inao>
<?php elseif ($produit->getInao()): ?>
				<libelle-fiscal><?php echo $produit->getInao() ?></libelle-fiscal>
<?php endif; ?>
				<libelle-personnalise><![CDATA[<?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES, "UTF-8")) ?><?php if($produit->hasLabel()): ?> <?php echo $produit->getLabelKeyString(); ?><?php endif; ?>]]></libelle-personnalise>
<?php if ($produit->getTav()): ?>
				<tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->getPremix()): ?>
				<premix>true</premix>
<?php endif; ?>
<?php if ($produit->getObservations()): ?>
				<observations><![CDATA[<?php echo $produit->getObservations() ?>]]></observations>
<?php endif; ?>
				<balance-stocks>
<?php
	$xml = '';
	noeudXml($produit, $ciel->get('balance-stocks/'.$drm->getCielLot().'/droits-suspendus'), $xml, array('mois', 'annee'));
	echo formatXml($xml, 5);
?>
				</balance-stocks>
			</produit>
<?php endforeach; endif; ?>
			<stockEpuise><?php echo (!$drm->getTotalStock() && !$drm->canSetStockDebutMois())? "true" : "false"; ?></stockEpuise>
		</droits-suspendus>
<?php if ($drm->hasExportableProduitsAcquittes()): ?>
		<droits-acquittes>
<?php if ($drm->hasStocksAcq()): foreach ($drm->getCielProduits() as $produit): if ($produit->isCleanable(true) && $drm->canSetStockDebutMois(true)) continue;  if (!$produit->getHasSaisieAcq()) { continue; } ?>
			<produit>
<?php if ($produit->getLibelleFiscal()): ?>
                <libelle-fiscal><?php echo $produit->getLibelleFiscal() ?></libelle-fiscal>
<?php elseif ($produit->isInao()): ?>
				<code-inao><?php echo $produit->getInao() ?></code-inao>
<?php elseif ($produit->getInao()): ?>
				<libelle-fiscal><?php echo $produit->getInao() ?></libelle-fiscal>
<?php endif; ?>
<?php if(((int)$drm->getAnnee() == 2020 && (int)$drm->getMois() < 8) || (int)$drm->getAnnee() < 2020): ?>
				<libelle-personnalise><![CDATA[<?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES, "UTF-8")) ?>]]></libelle-personnalise>
<?php else: ?>
				<libelle-personnalise><![CDATA[<?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES, "UTF-8")) ?><?php if($produit->hasLabel()): ?> <?php echo $produit->getLabelKeyString(); ?><?php endif; ?>]]></libelle-personnalise>
<?php endif; ?>
<?php if ($produit->getTav()): ?>
				<tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->getPremix()): ?>
				<premix>true</premix>
<?php endif; ?>
<?php if ($produit->getObservations()): ?>
				<observations><![CDATA[<?php echo $produit->getObservations() ?>]]></observations>
<?php endif; ?>
				<balance-stocks>
<?php
	$xml = '';
	noeudXml($produit, $ciel->get('balance-stocks/'.$drm->getCielLot().'/droits-acquittes'), $xml, array('mois', 'annee'));
	echo formatXml($xml, 5);
?>
				</balance-stocks>
			</produit>
<?php endforeach; endif; ?>
			<stockEpuise><?php echo (!$drm->getTotalStockAcq() && !$drm->canSetStockDebutMois(true))? "true" : "false"; ?></stockEpuise>
    	</droits-acquittes>
<?php endif; ?>
<?php endif; ?>
<?php if ($drm->exist('crds') && $drm->crds): foreach(drm2CrdCiel($drm) as $gcrds): ?>
    	<compte-crd>
      		<categorie-fiscale-capsules><?php echo $gcrds[0]->categorie->code ?></categorie-fiscale-capsules>
      		<type-capsule><?php echo $gcrds[0]->type->code ?></type-capsule>
<?php foreach($gcrds as $crd) : ?>
      		<centilisation volume="<?php echo $crd->centilisation->code ?>"<?php if ($crd->centilisation->centilitre): ?> volumePersonnalise="<?php echo $crd->centilisation->centilitre ?>" bib="<?php echo ($crd->centilisation->bib)? 1 : 0; ?>"<?php endif; ?>>
        		<stock-debut-periode><?php echo $crd->total_debut_mois ?></stock-debut-periode>
<?php if ($crd->entrees->achats || $crd->entrees->excedents || $crd->entrees->retours || $crd->entrees->autres): ?>
        		<entrees-capsules>
<?php if ($crd->entrees->achats): ?>
				<achats><?php echo $crd->entrees->achats ?></achats>
<?php endif; ?>
<?php if ($crd->entrees->excedents): ?>
				<excedents><?php echo $crd->entrees->excedents ?></excedents>
<?php endif; ?>
<?php if ($crd->entrees->retours): ?>
				<retours><?php echo $crd->entrees->retours ?></retours>
<?php endif; ?>
<?php if ($crd->entrees->autres): ?>
				<autres-entrees><?php echo $crd->entrees->autres ?></autres-entrees>
<?php endif; ?>
        		</entrees-capsules>
<?php endif; ?>
<?php if ($crd->sorties->utilisees || $crd->sorties->detruites || $crd->sorties->manquantes || $crd->sorties->autres): ?>
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
<?php if ($crd->sorties->autres): ?>
				<autres-sorties><?php echo $crd->sorties->autres ?></autres-sorties>
<?php endif; ?>
        		</sorties-capsules>
<?php endif; ?>
        		<stock-fin-periode><?php echo $crd->total_fin_mois ?></stock-fin-periode>
<?php if ($crd->observations): ?>
						<observations><?php echo $crd->observations ?></observations>
<?php endif ?>
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
            <?php if($rna[DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTACCISEDEST]): ?>
      		<numero-accise-destinataire><?php echo $rna[DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTACCISEDEST] ?></numero-accise-destinataire>
            <?php endif; ?>
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
  	</declaration-recapitulative>
</message-interprofession>
