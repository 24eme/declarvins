<?php
class cravateApiLiberationReserveTask extends sfBaseTask
{
    private $logs = [];

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('submissions_path', sfCommandArgument::REQUIRED, 'Path to submissions'),
            new sfCommandArgument('conf_filename', sfCommandArgument::REQUIRED, 'submission configuration filename'),
    	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'cravate-api';
        $this->name = 'liberation-reserve';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $this->logs = [];

        if (substr($arguments['submissions_path'], -1) !=  DIRECTORY_SEPARATOR) {
            $arguments['submissions_path'] .= DIRECTORY_SEPARATOR;
        }
        if (!is_dir($arguments['submissions_path'])) {
            $this->logs[] = $arguments['submissions_path']." n'est pas un dossier valide";
            $this->rapport();
        }
        $submissions = scandir($arguments['submissions_path']);
        if ($submissions === false) {
            $this->logs[] = $arguments['submissions_path']." une erreur est survenue au scan";
            $this->rapport();
        }
        $result = [];
        foreach ($submissions as $submission) {
            if (in_array($submission, ['.', '..'])) {
                continue;
            }
            if (strpos(substr(trim($submission), -10), '_APPROUV') === false) {
                continue;
            }
            $path = $arguments['submissions_path'].$submission.DIRECTORY_SEPARATOR;
            if (!is_file($path.$arguments['conf_filename'])) {
                $this->logs[] = $path.$arguments['conf_filename']." n'est pas un fichier valide";
                continue;
            }
            $content = file_get_contents($path.$arguments['conf_filename']);
            if ($content === false) {
                $this->logs[] = $path.$arguments['conf_filename']." erreur de lecture";
                continue;
            }
            $result[$path] = json_decode($content);
        }

        if ($this->logs) {
            $this->rapport();
        }

        foreach ($result as $path => $conf) {
            $identifiant = (isset($conf->form) && isset($conf->form->NUMCIVP))? $conf->form->NUMCIVP : null;
            $volume = (isset($conf->form) && isset($conf->form->VOLUME))? $conf->form->VOLUME : null;
            $hash = (isset($conf->form) && isset($conf->form->PRODUIT))? $conf->form->PRODUIT : null;
            $millesime = (isset($conf->form) && isset($conf->form->MILLESIME))? $conf->form->MILLESIME : null;
            $empty = false;
            if (!$identifiant) {
                $this->logs[] = "NUMCIVP non trouvé dans la conf $path".$arguments['conf_filename'];
                $empty = true;
            }
            if (!$volume) {
                $this->logs[] = "VOLUME non trouvé dans la conf $path".$arguments['conf_filename'];
                $empty = true;
            }
            if (!$hash) {
                $this->logs[] = "PRODUIT non trouvé dans la conf $path".$arguments['conf_filename'];
                $empty = true;
            }
            if (!$millesime) {
                $this->logs[] = "MILLESIME non trouvé dans la conf $path".$arguments['conf_filename'];
                $empty = true;
            }
            if ($empty) {
                continue;
            }
            $historique = new DRMHistorique($identifiant);
            $lastDRM = $historique->getLastDRM();
            if (!$lastDRM) {
                $this->logs[] = "Pas de drm pour l'etablissement $identifiant";
                continue;
            }
            $drms = [$lastDRM];
            if (!$lastDRM->isValidee()) {
                $drms[] = $historique->getPreviousDRM($lastDRM->getPeriode());
            }
            $executedTask = false;
            foreach ($drms as $drm) {
                if (!$drm->exist($hash)) {
                    $this->logs[] = "Pas de produit $hash dans la drm $drm->_id";
                    continue;
                }
                if (!$executedTask) {
                    $task = sfConfig::get('app_cravate_updateTask');
                    $status = sfConfig::get('app_cravate_updateStatus');
                    $comment = sfConfig::get('app_cravate_updateComment').$drm->_id;
                    $taskResult = shell_exec(escapeshellcmd("$task $path $status \"$comment\""));
                    if (!$taskResult) {
                        $this->logs[] = "Une erreur est survenue lors de l'appel de la tâche $task";
                        continue;
                    }
                    if (strpos($taskResult, 'SUCCES') === false) {
                        $this->logs[] = "Erreur dans l'execution de la tâche $task : $taskResult";
                        continue;
                    }
                    $this->logs[] = $taskResult;
                    $executedTask = true;
                }
                $produit = $drm->get($hash);
                $produit->setReserveInterpro($volume, $millesime);
                $drm->save();
                $this->logs[] = "Réserve libérée : $drm->_id";
            }
        }
        $this->rapport();
    }

    private function rapport() {
        $interpro = InterproClient::getInstance()->retrieveById('CIVP');
        $to = [sfConfig::get('app_email_to_notification'), $interpro->email_contrat_inscription];
		$mail = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), $to, "DeclarVins // Libérations auto. de réserve interpro.", implode(PHP_EOL, $this->logs))->setContentType('text/plain');
		$this->getMailer()->sendNextImmediately()->send($mail);
        echo implode(PHP_EOL, $this->logs);
    }

}
