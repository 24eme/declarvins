<?php

class PrintableOutput {

  protected $title;
  protected $link;
  protected $subtitle;
  protected $filename;
  protected $file_dir;
  

  public function __construct($title, $subtitle, $filename = '', $file_dir = null, $link = ' de ') {
    $this->title = $title;
    $this->link = $link;
    $this->subtitle = $subtitle;
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

