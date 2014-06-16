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
        
        $fictifs = CompteAllView::getInstance()->findBy(1, 'CompteTiers', _Compte::STATUT_FICTIF)->row;
        $attentes = CompteAllView::getInstance()->findBy('INTERPRO-IR', 'CompteTiers', _Compte::STATUT_ATTENTE)->row;
        $inscrits = CompteAllView::getInstance()->findBy('INTERPRO-IR', 'CompteTiers', _Compte::STATUT_INSCRIT)->row;
        foreach ($fictifs as $f) {
        	if ($f->value[CompteAllView::VALUE_NUMERO_CONTRAT]) {
        		if ($object = ContratClient::getInstance()->find('CONTRAT-'.$f->value[CompteAllView::VALUE_NUMERO_CONTRAT])) {
        			foreach ($object->etablissements as $etab) {
        				echo _Compte::STATUT_FICTIF.';'.$object->no_contrat.';'.$etab->raison_sociale.';'.$etab->email.';'.$etab->adresse.';'.$etab->code_postal.';'.$etab->commune.';'.$etab->siret.';'.$object->nom.';'.$object->prenom.';'.$object->email;
        				echo "\n";
        			}	
        		}				
        	}
        }
    	foreach ($attentes as $a) {
        	if ($a->value[CompteAllView::VALUE_NUMERO_CONTRAT]) {
        		if ($object = ContratClient::getInstance()->find('CONTRAT-'.$a->value[CompteAllView::VALUE_NUMERO_CONTRAT])) {
        			foreach ($object->etablissements as $etab) {
        				echo _Compte::STATUT_ATTENTE.';'.$object->no_contrat.';'.$etab->raison_sociale.';'.$etab->email.';'.$etab->adresse.';'.$etab->code_postal.';'.$etab->commune.';'.$etab->siret.';'.$object->nom.';'.$object->prenom.';'.$object->email;
        				echo "\n";
        			}	
        		}				
        	}
        }
    	foreach ($inscrits as $i) {
        	if ($i->value[CompteAllView::VALUE_NUMERO_CONTRAT]) {
        		if ($object = ContratClient::getInstance()->find('CONTRAT-'.$i->value[CompteAllView::VALUE_NUMERO_CONTRAT])) {
        			foreach ($object->etablissements as $etab) {
        				echo _Compte::STATUT_INSCRIT.';'.$object->no_contrat.';'.$etab->raison_sociale.';'.$etab->email.';'.$etab->adresse.';'.$etab->code_postal.';'.$etab->commune.';'.$etab->siret.';'.$object->nom.';'.$object->prenom.';'.$object->email;
        				echo "\n";
        			}	
        		}				
        	}
        }
    }

}