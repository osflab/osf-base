<?php
namespace Osf\Helper;

/**
 * Array management
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage helper
 */
class Tab
{
    const DELETE = '[DEL]';
    
    protected $array;
    
    protected function __construct(array &$initialArray)
    {
        $this->array = &$initialArray;
    }
    
    /**
     * @return $this
     */
    public static function newArray(array &$initialArray = []):self
    {
        return new self($initialArray);
    }
    
    /**
     * null = nothing to do
     * false = remove item
     * @param string $key
     * @param type $value
     * @param bool $checkIfKeyExists
     * @param bool $strict accepte les valeurs vides non nulles
     * @return $this
     * @throws Exception
     */
    public function addItemIfNotEmpty(string $key, $value, bool $checkIfKeyExists = false, bool $strict = false):self
    {
        if (($strict && $value !== null) || (!$strict && $value)) {
            if ($checkIfKeyExists && array_key_exists($key, $this->array)) {
                throw new Exception('Key [' . $key . '] already exists');
            }
            if ($value === self::DELETE) {
                if (array_key_exists($key, $this->array)) {
                    unset($this->array[$key]);
                }
            } else {
                $this->array[$key] = $value;
            }
        }
        return $this;
    }
    
    /**
     * Multiple addItemIfNotNull
     * @param array $items
     * @param bool $strict accepte les valeurs vides non nulles
     * @param bool $skipThis ignore la clé 'this' afin d'éviter d'ajouter l'objet contenu dans $this
     * @return $this
     */
    public function addItemsIfNotNull(array $items, bool $strict = false, bool $skipThis = true)
    {
        foreach ($items as $key => $value) {
            if ($skipThis && ($key === 'this')) {
                continue;
            }
            $this->addItemIfNotEmpty($key, $value, false, $strict);
        }
        return $this;
    }
    
    /**
     * @return array
     */
    public function getArray():array
    {
        return $this->array;
    }
    
    /**
     * @param array $a1
     * @param array $a2
     * @return array
     */
    public static function arrayDiffAssocRecursive(array $a1, array $a2):array 
    {
        $diff = [];
        foreach ($a1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($a2[$key]) || !is_array($a2[$key])) {
                    $diff[$key] = $value;
                } else {
                    $subDiff = self::arrayDiffAssocRecursive($value, $a2[$key]);
                    if (!empty($subDiff)) {
                        $diff[$key] = $subDiff;
                    }
                }
            } else if (!array_key_exists($key, $a2) || $a2[$key] !== $value) {
                $diff[$key] = $value;
            }
        }
        return $diff;
    }
    
    /**
     * @param array $a
     * @param mixed $leafsValue
     * @return array
     */
    public static function replaceLeafs(array $a, $leafsValue = null):array
    {
        foreach ($a as $key => $value) {
            if (!is_array($value)) {
                $a[$key] = $leafsValue;
            } else {
                $a[$key] = self::replaceLeafs($a[$key], $leafsValue);
            }
        }
        return $a;
    }
    
    /**
     * Mix diff and replace recursive
     * @param array $a1
     * @param array $a2
     * @param mixed $valueIfDelete
     * @return array
     */
    public static function arrayDiffReplaceRecursive(array $a1, array $a2, $valueIfDelete = null):array
    {
        $diff = [];
        foreach ($a1 as $key => $value) {
            if (!array_key_exists($key, $a2)) {
                $diff[$key] = $valueIfDelete;
            } else { 
                if (!is_array($value) && $value !== $a2[$key]) {
                    $diff[$key] = $a2[$key];
                } else if (is_array($value) && !is_array($a2[$key])) {
                    $diff[$key] = $a2[$key];
                } else if (is_array($value) && is_array($a2[$key])) {
                    $subDiff = self::arrayDiffReplaceRecursive($value, $a2[$key], $valueIfDelete);
                    if ($subDiff !== []) {
                        $diff[$key] = $subDiff;
                    }
                }
                unset($a2[$key]);
            }
        }
        if (count($a2)) {
            $diff = array_merge($diff, $a2);
        }
        return $diff;
    }
    
    /**
     * Get percentage of not empty cells
     * @param array $tab
     * @param array $keys
     * @return int
     */
    public static function getPercentage(array $tab, array $keys = null)
    {
        $plein = 0;
        $total = 0;
        foreach ($tab as $key => $value) {
            if (is_array($keys) && !in_array($key, $keys)) {
                continue;
            }
            if ($value) {
                $plein++;
            }
            $total++;
        }
        return floor(($plein / $total) * 100);
    }
    
    /**
     * Build a tab from $tab with only $keys keys
     * @param array $tab
     * @param array $includeKeys
     */
    public static function reduce(array $tab, array $includeKeys = [], array $excludeKeys = [])
    {
        // Suppression des clés à exclure
        foreach ($excludeKeys as $key) {
            if (array_key_exists($key, $tab)) {
                unset($tab[$key]);
            }
        }
        
        // Utilisation de fonctions natives pour la liste blanche
        if ($includeKeys) {
            return array_intersect_key($tab, array_flip($includeKeys));
        }
        
        return $tab;
    }
    
    /**
     * Return true if all values are empty
     * @param array $tab
     * @return bool
     */
    public static function allValuesEmpty(array $tab):bool
    {
        foreach ($tab as $value) {
            if ($value) {
                return false;
            }
        }
        return true;
    }
}
