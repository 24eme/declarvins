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
    
    public function preSave() {
        if ($this->document->isArchivageCanBeSet()) {
            $this->archiver();
        }
    }

    public function archiver() {
        if ($this->document->numero_archive) {
            return;
        }

        $this->document->date_archivage = $this->calculDateArchivage();
        $type = $this->document->toJson()->type;
        $last_numero = ArchivageAllView::getInstance()->getLastNumeroArchiveByTypeAndDate($type, $this->document->getDateArchivageLimite());
        $this->document->numero_archive = sprintf($this->format, $last_numero+1);
    }

    public function calculDateArchivage() {

        return date('Y-m-d');
    }


}