#!/bin/bash

cd $(dirname $0)/..

if [ "$#" -ne 1 ]; then
    echo "1 argument attendus : $0 dossier_cible" >&2
    exit 1
fi

if [ ! -d "$1" ]; then
    echo "$1 n'est pas un dossier valide ou n'existe pas." >&2
    exit 1
fi

dir="${1%/}"
dirname=$(basename "$dir")
identifiant=$(echo "$dirname" | cut -d '_' -f2)

php symfony cravate-api:suivi-reserve $identifiant > "$dir/00_metas.json"

echo "fichier créé avec succès : $dir/00_metas.json"
