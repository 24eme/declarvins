<?php

class ArchivageDocument implements iLock
{
    protected $document;
    protected $format;

    public function __construct(acCouchdbDocument $document, $format = "%05d")
    {
        $this->document = $document;
        $this->format = $format;
    }

    public function reset() {
        $this->document->numero_archive = null;
    }
    
    public function preSave() {
        if ($this->document->isArchivageCanBeSet()) {
            $this->archiver();
        }
    }

    public function archiver() {
        if ($this->document->numero_archive) {
            return;
        }
	Lock::runLock($this, $this->document->toJson()->type);
    }

    public function executeLock($type = null) {
      $last_numero = ArchivageAllView::getInstance()->getLastNumeroArchiveByTypeAndCampagne($type, $this->document->campagne);
      $this->document->numero_archive = sprintf($this->format, $last_numero+1);
      return array('value' => $this->document->campagne.' '.$this->document->numero_archive, 'key' => $type);
    }
}
