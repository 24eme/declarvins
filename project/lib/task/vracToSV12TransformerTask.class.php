<?php

class vracToSV12TransformerTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('vracid', sfCommandArgument::REQUIRED, 'Id du document VRAC'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
      new sfCommandOption('facture', null, sfCommandOption::PARAMETER_REQUIRED, 'Is facture', 0),
    ));

    $this->namespace        = 'vrac';
    $this->name             = 'sv12-transformer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $vrac = VracClient::getInstance()->find($arguments['vracid']);
    $checkingMode = $options['checking'];
    $isFacture = $options['facture'];

    if (!$vrac) {
        echo "Vrac objet with identifiant ".$arguments['vracid']." not found!";
        return;
    }

    $campagne = $vrac->getCampagne();
    $volume = $vrac->volume_propose;
    $produit = $vrac->getProduitObject();
    if (!$produit) {
        echo "product not found $vrac->produit\n";
        return;
    }
    if (!$vrac->acheteur_identifiant) {
        echo "acheteur identifiant not found $vrac->_id\n";
        return;
    }
    $sv12 = SV12Client::getInstance()->createOrFind($vrac->acheteur_identifiant, $campagne);
    if ($sv12->isValidee()) {
        $sv12->devalide();
    }
    if ($sv12->isNew()) {
        $sv12->constructId();
        $sv12->storeContrats();
    } else {
        $sv12->remove('totaux');
        $sv12->add('totaux');
    }
    $identifiant = SV12Client::SV12_KEY_SANSVITI.'-'.SV12Client::SV12_TYPEKEY_VENDANGE.str_replace('/', '-', $produit->getHash());
    $exist = $sv12->contrats->exist($identifiant);
    $sv12Contrat = $sv12->contrats->getOradd($identifiant);
    if (!$exist) {
        $sv12Contrat->updateNoContrat($produit, array('contrat_type' => SV12Client::SV12_TYPEKEY_VENDANGE, 'volume' => $volume));
    } else {
        $sv12Contrat->volume = round($sv12Contrat->volume + $volume, 2);
    }
    if (!$checkingMode) {
        $sv12->validate();
        if ($isFacture) {
            foreach($sv12->mouvements as $id => $mvts) {
                foreach($mvts as $mvt) {
                    if ($mvt->facturable && !$mvt->facture) {
                        $mvt->facture = 1;
                    }
                }
            }
        }
        $sv12->save();
    }
    echo "SV12 created $sv12->_id\n";
  }
}
