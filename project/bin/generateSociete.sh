#!/bin/bash

cat $1 | while read line; do
  id=$(echo $line|cut -d ';' -f1);
  cc=$(echo $line|cut -d ';' -f2);
  interpro=$(echo $line|cut -d ';' -f3);
  php symfony generate:societe-by-etablissement $id --code-comptable="$cc" --interpro="$interpro"
done;
