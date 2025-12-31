<?php

class CSVDRMClient extends CSVClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("CSVDRM");
    }

    public function findCSVDRM($identifiant, $from = "0000-00", $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this
            ->startkey(sprintf("CSV-DRM-%s-%s", $identifiant, $from))
            ->endkey(sprintf("CSV-DRM-%s-%s", $identifiant, "9999-99"))
            ->execute($hydrate)->getDatas();
    }


        public function getFileContent() {
            return file_get_contents($this->getAttachmentUri($this->getFileName()));
        }
}
