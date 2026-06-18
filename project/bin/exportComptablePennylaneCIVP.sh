#!/bin/bash

. bin/config.inc


TMPE="$TMP/export_exantis_civp"
LATEX="data/latex"

rm -rf $TMPE 2> /dev/null
mkdir -p $TMPE $TMPE/pdf

EXPORT_SOCIETE_ONLY_FACTURES=1 php symfony export:societe $SYMFONYTASKOPTIONS --interpro="INTERPRO-CIVP" > $TMP/societes.csv
