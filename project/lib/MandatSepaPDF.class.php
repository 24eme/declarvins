<?php
class MandatSepaPDF {

    protected $mandatSepa = null;
    protected $filename = null;
    protected $printable_document;
    protected $partial_function;

    public function __construct($mandatSepa, $type = 'pdf', $use_cache = false, $file_dir = null, $filename = null) {
        $this->mandatSepa = $mandatSepa;
        $this->filename = $filename;
        if (!$this->filename) {
            $this->filename = $this->getFileName(true);
        }
        $this->setUseCache($use_cache);
        $format_class = array('html' => 'PrintableHTML', 'pdf' => 'PrintablePDF');
		if(!array_key_exists($type, $format_class)) {
			throw new sfException(sprintf("Le format %s n'est pas géré", $type));
		}
        $class = $format_class[$type];
        $this->printable_document = new $class($this->filename);
        if (!$use_cache) {
            $this->printable_document->removeCache();
        }
        $this->printable_document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$this->printable_document->addHtml($this->getContent());
    }

    public function isUseCache()
    {
        return $this->use_cache;
    }

    public function setPartialFunction($function)
    {
        $this->partial_function = $function;
    }

    public function getPartialFunction()
    {
        return $this->partial_function;
    }

    public function isCached()
    {
        return ($this->printable_document->isCached());
    }

    public function getFile()
    {
        return $this->printable_document->getFile();
    }

    public function removeCache()
    {
        return $this->printable_document->removeCache();
    }

    public function generate()
    {
        return;
    }

    public function addHeaders($response)
    {
        $this->printable_document->addHeaders($response);
    }

    protected function getPartial($templateName, $vars = null)
    {
        if(!$this->partial_function) {
            return sfContext::getInstance()->getController()->getAction('mandatsepa', 'main')->getPartial($templateName, $vars);
        }
        return call_user_func_array($this->partial_function, array($templateName, $vars));
    }

    protected function setUseCache($use)
    {
        $this->use_cache = $use;
    }

    protected function getTitle()
    {
        return sprintf('%s de %s', $this->getHeaderTitle(), preg_replace('/\n/', ', ', $this->getHeaderSubtitle()));
    }

    public function getContent()
    {
        return $this->getPartial('mandatsepa/pdf', array('mandatSepa' => $this->mandatSepa));
    }

    public function output()
    {
        return $this->printable_document->output();
    }

    protected function getHeaderTitle()
    {
        return "Mandat de prélèvement SEPA";
    }

    protected function getHeaderSubtitle()
    {
        return "Référence : ".$this->mandatSepa->getReference(false);
    }

    protected function getFooterText()
    {
        return "";
    }

    public function getFileName($with_rev = false)
    {
        return self::buildFileName($this->mandatSepa, true);
    }

    public static function buildFileName($mandatSepa, $with_rev = false)
    {
        $filename = $mandatSepa->_id;
        if ($with_rev) {
            $filename .= '_' . $mandatSepa->_rev;
        }
        return $filename . '.pdf';
    }
}
