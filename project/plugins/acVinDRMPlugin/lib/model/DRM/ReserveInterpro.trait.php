<?php
trait ReserveInterpro
{
    public function getMillesimeCourant()
    {
        return substr($this->getDocument()->campagne, 0, 4);
    }

    public function hasReserveInterpro()
    {
        return $this->exist('reserve_interpro');
    }

    public function getReserveInterpro()
    {
        if ($this->hasReserveInterpro()) {
            return $this->_get('reserve_interpro');
        }
        return 0;
    }

    public function getVolumeCommercialisable()
    {
        return $this->total - $this->getReserveInterpro();
    }

    public function setReserveInterpro($volume, $millesime = null)
    {
        $millesime = $millesime ?: $this->getMillesimeCourant();
        $reserveDetails = $this->getOrAdd('reserve_interpro_details');
        $this->reserve_interpro_details->add($millesime, round($volume, 5));
        $this->updateVolumeReserveInterpro();
    }

    public function updateVolumeReserveInterpro()
    {
        $volumeTotalEnReserve = 0;
        foreach ($this->getOrAdd('reserve_interpro_details') as $millesime => $volume) {
            $volumeTotalEnReserve += $volume;
        }
        $this->getOrAdd('reserve_interpro');
        $this->_set('reserve_interpro', round($volumeTotalEnReserve, 5));
    }

    public function hasReserveInterproMultiMillesime()
    {
        return (count($this->getOrAdd('reserve_interpro_details')) > 1);
    }

    public function getMillesimeForReserveInterpro() {
        $details = $this->getOrAdd('reserve_interpro_details');
        if (count($details) != 1) {
            return $this->getMillesimeCourant();
        }
        return array_key_first($details->toArray(true,false));
    }

    public function hasCapaciteCommercialisation()
    {
        return $this->exist('reserve_interpro_capacite_commercialisation');
    }

    public function getCapaciteCommercialisation()
    {
        if ($this->hasCapaciteCommercialisation()) {
            return $this->_get('reserve_interpro_capacite_commercialisation');
        }
        return 0;
    }

    public function hasSuiviSortiesChais()
    {
        return $this->exist('reserve_interpro_suivi_sorties_chais');
    }

    public function getSuiviSortiesChais()
    {
        if ($this->hasSuiviSortiesChais()) {
            return $this->_get('reserve_interpro_suivi_sorties_chais');
        }
        return 0;
    }

    public function updateSuiviSortiesChais()
    {
        if ($this->hasCapaciteCommercialisation()) {
            $vol = round($this->getSuiviSortiesChais() + $this->getVolumeSortieChai(), 2);
            $this->add('reserve_interpro_suivi_sorties_chais', $vol);
        }
    }
}
