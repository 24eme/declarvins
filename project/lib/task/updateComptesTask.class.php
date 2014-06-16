<?php

class updateComptesTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'comptes';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $campagne = null;
        
        $comptes = array
('michel',
'saisiedrm',
'amandine84',
'assemat',
'asharvatt',
'campuget',
'cavedetavel',
'cavelirac30',
'cavestmarc',
'cavroch57',
'cedres',
'clefevre',
'damiron',
'ddm123',
'dom-panisse',
'domaine-condorcet',
'domaine-des-hauts-chassis',
'domainecoulange',
'domainedefontenille',
'domaine-bernard-crumiere',
'earl-domaine-saint-julien',
'ehebert',
'elysabe',
'ent8678',
'estezargues',
'franck-alexandre',
'gaillard',
'gassier',
'grangeon',
'imbertf',
'jcfromont',
'josephcastan',
'lay-fra',
'loufrejau',
'melody',
'michel-gayot',
'marjolet',
'panery',
'pjaume',
'pimpinella',
'sasdaussantj',
'sautjm',
'scea-ribasse',
'sixtine',
'srousset',
'stesteve',
'sourcesmarine',
'tbonnet',
'thoumy',
'transportsblanc',
'vignal-yvonne',
'vignoble-saut',
'vidalfleury',
'bouissiere',
'chateau-des-roques',
'domainecharite',
'gaec-aubert-freres');
        $i = 1;
        
        foreach ($comptes as $compte) {
        	$object = _CompteClient::getInstance()->find('COMPTE-'.$compte);
        	if ($object) {
        		$ldap = new Ldap();
                $ldap->saveCompte($object);
        	}
			$this->logSection('compte', $object->_id.' OK '.$i);
			$i++;
        }
    }

}