<?php

class PrintableOutput {

  protected $filename;
  protected $file_dir;
  protected $annexe = [];

  const FORMAT_A4 = "a4";
  const ORIENTATION_PORTRAIT = "portrait";
  const ORIENTATION_LANDING = "landscape";

  public function __construct($filename = '', $file_dir = null) {
    $this->filename = $filename;
    $this->file_dir = $file_dir;
    $this->init();
  }

  public function setAnnexe($annexe) {
      $this->annexe[] = $annexe;
  }


  public function setPaper($format, $orientation) {
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

  public function generatePDF() {
  }
  
  }

