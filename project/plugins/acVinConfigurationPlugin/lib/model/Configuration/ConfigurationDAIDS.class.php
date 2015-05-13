<?php
/**
 * Model for ConfigurationDAIDS
 *
 */

class ConfigurationDAIDS extends BaseConfigurationDAIDS 
{
	public function hasVolumeConditionne()
	{
		return ($this->volume_conditionne)? true : false;
	}
}