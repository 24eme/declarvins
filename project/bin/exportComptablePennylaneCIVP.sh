#!/bin/bash

. bin/config.inc


TMPE="$TMP/export_pennylane_civp"
LATEX="data/latex"

rm -rf $TMPE 2> /dev/null
mkdir -p $TMPE $TMPE/pdf

EXPORT_SOCIETE_ONLY_FACTURES=1 php symfony export:societe $SYMFONYTASKOPTIONS --interpro="INTERPRO-CIVP" > $TMPE/societes.csv

echo "Identifiant Client;Dénomination;Siren;Numéro de Compte;Adresse;Code Postal;Ville;Pays;Liste d’e-mails;Téléphone;Référence Client;Destinataire;Remarques;Numéro de TVA;Langue de Facturation;Conditions de Paiement;Prélèvement : Numéro de Mandat (RUM);Prélèvement : Date de Signature;Prélèvement : Type de Paiement;Prélèvement : IBAN;Prélèvement : BIC;Prélèvement : Banque;Virement : IBAN;Virement : BIC;Virement : Banque;ID du mandat de GoCardless" > $TMPE/societesPennylane.csv
awk -F";" -v OFS=';' 'function esc(v) { gsub(/"/, "", v); gsub(/;/, ",", v); sub(/^[[:space:]]+/, "", v); sub(/[[:space:]]+$/, "", v); return v; } function escBanque(v) { gsub(/[\047]/, " ", v); return v; } function escTVA(v) {  return (tolower(v) ~ /^fr[[:digit:]]{11}$/) ? v : "" } function generateCompte(v) { sub(/^CIVP/, "4110", v); return v; } NR>1 {print $1,esc($2),($12 ? substr($12, 1, 9) : ""), generateCompte($1),(esc($5) ? esc($5) : esc($2)),$7,esc($8),$9,$17,$15,$1,"","",escTVA($11),"fr_FR","Paiement sous 60 jours",($24 ? $1 : ""),$14,"Récurrent",$24,$23,escBanque(esc($21)),($24 ? "" : "FR7619106000214351565839549"),($24 ? "" : "AGRIFRPP891"),($24 ? "" : "CREDIT AGRICOLE PROVENCE COTE AZUR"),""}' $TMPE/societes.csv >> $TMPE/societesPennylane.csv


php symfony export:facture $SYMFONYTASKOPTIONS --interpro="INTERPRO-CIVP" > $TMPE/factures.csv

echo "Date;Code Journal;Numéro de Compte;Libellé de compte;Libellé de ligne;Taux de TVA du compte;Code pays du compte;Libellé de pièce;Numéro de pièce;Débit et/ou Crédit;Crédit;Famille de catégories;Catégorie;Identifiant de ligne;Identifiant de lettrage" > $TMPE/ecritures_facturesPennylane.csv
awk -F ";" -v OFS=';' 'function esc(v) { gsub(/-/, "", v); return v; } function generateCompte(v) { sub(/^CIVP/, "4110", v); sub(/^C/, "4110", v); return v; } NR>1 {print $2, $1, ($15 == "ECHEANCE") ? generateCompte($7) : $6, $16, $15,"20%","FR", $5, $4, ($10 == "DEBIT") ? esc($11) : "", ($10 == "CREDIT") ? esc($11) : "","" , $19, $20, $25,""
}' $TMPE/factures.csv >> $TMPE/ecritures_facturesPennylane.csv
