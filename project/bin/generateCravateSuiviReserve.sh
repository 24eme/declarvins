#!/bin/bash

if [ "$#" -ne 2 ]; then
    echo "2 arguments attendus : $0 dossier_cible identifiant" >&2
    exit 1
fi

mkdir -p "$1/$2/"

php symfony cravate-api:suivi-reserve $2 > "$1/$2/form.json"

echo "fichier créé avec succès : $1/$2/form.json"
