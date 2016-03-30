<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>

<message-interprofession>
	<siren-interprofession>str123400</siren-interprofession>
	<declaration-recapitulative>
		<identification-declarant>
			<numero-agrement><?php echo $drm->declarant->no_accises ?></numero-agrement>
			<numero-cvi><?php echo $drm->declarant->cvi ?></numero-cvi>
		</identification-declarant>
		<periode>
			<mois><?php echo $drm->getMois() ?></mois>
			<annee><?php echo $drm->getAnnee() ?></annee>
		</periode>
		<declaration-neant><?php echo (int)$drm->declaration->hasStockEpuise(); ?></declaration-neant>
<?php if (!$drm->declaration->hasStockEpuise()): ?>
		<droits-suspendus>
<?php foreach ($drm->getDetails() as $produit): ?>
			<produit>
				<libelle-fiscal>!!</libelle-fiscal>
				<code-inao>!!</code-inao>
				<libelle-personnalise><?php echo trim($produit->getLibelle()) ?></libelle-personnalise>
<?php if($produit->tav): ?>
				<tav><?php echo sprintf("%.2f", $produit->tav) ?></tav>
<?php endif; ?>
				<premix>!!</premix>
<?php if($produit->observations): ?>
				<observations><?php echo $produit->observations ?></observations>
<?php endif; ?>
				<balance-stocks>
					<stock-debut-periode>
						<stock><?php echo sprintf("%.2f", $produit->total_debut_mois) ?></stock>
<?php if($produit->stocks_debut->warrante): ?>
						<stock-warrante><?php echo sprintf("%.2f", $produit->stocks_debut->warrante) ?></stock-warrante>
<?php endif; ?>
					</stock-debut-periode>
<?php if ($produit->total_entrees): ?>
					<entrees-periode>
<?php if($produit->entrees->recolte): ?>
						<volume-produit><?php echo sprintf("%.2f", $produit->entrees->recolte) ?></volume-produit>
<?php endif; ?>
<?php if($produit->entrees->achat): ?>
						<achats-reintegrations><?php echo sprintf("%.2f", $produit->entrees->achat) ?></achats-reintegrations>
<?php endif; ?>
<?php if ($produit->entrees->embouteillage || $produit->entrees->mouvement || $produit->entrees->travail || $produit->entrees->distillation): ?>
						<mouvements-temporaires>
<?php if ($produit->entrees->embouteillage): ?>
							<embouteillage><?php echo sprintf("%.2f", $produit->entrees->embouteillage) ?></embouteillage>
<?php endif; ?>
<?php if ($produit->entrees->mouvement): ?>
							<relogement><?php echo sprintf("%.2f", $produit->entrees->mouvement) ?></relogement>
<?php endif; ?>
<?php if ($produit->entrees->travail): ?>
							<travail-a-facon><?php echo sprintf("%.2f", $produit->entrees->travail) ?></travail-a-facon>
<?php endif; ?>
<?php if ($produit->entrees->distillation): ?>
							<distillation-a-facon><?php echo sprintf("%.2f", $produit->entrees->distillation) ?></distillation-a-facon>
<?php endif; ?>
						</mouvements-temporaires>
<?php endif; ?>
<?php if ($produit->entrees->repli || $produit->entrees->declassement || $produit->entrees->manipulation || $produit->entrees->vci): ?>
						<mouvements-internes>
<?php if ($produit->entrees->repli || $produit->entrees->declassement): ?>
							<replis-declassement-transfert-changement-appellation><?php echo sprintf("%.2f", ($produit->entrees->repli+$produit->entrees->declassement)) ?></replis-declassement-transfert-changement-appellation>
<?php endif; ?>
<?php if ($produit->entrees->manipulation): ?>
							<manipulations><?php echo sprintf("%.2f", $produit->entrees->manipulation) ?></manipulations>
<?php endif; ?>
<?php if ($produit->entrees->vci): ?>
							<integration-vci-agree><?php echo sprintf("%.2f", $produit->entrees->vci) ?></integration-vci-agree>
<?php endif; ?>
						</mouvements-internes>
<?php endif; ?>
						<compensation>!!</compensation>
						<autres-entrees>!!</autres-entrees>
