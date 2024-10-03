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
        if (!$volume && $this->reserve_interpro_details->exist($millesime)) {
            $this->reserve_interpro_details->remove($millesime);
        } else {
            $this->reserve_interpro_details->add($millesime, round($volume, 5));
        }
        $this->updateVolumeReserveInterpro();
    }

    public function updateVolumeReserveInterpro()
    {
        $volumeTotalEnReserve = 0;
        foreach ($this->getOrAdd('reserve_interpro_details') as $millesime => $volume) {
            $volumeTotalEnReserve += $volume;
        }
        $this->_set('reserve_interpro', round($volumeTotalEnReserve, 5));
    }

    public function hasReserveInterproMultiMillesime()
    {
        return (count($this->getOrAdd('reserve_interpro_details')) > 1);
    }
}
