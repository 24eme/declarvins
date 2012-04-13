<?php
interface ConfigurationInterface
{
	public function setDonneesCsv($datas);
  	public function hasDepartements();
 	public function hasDroits();
  	public function hasLabel();
  	public function getTypeNoeud();
}