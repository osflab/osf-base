<?php
namespace Osf\Pdf\Document\Bean\Addon;

use Osf\Exception\ArchException;
use Osf\Pdf\Document\Bean\BaseDocumentBean as BDB;
use Osf\Bean\BeanHelper as BH;
use DateTime;

/**
 * Document parts : title, subject, date, place...
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
trait Parts
{
    protected $title = '';
    protected $subject;
    protected $displayCreatedBy = true;
    protected $confidential = true;

    /**
     * Document date
     * @var \DateTime
     */
    protected $date;  // Default = date du jour
    protected $place; // Default = ville de l'entreprise

    /**
     * @return the $title
     */
    public function getTitle(bool $escape = false): string
    {
        return (string) BH::filterContent($this->title, $escape);
    }
    
    /**
     * @return the $subject
     */
    public function getSubject(bool $escape = false): ?string
    {
        return BH::filterContent($this->subject, $escape);
    }
    
    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject(?string $subject)
    {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * Writing date
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date = null)
    {
        if ($date === null) {
            $date = new DateTime();
        }
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        if (!($date instanceof DateTime)) {
            throw new ArchException("Date must be a DateTime object or a valid string");
        }
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }
    
    /**
     * Build a date for files
     * @param DateTime $date
     * @return string
     */
    protected function filenameDate(DateTime $date = null): string
    {
        return $date instanceof DateTime
                ? $date->format(BDB::FILENAME_DATE_FORMAT) 
                : ($this->getDate() 
                    ? $this->getDate()->format(BDB::FILENAME_DATE_FORMAT) 
                    : '');
    }
    
    /**
     * @param $displayCreatedBy bool
     * @return $this
     */
    public function setDisplayCreatedBy($displayCreatedBy = true)
    {
        $this->displayCreatedBy = (bool) $displayCreatedBy;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDisplayCreatedBy(): bool
    {
        return $this->displayCreatedBy;
    }
        
    /**
     * @return $this
     */
    public function setConfidential($confidential = true)
    {
        $this->confidential = (bool) $confidential;
        return $this;
    }

    /**
     * @return bool
     */
    public function getConfidential(): bool
    {
        return $this->confidential;
    }

    /**
     * @return $this
     */
    public function setPlace(?string $place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * @param bool $escape
     * @return string|null
     */
    public function getPlace(bool $escape = false): ?string
    {
        return BH::filterContent($this->place, $escape);
    }
}
