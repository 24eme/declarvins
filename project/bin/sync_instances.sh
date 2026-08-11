#!/bin/bash

# Mode multi app
if ! test -f $(echo $0 | sed 's/[^\/]*$//')config.inc && ! test $1 ; then
    ls . $(echo $0 | sed 's/[^\/]*$//') | grep "config_" | grep ".inc$" | sed 's/config_//' | sed 's/\.inc//' | while read app; do
        bash $(echo $0 | sed 's/[^\/]*$//')sync_instances.sh $app;
    done
    exit 0
fi

if ! test $1 ; then
    . $(echo $0 | sed 's/[^\/]*$//')config.inc
else
    . $(echo $0 | sed 's/[^\/]*$//')config_"$1".inc
fi

rsync -zaO $WORKINGDIR"/web/generation/" $COUCHDISTANTHOST":"$WORKINGDIR"/web/generation"
rsync -zaO $WORKINGDIR"/"$EXPORTDIR"/" $COUCHDISTANTHOST":"$WORKINGDIR"/"$EXPORTDIR
if test "$EXTRA_SYNC" ; then
    echo $EXTRA_SYNC | sed 's/ /\n/g' | while read extra ; do
        if test "$extra" && test -d "$extra" ; then
            rsync -zaO $extra"/" $COUCHDISTANTHOST":"$extra
        fi
    done
fi
