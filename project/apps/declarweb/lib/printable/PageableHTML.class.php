<?php

class PageableHTML extends PageableOutput {

  protected $html;

  protected function init() {

  }

  public function addHtml($html) {
    $this->html .= $html;
  }

  public function output() {
    echo $this->html;
    return;
  }
}

