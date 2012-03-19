<?php

function isRectifierCssClass($object, $key) {

	if ($object->getDocument()->isModifiedMasterDRM($object->getHash(), $key)) {

		return 'rectifier';
	} else {

		return null;
	}
}