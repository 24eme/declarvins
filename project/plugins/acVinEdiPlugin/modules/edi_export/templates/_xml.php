<?php use_helper('Edi'); ?>
<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<message-interprofession xmlns="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0 echanges-interprofession-1.7.xsd">
	<siren-interprofession><?php echo $drm->getEtablissement()->getInterproObject()->siren ?></siren-interprofession>
	<declaration-recapitulative>
		<identification-declarant>
			<numero-agrement><?php echo $drm->declarant->no_accises ?></numero-agrement>
			<numero-cvi><?php echo $drm->declarant->cvi ?></numero-cvi>
		</identification-declarant>
		<periode>
			<mois><?php echo $drm->getMois() ?></mois>
			<annee><?php echo $drm->getAnnee() ?></annee>
		</periode>
		<declaration-neant><?php echo ($drm->declaration->hasStockEpuise())? "true" : "false"; ?></declaration-neant>
<?php if (!$drm->declaration->hasStockEpuise()): ?>
		<droits-suspendus>
<?php foreach ($drm->getExportableProduits() as $produit): ?>
			<produit>
<?php if ($produit->getLibelleFiscal()): ?>
				<libelle-fiscal><?php echo $produit->getLibelleFiscal() ?></libelle-fiscal>
<?php endif; ?>
<?php if ($produit->getInao()): ?>
				<code-inao><?php echo $produit->getInao() ?></code-inao>
<?php endif; ?>
				<libelle-personnalise><?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES | ENT_HTML401)) ?></libelle-personnalise>
<?php if ($produit->getTav()): ?>
				<tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->getPremix()): ?>
				<premix>true</premix>
<?php endif; ?>
<?php if ($produit->getObservations()): ?>
				<observations><?php echo $produit->getObservations() ?></observations>
<?php endif; ?>
				<balance-stocks>
<?php 
	$xml = '';
	noeudXml($produit, $ciel->get('balance-stocks/droits-suspendus'), $xml, array('mois', 'annee'));
	echo formatXml($xml, 5);
?>
				</balance-stocks>
			</produit>
<?php endforeach; ?>
			<stockEpuise><?php echo (!$drm->getTotalStock())? "true" : "false"; ?></stockEpuise>
		</droits-suspendus>
<?php if ($drm->hasExportableProduitsAcquittes()): ?>
		<droits-acquittes>
<?php foreach ($drm->getExportableProduits() as $produit): if (!$produit->getHasSaisieAcq()) { continue; } ?>
			<produit>
<?php if ($produit->getLibelleFiscal()): ?>
				<libelle-fiscal><?php echo $produit->getLibelleFiscal() ?></libelle-fiscal>
<?php endif; ?>
<?php if ($produit->getInao()): ?>
				<code-inao><?php echo $produit->getInao() ?></code-inao>
<?php endif; ?>
				<libelle-personnalise><?php echo trim(html_entity_decode($produit->getLibelle(), ENT_QUOTES | ENT_HTML401)) ?></libelle-personnalise>
<?php if ($produit->getTav()): ?>
				<tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->getPremix()): ?>
				<premix>true</premix>
<?php endif; ?>	
<?php if ($produit->getObservations()): ?>
				<observations><?php echo $produit->getObservations() ?></observations>
<?php endif; ?>
				<balance-stocks>
<?php 
	$xml = '';
	noeudXml($produit, $ciel->get('balance-stocks/droits-acquittes'), $xml, array('mois', 'annee'));
	echo formatXml($xml, 5);
?>
				</balance-stocks>
			</produit>
<?php endforeach; ?>
			<stockEpuise><?php echo (!$drm->getTotalStockAcq())? "true" : "false"; ?></stockEpuise>
    	</droits-acquittes>
<?php endif; ?>
<?php endif; ?>
<?php if ($drm->exist('crds') && $drm->crds): foreach(drm2CrdCiel($drm) as $gcrds): ?>
    	<compte-crd>
      		<categorie-fiscale-capsules><?php echo $gcrds[0]->categorie->code ?></categorie-fiscale-capsules>
      		<type-capsule><?php echo $gcrds[0]->type->code ?></type-capsule>
<?php foreach($gcrds as $crd) : ?>
      		<centilisation volume="<?php echo $crd->centilisation->code ?>">
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
        		<nombre-document-empreinte><?php echo $drm->declaratif->empreinte->nb ?></nombre-document-empreinte>
      		</daa-dca>
<?php endif; ?>
<?php if ($drm->declaratif->dsa->debut && $drm->declaratif->dsa->fin): ?>
      		<dsa-dsac>
        		<debut-periode><?php echo $drm->declaratif->dsa->debut ?></debut-periode>
        		<fin-periode><?php echo $drm->declaratif->dsa->fin ?></fin-periode>
        		<nombre-document-empreinte><?php echo $drm->declaratif->empreinte->nb ?></nombre-document-empreinte>
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
  	</declaration-recapitulative>
</message-interprofession>
