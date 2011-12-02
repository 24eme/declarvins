<?php

class sfWidgetFormInputFloat extends sfWidgetFormInputText
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfApplicationConfiguration::getActive()->loadHelpers('Float');
    $value = sprintFloat($value);
    $attributes['autocomplete'] = 'off';
    //$attributes['class'] = 'num';
    return parent::render($name, $value, $attributes, $errors);
  }
}