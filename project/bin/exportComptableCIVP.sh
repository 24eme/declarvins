#!/bin/bash

. bin/config.inc


TMPE="$TMP/export_exantis_civp"
LATEX="data/latex"

rm -rf $TMPE 2> /dev/null
mkdir -p $TMPE $TMPE/pdf


php symfony export:facture $SYMFONYTASKOPTIONS --interpro="INTERPRO-CIVP" > $TMPE/factures.csv

cat $TMPE/factures.csv | php bin/convertExportFacture2Exantis.php CONFIGURATION-PRODUITS-CIVP-20200801 > $TMPE/factures.json

cat $TMPE/factures.csv | awk -F ';' '{print $14}' | sort | uniq | grep 2[0-9][0-9][0-9] | while read FACTUREID; do
    php symfony facture:setexported $SYMFONYTASKOPTIONS $FACTUREID;
done


cat $TMPE/factures.csv | grep ";ECHEANCE;" | while read line; do
    numfacture=$(echo "$line" | cut -d";" -f4)
    factureid=$(echo "$line" | cut -d";" -f14)
    date=$(echo "$factureid" | tail -c11)

    pdf=$(ls -t "$LATEX" 2>/dev/null | grep "${numfacture}_${date}" | head -n1)

    if [ -z "$pdf" ]; then
        php symfony generate:AFacture $SYMFONYTASKOPTIONS --directory="/" "$factureid"
        pdf=$(ls -t "$LATEX" | grep "${numfacture}_${date}" | head -n1)
    fi

    if [ -n "$pdf" ]; then
        cp "$LATEX/$pdf" "$TMPE/pdf/${numfacture}.pdf"
    fi
done

zip -rjq $TMPE/factures.zip $TMPE/pdf


echo "$TMPE/factures.json|factures.json|Export JSON des factures"
echo "$TMPE/factures.csv|factures.csv|Export CSV des factures"
echo "$TMPE/factures.zip|factures.zip|PDF des factures"
