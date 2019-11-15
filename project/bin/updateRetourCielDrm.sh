#!/bin/bash
. $(dirname $0)/config.inc

INTERPRO=$1
NUMEROACCISE=$2
PERIODE=$3
DRM=$4

ANNEE=$(echo "$PERIODE" | cut -d '-' -f 1)
MOIS=$(echo "$PERIODE" | cut -d '-' -f 2)

if [[ $INTERPRO = "IR" ]]; then
	URL=$(echo "$STATSCIEL_XML_IR" | sed 's/?format=xml//')
fi
if [[ $INTERPRO = "CIVP" ]]; then
	URL=$(echo "$STATSCIEL_XML_CIVP" | sed 's/?format=xml//')
fi

URL="$URL$ANNEE/$MOIS/?format=xml&accise=$NUMEROACCISE"

cd $WORKINGDIR;

php symfony ciel:drm-cft $URL $DRM --interpro="$INTERPRO"