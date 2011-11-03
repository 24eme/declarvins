<?php

class PrintableOutput {

  protected $filename;
  protected $file_dir;
  

  public function __construct($filename = '', $file_dir = null) {
    $this->filename = $filename;
    $this->file_dir = $file_dir;
    $this->init();
  }

  public function addHtml($html) {
  }

  public function output() {
  }

  public function isCached() {
  }

  public function removeCache() {
    return true;
  }

  public function generatePDF($no_cache = false) {
  }

}

