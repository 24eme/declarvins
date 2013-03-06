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
        
	for($i = 0 ; $i < 10 ; $i++) {
	  try {
	    $this->getLock();
	    $last_numero = ArchivageAllView::getInstance()->getLastNumeroArchiveByTypeAndCampagne($type, $this->document->campagne);
	    $this->document->numero_archive = sprintf($this->format, $last_numero+1);
	    $this->setLock($this->document->campagne.' '.$this->document->numero_archive);
	    break;
	  }catch(sfException $e) {
	  }
	}
	if ($i >= 10) {
	  throw new sfException('Could not acquire the archive lock');
	}
    }

    private function getLock() {
      $this->lock = Lock::getInstance();
    }

    private function setLock($value) {
      $this->lock->add($this->document->type, $value);
      $this->lock->save();
    }
}