<?php if ($produit->entrees->crd): ?>
						<replacement-suspension>
							<mois>!!</mois>
							<annee>!!</annee>
							<volume><?php echo sprintf("%.2f", $produit->entrees->crd) ?></volume>
						</replacement-suspension>
<?php endif; ?>
					</entrees-periode>
<?php endif; ?>
<?php if ($produit->total_sorties): ?>
					<sorties-periode>
						<ventes-france-crd-suspendus>
							<annee-precedente>!!</annee-precedente>
							<annee-courante>!!</annee-courante>
						</ventes-france-crd-suspendus>
						<ventes-france-crd-acquittes>!!</ventes-france-crd-acquittes>
						<sorties-sans-paiement-droits>
							<sorties-definitives>!!</sorties-definitives>
<?php if ($produit->sorties->consommation): ?>
							<consommation-familiale-degustation><?php echo sprintf("%.2f", $produit->sorties->consommation) ?></consommation-familiale-degustation>
<?php endif; ?>
<?php if ($produit->sorties->embouteillage || $produit->sorties->mouvement || $produit->sorties->travail || $produit->sorties->distillation): ?>
							<mouvements-temporaires>
<?php if ($produit->sorties->embouteillage): ?>
								<embouteillage><?php echo sprintf("%.2f", $produit->sorties->embouteillage) ?></embouteillage>
<?php endif; ?>
<?php if ($produit->sorties->mouvement): ?>
								<relogement><?php echo sprintf("%.2f", $produit->sorties->mouvement) ?></relogement>
<?php endif; ?>
<?php if ($produit->sorties->travail): ?>
								<travail-a-facon><?php echo sprintf("%.2f", $produit->sorties->travail) ?></travail-a-facon>
<?php endif; ?>
<?php if ($produit->sorties->distillation): ?>
								<distillation-a-facon><?php echo sprintf("%.2f", $produit->sorties->distillation) ?></distillation-a-facon>
<?php endif; ?>
							</mouvements-temporaires>
<?php endif; ?>
<?php if ($produit->sorties->repli || $produit->sorties->declassement || $produit->sorties->vci || $produit->sorties->autres_interne || 1 == 1): ?>
							<mouvements-internes>
<?php if ($produit->sorties->repli || $produit->sorties->declassement): ?>
								<replis-declassement-transfert-changement-appellation><?php echo sprintf("%.2f", ($produit->sorties->repli+$produit->sorties->declassement)) ?></replis-declassement-transfert-changement-appellation>
<?php endif; ?>
								<fabrication-autre-produit>!!</fabrication-autre-produit>
<?php if ($produit->sorties->vci): ?>
								<revendication-vci><?php echo sprintf("%.2f", $produit->sorties->vci) ?></revendication-vci>
<?php endif; ?>
<?php if ($produit->sorties->autres_interne): ?>
								<autres-mouvements-internes><?php echo sprintf("%.2f", $produit->sorties->autres_interne) ?></autres-mouvements-internes>
<?php endif; ?>
							</mouvements-internes>
<?php endif; ?>
							<autres-sorties>!!</autres-sorties>
						</sorties-sans-paiement-droits>
					</sorties-periode>
<?php endif; ?>
					<stock-fin-periode>
						<stock><?php echo sprintf("%.2f", $produit->total) ?></stock>
<?php if ($produit->stocks_fin->warrante): ?>
						<stock-warrante><?php echo sprintf("%.2f", $produit->stocks_fin->warrante) ?></stock-warrante>
<?php endif; ?>
					</stock-fin-periode>
				</balance-stocks>
			</produit>
<?php endforeach; ?>
			<stockEpuise>!!</stockEpuise>
		</droits-suspendus>
<?php if ($drm->hasExportableProduitsAcquittes()): ?>
		<droits-acquittes>
<?php foreach ($drm->getDetails() as $produit): ?>
		<produit>
			<libelle-fiscal>!!</libelle-fiscal>
			<code-inao>!!</code-inao>
			<libelle-personnalise><?php echo $produit->getLibelle() ?></libelle-personnalise>
<?php if($produit->tav): ?>
			<tav><?php echo sprintf("%.2f", $produit->tav) ?></tav>
