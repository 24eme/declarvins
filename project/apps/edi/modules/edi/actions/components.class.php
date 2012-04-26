<?php

class ediComponents extends sfComponents {
    /**
     *
     * @param sfWebRequest $request
     */
    public function executeViewDRM() {
      $this->csv = DRMCsvFile::createFromDRM($this->drm);
    }
}