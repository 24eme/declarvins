#!/bin/bash

. bin/config.inc


TMPE="$TMP/export_exantis"
LATEX="data/latex"

rm -rf $TMPE 2> /dev/null
mkdir -p $TMPE $TMPE/pdf


php symfony export:facture $SYMFONYTASKOPTIONS --interpro="INTERPRO-IR" > $TMPE/factures.csv

cat $TMPE/factures.csv | php bin/convertExportFacture2Exantis.php CONFIGURATION-PRODUITS-IR-20240101 > $TMPE/factures.json

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


zip -rj $TMPE/factures.zip $TMPE/pdf

php symfony export:mvts-enattentes $SYMFONYTASKOPTIONS --interpro="INTERPRO-IR" > $TMPE/reliquats-drm.csv
cat $TMPE/reliquats-drm.csv | php bin/convertExportFacture2Exantis.php > $TMPE/reliquats-drm.json

echo "$TMPE/factures.json|factures.json|Export JSON des factures"
echo "$TMPE/factures.csv|factures.csv|Export CSV des factures"
if [ -f "$TMPE/factures.zip" ]; then
echo "$TMPE/factures.zip|factures.zip|PDF des factures"
fi
echo "$TMPE/reliquats-drm.json|reliquats-drm.json|Export JSON des reliquats DRM"
echo "$TMPE/reliquats-drm.csv|reliquats-drm.csv|Export CSV des reliquats DRM"
