#!/bin/bash

. bin/config.inc

cd "$TARGETODGFILES" || exit 1

/usr/bin/wget --user="$USERODG" --password="$PASSWORDODG" -O odgprovence_sv12.csv https://declaration.syndicat-cotesdeprovence.com/exports_CIVP/sv12.csv
/usr/bin/wget --user="$USERODG" --password="$PASSWORDODG" -O odgprovence_sv11.csv https://declaration.syndicat-cotesdeprovence.com/exports_CIVP/sv11.csv
/usr/bin/wget --user="$USERODG" --password="$PASSWORDODG" -O odgprovence_drev.csv https://declaration.syndicat-cotesdeprovence.com/exports_CIVP/drev.csv
/usr/bin/wget --user="$USERODG" --password="$PASSWORDODG" -O odgprovence_reserve-interpro.csv https://declaration.syndicat-cotesdeprovence.com/exports_CIVP/reserve-interpro.csv
/usr/bin/wget --user="$USERODG" --password="$PASSWORDODG" -O odgprovence_dr.csv https://declaration.syndicat-cotesdeprovence.com/exports_CIVP/dr.csv
