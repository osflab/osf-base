<?php
namespace Osf\Config;

use Osf\Exception\ArchException;
use Osf\Form\Element\ElementSubmit;
use Osf\Form\OsfForm as Form;
use Osf\Form\TableForm;

/**
 * Configuration structure with form build instructions for automatic form generation
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage config
 */
class OsfConfig
{
    protected $config = ['forms' => []];
    protected $fields = [];
    
    /**
     * Add a new configuration data
     * @param array $config
     * @param array $filterData
     * @return $this
     */
    public function appendConfig(array $config, ?array $filterData = null)
    {
        // On complète la configuration 
        $this->config = array_replace_recursive($this->config, $config);
        
        // On filtre la configuration avec les données de filtrage
        if (is_array($filterData)) {
            $this->filterConfig($filterData);
        }
        
        // Réinitialisation du build des fields
        $this->fields = [];
        
        return $this;
    }
    
    /**
     * @return \Osf\Form\OsfForm
     */
    public function getForm(bool $withDescriptions = true): \Osf\Form\OsfForm
    {
        $form = new Form();
        foreach ($this->config['forms'] as $key => $category) {
            $subForm = (new TableForm(new Part($category['options'])))
                       ->setGenerateDescriptions($withDescriptions);
            $title = isset($category['title']) ? $category['title'] : __("Options");
            $icon  = isset($category['icon'])  ? $category['icon']  : null;
            $subForm->setTitle($title, $icon);
            $form->addSubForm($key, $subForm);
        }
        $submitValue = isset($this->config['submitValue']) ? __($this->config['submitValue']) : __("Mettre à jour");
        $form->add((new ElementSubmit('submit'))->setValue($submitValue));
        return $form;
    }
    
    /**
     * @return array
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->config['forms'] as $key => $category) {
            foreach ($category['options'] as $subKey => $params) {
                $values[$key][$subKey] = 
                    isset($params['value']) 
                    ? $params['value']
                    : (
                        isset($params['default'])
                        ? $params['default']
                        : null
                    );
            }
        }
        return $values;
    }
    
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
    
    /**
     * Filter configuration based on values (parameter "condition")
     * @param array $data
     * @return $this
     */
    public function filterConfig(array $data)
    {
        foreach ($this->config['forms'] as $key => $category) {
            foreach ($category['options'] as $subKey => $params) {
                if (!isset($params['conditions'])) {
                    continue;
                }
                foreach ($params['conditions'] as $command => $values) {
                    foreach ($values as $keys => $value) {
                        $this->filterItem($key, $subKey, $command, $keys, $value, $data);
                    }
                }
            }
        }
        return $this;
    }
    
    /**
     * @param type $key
     * @param type $subKey
     * @param type $command
     * @param type $keys
     * @param type $value
     * @param array $data
     * @throws ArchException
     */
    protected function filterItem($key, $subKey, $command, $keys, $value, array $data)
    {
        $match = false;
        $dataValue = $this->getDataValue($data, explode('|', $keys));
        switch ($command) {
            case 'is' : 
                $match = is_array($value) ? in_array($dataValue, $value) : $value == $dataValue;
                break;
            case 'isnot' : 
                $match = is_array($value) ? !in_array($dataValue, $value) : $value != $dataValue;
                break;
            case 'in' :
                if (!is_array($dataValue)) {
                    throw new ArchException('Value of [' . $keys . '] is not an array');
                }
                $match = in_array($value, $dataValue);
                break; 
            case 'notin' : 
                if (!is_array($dataValue)) {
                    throw new ArchException('Value of [' . $keys . '] is not an array');
                }
                $match = !in_array($value, $dataValue);
                break;
            default : 
                throw new ArchException('Command [' . $command . '] not found');
        }
        if (!$match) {
            unset($this->config['forms'][$key]['options'][$subKey]);
        }
    }
    
    /**
     * @param array $data
     * @param array $keys
     * @return mixed
     */
    protected function getDataValue(array $data, array $keys)
    {
        $elt = $data;
        foreach ($keys as $key) {
            if (!is_array($elt) || !array_key_exists($key, $elt)) {
                return null;
            }
            $elt = $elt[$key];
        }
        return $elt;
    }
    
//    /**
//     * @param array $formValues
//     * @return $this
//     */
//    public function populate(array $formValues)
//    {
//        foreach ($formValues as $key => $forms) {
//            foreach ($forms as $subKey => $value) {
//                if (!isset($this->config['forms'][$key]['options'][$subKey])) {
//                    throw new Exception\ArchException('No option [' . $key . '::' . $subKey . ']');
//                }
//                $this->config['forms'][$key]['options'][$subKey]['value'] = $value;
//            }
//        }
//        return $this;
//    }
}