<?php endif; ?>
			<premix>!!</premix>
<?php if($produit->observations): ?>
				<observations><?php echo $produit->observations ?></observations>
<?php endif; ?>
			<balance-stocks>
  				<stock-debut-periode><?php echo sprintf("%.2f", $produit->acq_total_debut_mois) ?></stock-debut-periode>
<?php if ($produit->entrees->acq_achat || $produit->entrees->acq_autres): ?>
  				<entrees-periode>
<?php if ($produit->entrees->acq_achat): ?>
					<achats><?php echo sprintf("%.2f", $produit->entrees->acq_achat) ?></achats>
<?php endif; ?>
<?php if ($produit->entrees->acq_autres): ?>
					<autres-entrees><?php echo sprintf("%.2f", $produit->entrees->acq_autres) ?></autres-entrees>
<?php endif; ?>
  				</entrees-periode>
<?php endif; ?>
<?php if ($produit->sorties->acq_crd || $produit->sorties->acq_replacement || $produit->sorties->acq_autres): ?>
  				<sorties-periode>
<?php if ($produit->sorties->acq_crd): ?>
					<ventes><?php echo sprintf("%.2f", $produit->sorties->acq_crd) ?></ventes>
<?php endif; ?>
<?php if ($produit->sorties->acq_replacement): ?>
					<replacement-suspension><?php echo sprintf("%.2f", $produit->sorties->acq_replacement) ?></replacement-suspension>
<?php endif; ?>
<?php if ($produit->sorties->acq_autres): ?>
					<autres-sorties><?php echo sprintf("%.2f", $produit->sorties->acq_autres) ?></autres-sorties>
<?php endif; ?>
  				</sorties-periode>
<?php endif; ?>
  				<stock-fin-periode><?php echo sprintf("%.2f", $produit->acq_total) ?></stock-fin-periode>
			</balance-stocks>
		</produit>
<?php endforeach ?>
      		<stockEpuise>!!</stockEpuise>
    	</droits-acquittes>
<?php endif; ?>
<?php endif; ?>
<?php if ($drm->exist('crds') && $drm->crds): foreach($drm->crds as $crd): ?>
    	<compte-crd>
      		<categorie-fiscale-capsules><?php echo $crd->categorie->code ?></categorie-fiscale-capsules>
      		<type-capsule><?php echo $crd->type->code ?></type-capsule>
      		<centilisation volume="<?php echo $crd->centilisation->code ?>">
        		<stock-debut-periode><?php echo $crd->total_fin_mois ?></stock-debut-periode>
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
        		<stock-fin-periode><?php echo $crd->total_debut_mois ?></stock-fin-periode>
      		</centilisation>
    	</compte-crd>
<?php endforeach; endif; ?>
<?php if (($drm->declaratif->empreinte->debut && $drm->declaratif->empreinte->fin) || ($drm->declaratif->daa->debut && $drm->daa->fin) || ($drm->declaratif->dsa->debut && $drm->declaratif->dsa->fin)): ?>
    	<document-accompagnement>
<?php if ($drm->declaratif->empreinte->debut && $drm->declaratif->empreinte->fin): ?>
      		<numero-empreintes>
        		<debut-periode><?php echo $drm->declaratif->empreinte->debut ?></debut-periode>
        		<fin-periode><?php echo $drm->declaratif->empreinte->fin ?></fin-periode>
      		</numero-empreintes>
<?php endif; ?>
<?php if ($drm->declaratif->daa->debut && $drm->declaratif->daa->fin): ?>
      		<daa-dca>
        		<debut-periode><?php echo $drm->declaratif->daa->debut ?></debut-periode>
        		<fin-periode><?php echo $drm->declaratif->daa->fin ?></fin-periode>
      		</daa-dca>
<?php endif; ?>
<?php if ($drm->declaratif->dsa->debut && $drm->declaratif->dsa->fin): ?>
      		<dsa-dsac>
        		<debut-periode><?php echo $drm->declaratif->dsa->debut ?></debut-periode>
        		<fin-periode><?php echo $drm->declaratif->dsa->fin ?></fin-periode>
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
