<?php
class Interpro extends BaseInterpro {
  public function __toString() {
    return $this->nom;
  }
}