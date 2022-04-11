#!/bin/bash

. bin/config.inc


php symfony export:facture $SYMFONYTASKOPTIONS > $TMP/factures.csv
cat $TMP/factures.csv | perl bin/convertExportFacture2SAGE.pl | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/factures.txt

echo -n > $TMP/factures.sage
echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/factures.sage
echo "#VER 18" | sed 's/$/\r/' >> $TMP/factures.sage
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/factures.sage
cat $TMP/factures.txt >> $TMP/factures.sage
echo "#FIN" | sed 's/$/\r/' >> $TMP/factures.sage

cat $TMP/factures.csv | awk -F ';' '{print $14}' | sort | uniq | grep 2[0-9][0-9][0-9] | while read FACTUREID; do
    php symfony facture:setexported $SYMFONYTASKOPTIONS $FACTUREID;
done

php symfony export:facture-paiements $SYMFONYTASKOPTIONS > $TMP/paiements.csv
cat $TMP/paiements.csv | perl bin/convertExportPaiement2SAGE.pl | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/paiements.txt

echo -n > $TMP/paiements.sage
echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/paiements.sage
echo "#VER 6" | sed 's/$/\r/' >> $TMP/paiements.sage
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/paiements.sage
cat $TMP/paiements.txt >> $TMP/paiements.sage
echo "#FIN" | sed 's/$/\r/' >> $TMP/paiements.sage

cat $TMP/paiements.csv | awk -F ';' '{print $12}' | sort | uniq | grep 2[0-9][0-9][0-9] | while read FACTUREID; do
    php symfony paiements:setexported $SYMFONYTASKOPTIONS $FACTUREID;
done

php symfony paiements:generate-remises $SYMFONYTASKOPTIONS --filename="$TMP/paiements.pdf" $TMP/paiements.csv

echo "$TMP/factures.sage|factures.sage|Export SAGE des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"
echo "$TMP/paiements.sage|paiements.sage|Export SAGE des paiements"
echo "$TMP/paiements.csv|paiements.csv|Export CSV des paiements"
echo "$TMP/paiements.pdf|paiements.pdf|Bordereaux de remise"
