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
    if (!($value*1)) 
      $attributes['class'] .= ' num_light num_float';

    return parent::render($name, $value, $attributes, $errors);
  }

  protected function attributesToHtmlCallback($k, $v)
  {
    return false === $v || ('' === $v && 'value' != $k) ? '' : sprintf(' %s="%s"', $k, $this->escapeOnce($v));
  }
}