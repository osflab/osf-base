<?php
namespace Osf\Form\Hydrator;

use Osf\Form\AbstractForm;
use Osf\Exception\ArchException;
use Osf\Form\Element\ElementCheckbox;
use Osf\Form\Element\ElementSelect;

/**
 * Common hydrator for forms
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package package
 * @subpackage subpackage
 */
class HydratorStandard extends HydratorAbstract
{
    /**
     * Common form hydrator
     * 
     * If the form and its elements have prefixes, you need to put an array which 
     * take account of prefixes. For example, with the prefix 'product', send an 
     * array ['product' => ['name' => 'xxx', 'price' => 'xxx', ...]. 
     * 
     * @param array $values hydrating values
     * @param AbstractForm $form form to hydrate
     * @param bool $noError Do not throw an error if a value do not correspond to an existing element
     * @param bool $fullValues Throw an error if all the elements of the form have not been hydrated
     * @return $this
     * @throws ArchException
     */
    public function hydrate(array $values, AbstractForm $form, bool $prefixedValues = true, bool $noError = false, bool $fullValues = false)
    {
        // Hydratation avec préfixes
        if ($prefixedValues) {
            
            // Elements du formulaire principal
            foreach ($form->getElements() as $elt) {
                $eltName = $elt->getName(false);
                
                // Ajout des éléments (formulaire avec préfixe)
                if ($elt->getPrefix()) {
                    $valueExists = isset($values[$elt->getPrefix()]) && array_key_exists($eltName, $values[$elt->getPrefix()]);
                    if (!$valueExists && $fullValues) {
                        throw new ArchException('No value for element [' . $eltName . ']');
                    }
                    if ($valueExists) {
                        $filteredValue = $elt->getFilters()->filter($values[$elt->getPrefix()][$eltName]);
                        $elt->setValue($filteredValue);
                        unset($values[$elt->getPrefix()][$eltName]);
                        if (!$values[$elt->getPrefix()]) {
                            unset($values[$elt->getPrefix()]);
                        }
                    }
                }
                
                // Ajout des éléments (formulaire sans préfixe)
                else {
                    $valueExists = array_key_exists($eltName, $values);
                    if (!$valueExists && !$noError) {
                        throw new ArchException('No value for element [' . $eltName . ']');
                    }
                    if ($valueExists) {
                        $filteredValue = $elt->getFilters()->filter($values[$eltName]);
                        $elt->setValue($filteredValue);
                        unset($values[$eltName]);
                    }
                }
                
                // On fixe la valeur à "false" pour les checkboxes non détectées
                if (!$valueExists && $elt instanceof ElementCheckbox) {
                    $elt->setValue(false);
                }
                
                // On vide les listes non détectées
                if (!$valueExists && $elt instanceof ElementSelect) {
                    $elt->setValue($elt->isMultiple() ? [] : null);
                }
            }
            
            // Appel récursif pour les sous formulaires
            foreach ($form->getSubForms() as $key => $subForm) {
                $noSfValues = !isset($values[$key]) || !is_array($values[$key]);
                if ($fullValues && $noSfValues) {
                    throw new ArchException('No values found for subform [' . $key . ']');
                }
                if ($noSfValues) {
                    $values[$key] = [];
                }
                $subForm->hydrate($values[$key], null, true, $noError, $fullValues);
                unset($values[$key]);
            }
            if (!$noError && $values) {
                throw new ArchException('Some values are not hydrated [' . print_r($values, true) . ']');
            }
        } 
        
        // Hydratation sans préfixes
        else {
            foreach ($values as $key => $value) {
                if ($form->hasSubForm($key) && is_array($value)) {
                    $form->getSubForm($key)->hydrate($value, null, $noError);
                    continue;
                }
                $elt = $form->getElement($key);
                if (!$elt) {
                    if ($noError) {
                        continue;
                    }
                    throw new ArchException('No element [' . $key . '] found');
                } else {
                    $elt->setValue($elt->getFilters()->filter($value));
                }
            }
        }
        return $this;
    }
}
