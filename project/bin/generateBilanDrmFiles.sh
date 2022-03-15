#!/bin/bash
. bin/config.inc

php symfony generate:bilanDRM CIVP $BILANDRMDIR/CIVP/ $SYMFONYTASKOPTIONS
php symfony generate:bilanDRM CIVP $BILANDRMDIR/CIVP/ $SYMFONYTASKOPTIONS --lastcampagne="1"

php symfony generate:bilanDRM IR $BILANDRMDIR/IR/ $SYMFONYTASKOPTIONS
php symfony generate:bilanDRM IR $BILANDRMDIR/IR/ $SYMFONYTASKOPTIONS --lastcampagne="1"

php symfony generate:bilanDRM IVSE $BILANDRMDIR/IVSE/ $SYMFONYTASKOPTIONS
php symfony generate:bilanDRM IVSE $BILANDRMDIR/IVSE/ $SYMFONYTASKOPTIONS --lastcampagne="1"
