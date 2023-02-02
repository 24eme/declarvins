<?php

class updateSocietesModelCodesComptablesTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'societes-model-codes-comptables';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
		ini_set('memory_limit', '2048M');
  		set_time_limit(0);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $items = SocieteAllView::getInstance()->findByInterpro('INTERPRO-declaration');
        foreach($items as $item) {
            $societe = SocieteClient::getInstance()->find($item->id);
            if ($societe->code_comptable_client && $societe->code_comptable_client != Societe::REFERENCE_INTERPROS_METAS) {
                $societe->setMetasForInterpro('INTERPRO-IVSE', ['code_comptable_client' => $societe->code_comptable_client]);
                $societe->setMetasForInterpro('INTERPRO-IR', ['code_comptable_client' => substr($societe->identifiant, 0, -3)]);
                if ($societe->exist('codes_comptables_client')) {
                    $societe->remove('codes_comptables_client');
                }
                $societe->code_comptable_client = Societe::REFERENCE_INTERPROS_METAS;
                $societe->save();
                echo $societe->_id." updated\n";
            }
        }

    }

}
