#!/bin/bash

. bin/config.inc

data=$(curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$1)

json_data=$(echo $data|jq -r 'del(._rev) | tostring')

curl -X POST -H "Content-Type: application/json" -d "$json_data" http://$COUCHHOST:$COUCHPORT/$COUCHBASE
