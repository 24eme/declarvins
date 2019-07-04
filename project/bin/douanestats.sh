#!/bin/bash
. bin/config.inc

INTERPRO=$1

if [[ -f /tmp/bilans.json ]]; then
	rm -f /tmp/bilans.json
fi
if [[ -f /tmp/grc.csv ]]; then
	rm -f /tmp/grc.csv
fi
if [[ -f /tmp/drms.xml ]]; then
	rm -f /tmp/drms.xml
fi

wget -q -O /tmp/bilans.json "$STATSCIEL_BILANDRM"

if [[ $INTERPRO = "IR" ]]; then
	wget -q -O /tmp/grc.csv "$STATSCIEL_GRC_IR"
	wget -q -O /tmp/drms.xml "$STATSCIEL_XML_IR"
fi
if [[ $INTERPRO = "CIVP" ]]; then
	wget -q -O /tmp/grc.csv "$STATSCIEL_GRC_CIVP"
	wget -q -O /tmp/drms.xml "$STATSCIEL_XML_CIVP"
fi

echo "IDENTIFIANT;INTERPRO_REF;ZONES;FAMILLE;SOUS_FAMILLE;NUM_ACCISES;RAISON_SOCIALE;CODE_POSTAL;SERVICE_DOUANE;EST_CONVENTIONNE_DV;NUM_CONVENTION_DV;TELEDECL_DRM_DV;EST_CONVENTIONNE_CIEL;TRANSMET_DRM_DV_CIEL;TELEDECL_DRM_CIEL"

cat /tmp/grc.csv | while IFS='' read -r line; do
	if [[ $line =~ ^\# ]]; then
		continue
	fi
	ID=$(echo "$line" | cut -d ';' -f 1)
	EA=$(echo "$line" | cut -d ';' -f 8)
	INFOS=$(echo "$line" | awk -F ";" '{print $1 ";" $4 ";" $36 ";" $19 ";" $20 ";" $8 ";" $13 ";" $17 ";" $25 }')

	HAS_CONV=0
	HAS_CONV_CIEL=0
	HAS_DRM=0
	HAS_DRM_DVCIEL=0
	HAS_DRM_CIEL=0

	CONV=$(echo "$line" | cut -d ';' -f 3)
	CONV_CIEL=$(echo "$line" | cut -d ';' -f 38)
	NB_BILAN=$(cat /tmp/bilans.json | grep "BILAN-DRM-$ID" | wc -l)
	NB_DV_CIEL=$(cat /tmp/bilans.json | grep "BILAN-DRM-$ID" | grep "CIEL" | wc -l)
	NB_DRM_CIEL=0
	if [ ! -z "$EA" ]; then
		NB_DRM_CIEL=$(cat /tmp/drms.xml | grep "$EA" | wc -l)
	fi

	
	if [[ $CONV > 0 ]]; then
		HAS_CONV=1
	fi
	if [[ $CONV_CIEL = "oui" ]]; then
		HAS_CONV_CIEL=1
	fi
	if [[ $NB_BILAN > 0 ]]; then
		HAS_DRM=1
	fi
	if [[ $NB_DV_CIEL > 0 ]]; then
		HAS_DRM_DVCIEL=1
	fi
	if [[ $NB_DRM_CIEL > 0 ]]; then
		HAS_DRM_CIEL=1
	fi

	echo "$INFOS;$HAS_CONV;$CONV;$HAS_DRM;$HAS_CONV_CIEL;$HAS_DRM_DVCIEL;$HAS_DRM_CIEL"

done
