#!/bin/bash

. bin/config.inc


TMPE="$TMP/export_pennylane_civp"
LATEX="data/latex"

rm -rf $TMPE 2> /dev/null
mkdir -p $TMPE $TMPE/pdf

EXPORT_SOCIETE_ONLY_FACTURES=1 php symfony export:societe $SYMFONYTASKOPTIONS --interpro="INTERPRO-CIVP" > $TMPE/societes.csv

echo "Identifiant Client;Dénomination;Siren;Numéro de Compte;Adresse;Code Postal;Ville;Pays;Liste d’e-mails;Téléphone;Référence Client;Destinataire;Remarques;Numéro de TVA;Langue de Facturation;Conditions de Paiement;Prélèvement : Numéro de Mandat (RUM);Prélèvement : Date de Signature;Prélèvement : Type de Paiement;Prélèvement : IBAN;Prélèvement : BIC;Prélèvement : Banque;Virement : IBAN;Virement : BIC;Virement : Banque;ID du mandat de GoCardless" > $TMPE/societesPennylane.csv
awk -F";" -v OFS=';' 'function esc(v) { gsub(/"/, "", v); gsub(/;/, ",", v); sub(/^[[:space:]]+/, "", v); sub(/[[:space:]]+$/, "", v); return v; } function escBanque(v) { gsub(/[\047]/, " ", v); return v; } function escTVA(v) {  return (tolower(v) ~ /^fr[[:digit:]]{11}$/) ? v : "" } function generateCompte(v) { sub(/^CIVP/, "4110", v); return v; } NR>1 {print $1,esc($2),($12 ? substr($12, 1, 9) : ""), generateCompte($1),(esc($5) ? esc($5) : esc($2)),$7,esc($8),$9,$17,$15,$1,"","",escTVA($11),"fr_FR","Paiement sous 60 jours",($24 ? $1 : ""),$14,"Récurrent",$24,$23,escBanque(esc($21)),($24 ? "" : "FR7619106000214351565839549"),($24 ? "" : "AGRIFRPP891"),($24 ? "" : "CREDIT AGRICOLE PROVENCE COTE AZUR"),""}' $TMPE/societes.csv >> $TMPE/pennylane.societes.csv

echo "Identifiant Client;Dénomination;Numéro de mandat (RUM);Date de signature;Type de paiement;IBAN;BIC;Banque" > $TMPE/pennylane.mandatssepa.csv
awk -F";" -v OFS=';' 'NR>1 && $20 {print $1,$2,$17,$18,$19,$20,$21,$22}' $TMPE/pennylane.societes.csv >> $TMPE/pennylane.mandatssepa.csv

php symfony export:facture $SYMFONYTASKOPTIONS --interpro="INTERPRO-CIVP" > $TMPE/factures.csv

echo "Date;Code Journal;Numéro de Compte;Libellé de compte;Libellé de ligne;Taux de TVA du compte;Code pays du compte;Libellé de pièce;Numéro de pièce;Débit et/ou Crédit;Crédit;Famille de catégories;Catégorie;Identifiant de ligne;Identifiant de lettrage" > $TMPE/pennylane.factures.csv
awk -F ";" -v OFS=';' 'function esc(v) { gsub(/-/, "", v); return v; } function generateCompte(v) { sub(/^CIVP/, "4110", v); return v; } NR>1 {print $2, $1, ($15 == "ECHEANCE") ? generateCompte($7) : $6, $16, $15,"20%","FR", $5, $4, ($10 == "DEBIT") ? esc($11) : "", ($10 == "CREDIT") ? esc($11) : "","" , $19,"",""
}' $TMPE/factures.csv >> $TMPE/pennylane.factures.csv

cat $TMPE/factures.csv | awk -F ';' '{print $14}' | sort | uniq | grep 2[0-9][0-9][0-9] | while read FACTUREID; do
    php symfony facture:setexported $SYMFONYTASKOPTIONS $FACTUREID;
done


cat $TMPE/factures.csv | grep ";ECHEANCE;" | while read line; do
    numfacture=$(echo "$line" | cut -d";" -f4)
    factureid=$(echo "$line" | cut -d";" -f14)
    date=$(echo "$factureid" | tail -c11)

    pdf=$(ls -t "$LATEX" 2>/dev/null | grep "${numfacture}_${date}" | head -n1)

    if [ -z "$pdf" ]; then
        php symfony generate:AFacture $SYMFONYTASKOPTIONS --directory="/" "$factureid" > /dev/null
        pdf=$(ls -t "$LATEX" | grep "${numfacture}_${date}" | head -n1)
    fi

    cp "$LATEX/$pdf" "$TMPE/pdf/${numfacture}.pdf"
done

zip -rjq $TMPE/pennylane.factures.zip $TMPE/pdf

echo "$TMPE/pennylane.societes.csv|pennylane.societes.csv|Export Pennylane des sociétés"
echo "$TMPE/pennylane.mandatssepa.csv|pennylane.mandatssepa.csv|Export Pennylane des mandats sepa"
echo "$TMPE/pennylane.factures.csv|pennylane.factures.csv|Export Pennylane des factures"
if [ -f "$TMPE/pennylane.factures.zip" ]; then
echo "$TMPE/pennylane.factures.zip|pennylane.factures.zip|PDF des factures"
fi
echo "$TMPE/societes.csv|societes.csv|Export CSV des sociétés"
echo "$TMPE/factures.csv|factures.csv|Export CSV des factures"
