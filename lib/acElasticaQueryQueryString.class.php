<?php

class acElasticaQueryQueryString extends Elastica_Query_QueryString {
  public function __construct($query_string = null) {
    parent::__construct();
    $this->setDefaultOperator('AND');
    if ($query_string) {
      $this->setQuery($query_string);
    }
    return $this;
  }
}