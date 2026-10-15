#!/bin/bash
. bin/config.inc

php symfony generate:bilanDRM CIVP $BILANDRMDIR/CIVP/ $SYMFONYTASKOPTIONS
php symfony generate:bilanDRM CIVP $BILANDRMDIR/CIVP/ $SYMFONYTASKOPTIONS --lastcampagne="1"

bash "$(dirname "$0")/globalizeBilanDrmFiles.sh" "$BILANDRMDIR/CIVP/"

php symfony generate:bilanDRM IR $BILANDRMDIR/IR/ $SYMFONYTASKOPTIONS
php symfony generate:bilanDRM IR $BILANDRMDIR/IR/ $SYMFONYTASKOPTIONS --lastcampagne="1"

bash "$(dirname "$0")/globalizeBilanDrmFiles.sh" "$BILANDRMDIR/IR/"

php symfony generate:bilanDRM IVSE $BILANDRMDIR/IVSE/ $SYMFONYTASKOPTIONS
php symfony generate:bilanDRM IVSE $BILANDRMDIR/IVSE/ $SYMFONYTASKOPTIONS --lastcampagne="1"

bash "$(dirname "$0")/globalizeBilanDrmFiles.sh" "$BILANDRMDIR/IVSE/"
