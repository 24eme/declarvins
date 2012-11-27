<?php

class ArchivageDocument
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

        $type = $this->document->toJson()->type;
        
        $last_numero = ArchivageAllView::getInstance()->getLastNumeroArchiveByTypeAndCampagne($type, $this->document->campagne);
        $this->document->numero_archive = sprintf($this->format, $last_numero+1);
    }
}