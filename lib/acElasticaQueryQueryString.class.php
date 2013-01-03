<?php

class acElasticaQueryQueryString extends Elastica_Query_QueryString {
  public function __construct($query_string) {
    parent::__construct();
    $this->setDefaultOperator('AND');
    $this->setQuery($query_string);
  }
}