<?php

class sfWidgetFormInputFloat extends sfWidgetFormInputText
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfApplicationConfiguration::getActive()->loadHelpers('Float');
    $value = sprintFloat($value);
    $attributes['autocomplete'] = 'off';
    if (!isset($attributes['class']))
      $attributes['class'] = '';
    $attributes['class'] = ' num num_float';
    if (!($value*1)) 
      $attributes['class'] .= ' num_light';

    return parent::render($name, $value, $attributes, $errors);
  }
}