<?php

class PrintableHTML extends PrintableOutput {

  protected $html;

  protected function init() {

  }

  public function addHtml($html) {
    $this->html .= $html;
  }

    public function addHeaders($response) {
        
    }

  public function output() {
    return $this->html;
  }
    }

