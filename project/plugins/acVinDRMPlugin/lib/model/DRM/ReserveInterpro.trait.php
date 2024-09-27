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

    public function setReserveInterpro($volume)
    {
        $millesime = $this->getMillesimeCourant();
        $reserveDetails = $this->getOrAdd('reserve_interpro_details');
        $reserveVolume = $reserveDetails->getOrAdd($millesime);
        $volumeEnReserve = ($reserveVolume > 0)? $reserveVolume + $volume : $volume;
        $this->reserve_interpro_details->add($millesime, round($volumeEnReserve, 5));
        $this->updateVolumeReserveInterpro();
    }

    public function updateVolumeReserveInterpro()
    {
        $volumeTotalEnReserve = 0;
        foreach ($this->getOrAdd('reserve_interpro_details') as $millesime => $volume) {
            $volumeTotalEnReserve += $volume;
        }
        $this->add('reserve_interpro', round($volumeTotalEnReserve, 5));
    }

    public function getReserveInterproDetails()
    {
        if ($this->exist('reserve_interpro_details')) {
            return $this->_get('reserve_interpro_details')->toArray(true, false);
        }
        return [$this->getMillesimeCourant() => $this->getReserveInterpro()];
    }
}
