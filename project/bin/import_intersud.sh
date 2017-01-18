#!/bin/bash

. bin/config.inc

REMOTE_DATA=$1

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_intersud_csv

mkdir $TMP 2> /dev/null; mkdir $DATA_DIR 2> /dev/null;

wget -O $DATA_DIR/drms_intersud.csv $REMOTE_DATA

if [ -f $DATA_DIR/drms_intersud.csv ]; then

	rm -rf $DATA_DIR/drms; 
	mkdir $DATA_DIR/drms

	awk -F ";" '{print >> ("'$DATA_DIR'/drms/" $3 "_" $2 ".csv")}' $DATA_DIR/drms_intersud.csv

	echo "Import des DRM"

	php symfony import:DRM $DATA_DIR/drms --checking=1

fi

