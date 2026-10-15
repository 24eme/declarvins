#!/bin/bash

target="$1"

for f in "$target"/*/bilan.csv; do
    awk -F';' 'BEGIN { OFS=";" }
    NR == 1 {
        for (i=1; i<=NF; i++)
            header[i]=$i

        for (i=1; i<=18; i++) {
            printf "%s%s", (i==1 ? "" : OFS), header[i]
        }
        print OFS "Période" OFS "Statut"
        next
    }
    NF > 0 {
        for (i=19; i<=NF; i++) {
            ligne=""

            for (j=1; j<=18; j++)
                ligne = ligne (j==1 ? "" : OFS) $j

            print ligne, header[i], $i
        }
    }
    ' "$f" > "${f%/*}/bilan_ligne.csv"
done

first=1
for f in "$target"/*/bilan_ligne.csv; do
    if [ "$first" -eq 1 ]; then
        head -n 1 "$f" > "$target/bilan_total.csv"
        first=0
      fi
        tail -n +2 "$f" >> "$target/bilan_total.csv"
done

rm -f "$target"/*/bilan_ligne.csv
