<?php

class importTask extends sfBaseTask {

	protected function configure() {
		$this->addArguments(array(
		new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Update from file'),
		));
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
		new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
		new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
		// add your own options here
		));

		$this->namespace = 'messages';
		$this->name = 'import';
		$this->briefDescription = 'import csv messages file';
		$this->detailedDescription = <<<EOF
The [import|INFO] task does things.
Call it with:

  [php symfony import|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array()) {
		ini_set('memory_limit', '512M');
		set_time_limit('3600');
		// initialize the database connection
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
		$json = new stdClass();
		$json->_id = 'MESSAGES';
		$json->type = 'Messages';
		/* Mise a jour des messages si un csv est passé en argument */
		if (isset($arguments['file']) && !empty($arguments['file'])) {
			if (file_exists($arguments['file'])) {
				foreach (file($arguments['file']) as $numero => $ligne) {
					$datas = explode(';', $ligne);
					$field = $this->getCsvValueAfterTreatment($datas[0]);
					$value = $this->getCsvValueAfterTreatment($datas[1]);
					if (isset($json->{$field})) {
						$this->logSection("ligne ".($numero + 1), "update success", null);
					} else {
						$this->logSection("ligne ".($numero + 1), $field." doesn't exist, it was created", null, 'ERROR');
					}
					$json->{$field} = $value;
				}
			} else {
				$this->logSection("update", "the file given can not be found", null, 'ERROR');
			}
		}
		$doc = acCouchdbManager::getClient()->retrieveDocumentById($json->_id);
		if ($doc) {
			$doc->delete();
		}
		$doc = acCouchdbManager::getClient()->createDocumentFromData($json);
		$doc->save();
	}
	protected function deleteFirstAndLastCharacter($string)
	{
		$string = substr($string, 0, -1); // delete last
		$string = substr($string, 1); // delete first
		return $string;
	}
	protected function getCsvValueAfterTreatment($string)
	{
		$string = trim($string);
		if (strlen($string) > 2) {
			$string = $this->deleteFirstAndLastCharacter($string);
		}
		return $string;
	}
}