#!/bin/bash

TMP=/tmp/declarvins

REMOTE_DRM=$1
REMOTE_VRAC=$2
FROM=$(date --date="15 days ago" +"%Y-%m-%d")

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_intersud_csv

mkdir $TMP 2> /dev/null; mkdir $DATA_DIR 2> /dev/null;

wget -O $DATA_DIR/drms_intersud.csv $REMOTE_DRM$FROM

wget -O $DATA_DIR/contrats_intersud.csv $REMOTE_VRAC

touch $DATA_DIR/drms_intersud.clean.csv

touch $DATA_DIR/contrats_intersud.clean.csv

iconv -f UTF-8 -t ISO-8859-1//TRANSLIT $DATA_DIR/drms_intersud.csv > $DATA_DIR/drms_intersud.clean.csv
iconv -f UTF-8 -t ISO-8859-1//TRANSLIT $DATA_DIR/contrats_intersud.csv > $DATA_DIR/contrats_intersud.clean.csv


if [ -f $DATA_DIR/contrats_intersud.clean.csv ]; then

	rm -rf $DATA_DIR/contrats; 
	mkdir $DATA_DIR/contrats

	awk -F ";" '{print >> ("'$DATA_DIR'/contrats/" $3 "_" $2 "_" $1 ".csv")}' $DATA_DIR/contrats_intersud.clean.csv

	echo "Import des Contrats"

	php symfony import:vrac $DATA_DIR/contrats

fi

if [ -f $DATA_DIR/drms_intersud.clean.csv ]; then

	rm -rf $DATA_DIR/drms; 
	mkdir $DATA_DIR/drms

	awk -F ";" '{print >> ("'$DATA_DIR'/drms/" $4 "_" $3 "_" $2 ".csv")}' $DATA_DIR/drms_intersud.clean.csv

	echo "Import des DRM"

	php symfony import:DRM $DATA_DIR/drms

fi

