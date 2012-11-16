<?php
/**
 * Model for DAIDSDeclaration
 *
 */

class DAIDSDeclaration extends BaseDAIDSDeclaration 
{

	public function getChildrenNode() 
	{
		return $this->certifications;
	}

}