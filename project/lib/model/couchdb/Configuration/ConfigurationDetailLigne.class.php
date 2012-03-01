<?php
/**
 * Model for ConfigurationDetailLigne
 *
 */

class ConfigurationDetailLigne extends BaseConfigurationDetailLigne {
  public function isReadable() {
    return ($this->readable);
  }
  public function isWritable() {
    return ($this->readable) && ($this->writable);
  }
}