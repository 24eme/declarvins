<?php

class DRMStatsVolumesView extends acCouchdbView
{
    const KEY_CAMPAGNE = 0;
    const KEY_MOIS = 1;
    const KEY_CERTIFICATION = 2;
    const KEY_GENRE = 3;
    const KEY_APPELLATION = 4;
    const KEY_MENTION = 5;
    const KEY_LIEU = 6;
    const KEY_COULEUR = 7;
    const KEY_CEPAGE = 8;

    public static function getInstance() {

        return acCouchdbManager::getView('drm', 'stats_volumes', 'DRM');
    }

    public function getByCampagne($campagne, $group_level) {
        $items = array();
        for($i = 0; $i < 12; $i++) {
            $items = array_merge($items, $this->getByCampagneAndMois($campagne, sprintf('%02d', (($i + 7) % 12)+1), $group_level));
        }
        return $items;
    }

    public function getByCampagneAndMois($campagne, $mois, $group_level) {

        return $this->build($this->client
                                 ->startkey(array($campagne, $mois))
                                 ->endkey(array($campagne, $mois, array()))
                                 ->reduce(true)
                                 ->group_level($group_level)
                                 ->getView($this->design, $this->view)->rows);
    }

    protected function build($rows) {
        $items = array();

        foreach($rows as $row) {
            $items[] = array_merge($row->key, array(round($row->value, 2)));
        }

        return $items;
    }

}  