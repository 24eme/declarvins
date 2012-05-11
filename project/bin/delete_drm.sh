curl "http://localhost:5984/declarvin/_design/drm/_view/all?group_level=9" | sed 's/"].*//' | sed 's/.*"//' | grep DRM | while read DRM; do
	DRMREV=$(curl http://localhost:5984/declarvin/$DRM | sed 's/{"_id":"//' | sed 's/","_rev":"/?rev=/' | grep DRM | sed 's/".*//')
	curl -X DELETE http://localhost:5984/declarvin/$DRMREV
done
