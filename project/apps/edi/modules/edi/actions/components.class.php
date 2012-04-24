<?php

class ediComponents extends sfComponents {
    /**
     *
     * @param sfWebRequest $request
     */
    public function executeViewDRM() {
      $this->csv = new DRMCsvFile($this->drm);
    }
}