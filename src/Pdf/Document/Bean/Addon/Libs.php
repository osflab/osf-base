<?php
namespace Osf\Pdf\Document\Bean\Addon;

use Osf\Bean\BeanHelper as BH;

/**
 * Document wordings (labels)
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
trait Libs
{
    protected $libs = []; // Wordings
    
    /**
     * Wordings (object, etc.)
     * @param array $libs
     * @return \Osf\Pdf\Document\Bean\LetterBean
     */
    public function setLibs(array $libs)
    {
        foreach ($libs as $key => $lib) {
            $this->setHeadLib($key, $lib);
        }
        return $this;
    }
    
    /**
     * Wordings (object, etc.)
     * @param bool $escape
     * @return array
     */
    public function getLibs(bool $escape = false): array
    {
        if ($escape) {
            $escaped = [];
            foreach ($this->libs as $key => $value) {
                $escaped[BH::filterContent($key, true)] = BH::filterContent($value, true);
            }
            return $escaped;
        }
        return $this->libs;
    }
    
    /**
     * Top words (object, attention, etc.)
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeadLib(string $key, ?string $value = null)
    {
        if ($value === null && array_key_exists($key, $this->libs)) {
            unset($this->libs[$key]);
        } else {
            $this->libs[$key] = trim($value);
        }
        return $this;
    }

    /**
     * Letter subject
     * @param string $object
     * @return $this
     */
    public function setObject(?string $object)
    {
        return $this->setHeadLib(__("Objet :"), $object);
    }
    
    /**
     * ATTN
     * @param string $attn
     * @return $this
     */
    public function setAttn(?string $attn)
    {
        return $this->setHeadLib(__("A l'attention de :"), $attn);
    }
    
    /**
     * Recipient reference number
     * @param string $vref
     * @return $this
     */
    public function setVref(?string $vref)
    {
        return $this->setHeadLib(__("V./réf."), $vref);
    }
    
    /**
     * Provider reference number
     * @param string $nref
     * @return $this
     */
    public function setNref(?string $nref)
    {
        return $this->setHeadLib(__("N./réf."), $nref);
    }
    
    /**
     * Top word
     * @param string $key
     * @return string
     */
    public function getHeadlib(string $key, bool $escape = false): ?string
    {
        return array_key_exists($key, $this->libs) 
                ? BH::filterContent($this->libs[$key], $escape) 
                : null;
    }
    
    /**
     * Some words
     */
    public static function getLibSelectList()
    {
        $libs = [
            __("Objet :") => __("Objet :"),
            __("A l'attention de :") => __("A l'attention de :"),
            __("N./réf. :") => __("N./réf. : (Notre Réf.)"),
            __("V./réf. :") => __("V./réf. : (Votre Réf.)")
        ];
        return $libs;
    }
}
