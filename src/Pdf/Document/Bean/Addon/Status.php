<?php
namespace Osf\Pdf\Document\Bean\Addon;

use Osf\Exception\ArchException;
use Osf\Pdf\Document\Bean\InvoiceBean as IB;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;

/**
 * Document status : created, sent, processed, canceled
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
trait Status
{
    protected $status = 'created';
    
    /**
     * @param $status string
     * @return $this
     */
    public function setStatus(string $status)
    {
        if (!in_array($status, static::STATUSES)) {
            throw new ArchException('Unknown status [' . $status . ']');
        }
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * Status labels
     * @param string $documentType
     * @return array
     * @throws ArchException
     */
    public static function getStatusNames(?string $documentType = null, ?string $labelForSelect = null): array
    {
        $statuses = $labelForSelect === null ? [] : ['' => $labelForSelect];
        $statuses[static::STATUS_CREATED  ] = __("Brouillon");
        $statuses[static::STATUS_SENT     ] = __("Envoyé");
        $statuses[static::STATUS_READ     ] = __("Lu par dest.");
        $statuses[static::STATUS_PROCESSED] = $documentType === IB::TYPE_INVOICE ? __("Payé") : ($documentType === IB::TYPE_ORDER ? __("Signé") : __("Résolu"));
        $statuses[static::STATUS_CANCELED ] = __("Annulé");
        return $statuses;
    }
    
    /**
     * Status letter array
     * @param string $documentType
     * @return array
     * @throws ArchException
     */
    public static function getStatusLetters(string $documentType = null): array
    {
//        if (!in_array($documentType, IB::TYPES)) {
//            throw new ArchException('Bad type [' . $documentType . ']');
//        }
        return [
            static::STATUS_CREATED   => __("BR"),
            static::STATUS_SENT      => __("EN"),
            static::STATUS_READ      => __("LU"),
            static::STATUS_PROCESSED => __("OK"), // $documentType === IB::TYPE_INVOICE ? __("PY") : ($documentType === IB::TYPE_ORDER ? __("SG") : __("RS")),
            static::STATUS_CANCELED  => __("CA")
        ];
    }
    
    /**
     * @return array
     */
    public static function getStatusColors($colorsInsteadOfBootstrapStatus = false, string $documentType = null): array
    {
        if ($colorsInsteadOfBootstrapStatus) {
            return [
                static::STATUS_CREATED   => AVH::COLOR_BLUE_LIGHT,
                static::STATUS_SENT      => AVH::COLOR_ORANGE,
                static::STATUS_READ      => AVH::COLOR_BLUE,
                static::STATUS_PROCESSED => AVH::COLOR_GREEN,
                static::STATUS_CANCELED  => AVH::COLOR_RED,
            ];
        }
        return [
            static::STATUS_CREATED   => AVH::STATUS_PRIMARY,
            static::STATUS_SENT      => AVH::STATUS_WARNING,
            static::STATUS_READ      => AVH::STATUS_INFO,
            static::STATUS_PROCESSED => AVH::STATUS_SUCCESS,
            static::STATUS_CANCELED  => AVH::STATUS_DANGER
        ];
    }
    
    /**
     * Document current status fullname
     * @return string|null
     */
    public function getStatusName(): ?string
    {
        $names = self::getStatusNames($this->getType());
        if (array_key_exists($this->status, $names)) {
            return $names[$this->status];
        }
        return null;
    }
    
    /**
     * Status letter of the current document (for small screens)
     * @return string|null
     */
    public function getStatusLetter(): ?string
    {
        $letters = self::getStatusLetters($this->getType());
        if (array_key_exists($this->status, $letters)) {
            return $letters[$this->status];
        }
        return null;
    }
    
    /**
     * @return string|null
     */
    public function getStatusColor($colorsInsteadOfBootstrapStatus = false): ?string
    {
        $colors = self::getStatusColors($colorsInsteadOfBootstrapStatus);
        if (array_key_exists($this->status, $colors)) {
            return $colors[$this->status];
        }
        return null;
    }
}
