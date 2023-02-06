#!/bin/bash

. bin/config.inc


TMPE="$TMP/export_exantis"
LATEX="data/latex"

rm -rf $TMP 2> /dev/null
mkdir -p $TMPE $TMPE/pdf


php symfony export:facture $SYMFONYTASKOPTIONS > $TMPE/factures.csv
cat $TMPE/factures.csv | php bin/convertExportFacture2Exantis.php > $TMPE/factures.json

cat $TMPE/factures.csv | awk -F ';' '{print $14}' | sort | uniq | grep 2[0-9][0-9][0-9] | while read FACTUREID; do
    php symfony facture:setexported $SYMFONYTASKOPTIONS $FACTUREID;
done

cat $TMPE/factures.csv|grep ";ECHEANCE;"|while read line; do cp $LATEX/$(ls $LATEX/|grep $(echo $line|cut -d";" -f4)|tail -n1) $TMPE/pdf/$(echo $line|cut -d";" -f4).pdf; done

zip -r $TMPE/factures.zip $TMPE/pdf

echo "$TMPE/factures.json|factures.json|Export JSON des factures"
echo "$TMPE/factures.csv|factures.csv|Export CSV des factures"
echo "$TMPE/factures.zip|factures.zip|PDF des factures"
