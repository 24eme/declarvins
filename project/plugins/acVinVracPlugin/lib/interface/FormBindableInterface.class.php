<?php
interface FormBindableInterface
{
	public function bind(array $taintedValues = null, array $taintedFiles = null);
	public function unEmbedForm($key);
}