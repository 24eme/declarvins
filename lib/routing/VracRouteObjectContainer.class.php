<?php

/**
* 
*/
class VracRouteObjectContainer
{
    protected $vrac = null;
    protected $etablissement = null;
    
    public function __construct(Vrac $vrac, $etablissement = null)
    {
        $this->vrac = $vrac;
        $this->etablissement = $etablissement;
    }
}