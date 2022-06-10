<?php
namespace Osf\Bean;

use Osf\Stream\Text as T;
use Osf\Exception\ArchException;
use Osf\Crypt\Crypt;

/**
 * Beans super class
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage bean
 */
abstract class AbstractBean
{
    private $lastBuildedHash = null;
    
    /**
     * @return $this
     */
    public function populate(array $data, bool $noError = false)
    {
        $assocs = [];
        foreach ($data as $key => $value) {
            
            // Clés de type '[k|v]_name_[0-9]+' traduites en tableau 
            // associatif [k1 => v1, k2 => v2...], qui appellent setName()
            // (type libellés de lettre, etc.)
            $matches = [];
            if (preg_match('/^([kv])_([A-z_-]+)_([0-9]+)$/', $key, $matches)) {
                if ($matches[1] === 'k' && substr($value, -1, 1) !== ':') {
                    $value .= __(" :");
                }
                $assocs[$matches[2]][$matches[3]][$matches[1]] = $value;
                continue;
            }
            
            // Clés de type 'name' qui appellent setName()
            $setter = 'set' . T::camelCase($key);
            if (!method_exists($this, $setter)) {
                if ($noError) { continue; }
                throw new ArchException('Key ' . $key . ' unknown in ' . get_class($this) . ' bean');
            }
            $this->$setter($value);
        }
        
        // Insertion des tableaux associatifs
        if ($assocs) {
            foreach ($assocs as $key => $values) {
                ksort($values);
                $assoc = [];
                foreach ($values as $vals) {
                    $assoc[$vals['k']] = $vals['v'];
                }
                $setter = 'set' . T::camelCase($key);
                $this->$setter($assoc);
            }
        }
        
        return $this;
    }
    
    /**
     * Document unique identifier / verification code
     * @return string
     */
    public function buildHash(): string
    {
        $hash = Crypt::hash(serialize($this), true);
        $this->lastBuildedHash = $hash;
        return $hash;
    }
    
    /**
     * Get the last requested hash
     * @return null|string
     */
    public function getHashLastBuilded(bool $buildIfNotFound = false): ?string
    {
        return $this->lastBuildedHash ?? ($buildIfNotFound ? $this->buildHash() : null);
    }
}
