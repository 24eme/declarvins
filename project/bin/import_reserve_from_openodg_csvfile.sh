#!/bin/bash
. bin/config.inc

if [[ $# -ne 2 ]]; then
    echo "Arguments manquants : $0 <interpro> <openodg_reserve_csvfile>"
    exit 1
fi

interpro="$1"
csvfile="$2"

if [[ -z "$csvfile" ]]; then
    echo "Erreur : aucun fichier spécifié"
    exit 1
fi

if [[ ! -f "$csvfile" ]]; then
    echo "Erreur : le fichier '$csvfile' n'existe pas"
    exit 1
fi

TMPI=$TMP/$interpro
mkdir -p $TMPI

awk -F';' -v OFS=';' 'NR > 1 {
sub("CDP", "CP", $15)
hash = "declaration/certifications/" $11 "/genres/" $13 "/appellations/" $15 "/mentions/DEFAUT/lieux/" $17 "/couleurs/" $19 "/cepages/" $21
print $3, $2, hash, substr($1, 1, 4), $27
}' "$csvfile" > "$TMPI/reserves.csv"

millesime=$(cat /tmp/declarvins_prod/CIVP/reserves.csv|cut -d';' -f4|sort|uniq|tail -n1)

php symfony import:reserve-interpro $SYMFONYTASKOPTIONS --interpro="$interpro" --forceImport="0" --filtreMillesime="$millesime" "$TMPI/reserves.csv"

rm "$TMPI/reserves.csv"
