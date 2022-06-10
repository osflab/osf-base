<?php
namespace Osf\Generator;

use Osf\Exception\ArchException;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Reflection\MethodReflection;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Zend resources generation for quick and fast access
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage zend
 */
class ZendGenerator extends AbstractGenerator
{
    protected const ZF_VENDOR_PATH = '/../../vendor/zendframework';
    
    protected function getZendVendorPath(): string
    {
        static $vendorPath = null;
        
        if (!$vendorPath) {
            $vendorPath = __DIR__ . self::ZF_VENDOR_PATH;
            if (!is_dir($vendorPath)) {
                throw new ArchException('This generator must be used in a full OSF project');
            }
        }
        return $vendorPath;
    }
    
    public function generateAll()
    {
        $this->generateFilters();
        $this->generateValidators();
        $this->generateFormElements(); // BAD PERFORMANCES
        $this->generateViewHelpers();  // BAD PERFORMANCES
    }
    
    /**
     * Clean data types for zend generator
     * @param string|null $type
     * @return string|null
     */
    protected function cleanZendType(?string $type): ?string
    {
        if (!$type || strtolower($type) == 'null') {
            return null;
        }
        if (strtolower($type) == 'array') {
            return 'array';
        }
        if (strpos('\\', $type)) {
            return '\\' . $type;
        }
        return $type;
    }
    
    public function generateViewHelpers()
    {
        $znamespaces = [
            [
                'name' => "\\Zend\\View\\Helper\\",
                'prefix' => '',
                'pattern' => '/view-view/src/Helper/*.php'],
            [
                'name' => "\\Zend\\Form\\View\\Helper\\",
                'prefix' => '',
                'pattern' => '/zend-form/src/View/Helper/*.php'],
            [
                'name' => "\\Zend\\Form\\View\\Helper\\Captcha\\",
                'prefix' => 'captcha',
                'pattern' => '/zend-form/src/View/Helper/Captcha/*.php']
        ];
        $class = $this->buildClass('AbstractZendHelper', 'AbstractHelper', 'Zend Helpers builders');
        $class->setFlags(ClassGenerator::FLAG_ABSTRACT);
        $docBlock = $class->getDocBlock();
        $docBlock->setWordWrap(false);
        $methods = [];
        foreach ($znamespaces as $znamespace) {
            $classesPattern = $this->getZendVendorPath() . $znamespace['pattern'];
            $files = glob($classesPattern);
            foreach ($files as $file) {
                $className = basename($file, '.php');
                $helperMethod = $znamespace['prefix'] ? $znamespace['prefix'] . $className : strtolower($className{0}) . substr($className, 1);
                if (substr($className, 0, 8) == 'Abstract' 
                    || substr($className, -9) == 'Interface'
                    || in_array($className, ['ViewModel'])) {
                    continue;
                }
                //include_once $file;
                $methods[$helperMethod] = $znamespace['name'] . $className;
                $reflectionMethod = new MethodReflection($znamespace['name'] . $className, '__invoke');
                $parametersPrototypes = [];
                //$method = new MethodGenerator();
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $parameterType = $this->cleanZendType($parameter->getType());
                    $zparameter = new ParameterGenerator($parameter->getName(), $parameterType);
                    if ($parameter->isDefaultValueAvailable()) {
                        $zparameter->setDefaultValue($parameter->getDefaultValue());
                    }
                    $parametersPrototypes[] = trim($zparameter->generate());
                }
                $methodPrototype  = $znamespace['name'] . $className . ' ' . $helperMethod;
                $methodPrototype .= '(' . implode(', ', $parametersPrototypes) . ')';
                $docBlock->setTag(['name' => 'method', 'description' => $methodPrototype]);
            }
        }
        $class->addProperty('availableZendHelpers', $methods, PropertyGenerator::FLAG_PROTECTED);
        $namespace = 'Osf\View\Generated';
        $uses = ['Osf\View\AbstractHelper'];
        $fileName = __DIR__ . '/../View/Generated/AbstractZendHelper.php';
        $this->writeFile($fileName, $namespace, $class->generate(), $uses);
    }
    
    
    protected function generateSubClasses(
            $filesGlobPattern,   // Pattern to find Zend Classes to inherit
            $osfTargetDir,       // Target diretory to generate new subclasses
            $zendNamespace,      // Zend namespace writed in 'use' section (begin/end with \)
            $osfNamespace,       // Osf namespace used to declare file namespace (without begin/end \)
            $subClassPrefix,     // Prefix of subclass names and files
            $classesPrefix = '', // Prefix for container names
            $classes = [],       // Generate classes returned by this function to generate container
            $overwrite = true)
    {
        $files = glob($filesGlobPattern);
        if (!is_dir($osfTargetDir)) {
            mkdir($osfTargetDir, 0777, true);
        }
        foreach ($files as $file) {
            $className = basename($file, '.php');
            $fileName = $osfTargetDir . $subClassPrefix . $className . '.php';
            if (preg_match('/^Abstract.*$/', $className)) {
                continue;
            }
            $classes[$classesPrefix . $className] = "\\" . $osfNamespace . "\\" . $subClassPrefix . $className;
            if ($overwrite || !file_exists($fileName)) {
                $class = $this->buildClass($subClassPrefix . $className, $className, 'Zend Class ' . $className . ' inheritor');
                $uses = [$zendNamespace . $className];
                $this->writeFile($fileName, $osfNamespace, $class->generate(), $uses);
            }
        }
        return $classes;
    }
    
    public function generateBuilder($classes, $targetFile, $targetClassName, $namespace, $description)
    {
        $class = $this->buildClass($targetClassName, '\Osf\Generator\AbstractBuilder', $description);
        $class->setFlags(ClassGenerator::FLAG_ABSTRACT);
        $docBlock = $class->getDocBlock();
        $docBlock->setWordWrap(false);
        $docBlock->setLongDescription('This class is generated, do not edit.');
        $class->addProperty('classes', $classes, PropertyGenerator::FLAG_PROTECTED | PropertyGenerator::FLAG_STATIC);
        foreach ($classes as $name => $className) {
            $class->addMethod('new' . $name, ['...$args'],
                MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
                'return self::get(\'' . $name . '\', $args, false);',
                '@return ' . $className);
            $class->addMethod('get' . $name, ['...$args'],
                MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
                'return self::get(\'' . $name . '\', $args, true);',
                '@return ' . $className);
        }
        $classSrc = str_replace('$...', '...', $class->generate());
        $this->writeFile($targetFile, $namespace, $classSrc);
    }
    
    public function generateFormElements()
    {
        $this->generateSubClasses(
            $this->getZendVendorPath() . '/zend-form/src/Element/*.php', 
            __DIR__ . '/../Form/Zend/',
            "Zend\Form\\Element\\", 
            "Osf\\Form\\Zend", 
            'ZendElement');
    }
    
    public function generateFilters()
    {
        // Génération des filtres Zend principaux
        $classes = $this->generateSubClasses(
            $this->getZendVendorPath() . '/zend-filter/src/*.php', 
            __DIR__ . '/../Filter/Zend/',
            "Zend\Filter\\", 
            "Osf\\Filter\\Zend", 
            'ZendFilter');
        $subClasses = ['Compress', 'Encrypt', 'File', 'Word'];
        
        // Génération des filtres Zend situés dans des dossiers à part
        foreach ($subClasses as $subClass) {
            $classes = $this->generateSubClasses(
                $this->getZendVendorPath() . '/zend-filter/src/' . $subClass . '/*.php', 
                __DIR__ . '/../Filter/Zend/',
                "Zend\Filter\\" . $subClass . "\\", 
                "Osf\\Filter\\Zend", 
                'ZendFilter' . $subClass, 
                $subClass, 
                $classes);
        }
        
        // Ajout des filtres OSF au conteneur de filtres
        $filters = glob(__DIR__ . '/../Filter/*.php');
        foreach ($filters as $filterFile) {
            $filterFile = realpath($filterFile);
            $baseName = basename($filterFile, '.php');
            if (in_array($baseName, ['AbstractFilter', 'Filter', 'Test'])) {
                continue;
            }
            $classes[$baseName] = "\\Osf\\Filter\\" . $baseName;
        }
        ksort($classes);
        
        $this->generateBuilder($classes, __DIR__ . '/../Filter/Filter.php', 'Filter', 'Osf\Filter', 'Filters general builder');
    }
    
    public function generateValidators()
    {
        // Validateurs principaux
        $classes = $this->generateSubClasses(
            $this->getZendVendorPath() . '/zend-validator/src/*.php', 
            __DIR__ . '/../Validator/Zend/',
            "Zend\\Validator\\", 
            "Osf\\Validator\\Zend", 
            'ZendValidator');
        $subClasses = ['Barcode', 'File', 'Hostname'];
        
        // Sous-classes de validation
        foreach ($subClasses as $subClass) {
            $classes = $this->generateSubClasses(
                $this->getZendVendorPath() . '/zend-validator/src/' . $subClass . '/*.php', 
                __DIR__ . '/../Validator/Zend/',
                "Zend\Validator\\" . $subClass . "\\", 
                "Osf\\Validator\\Zend", 
                'ZendValidator' . $subClass, 
                $subClass, 
                $classes);
        }
        
        // Validations i18n
        $classes = $this->generateSubClasses(
            $this->getZendVendorPath() . '/zend-i18n/src/Validator/*.php', 
            __DIR__ . '/../Validator/Zend/',
            "Zend\\I18n\\Validator\\", 
            "Osf\\Validator\\Zend", 
            'ZendValidatorI18n', 
            '',
            $classes);
        
        // Ajout des validateurs OSF au conteneur
        $validators = glob(__DIR__ . '/../Validator/*.php');
        foreach ($validators as $validatorFile) {
            $validatorFile = realpath($validatorFile);
            $baseName = basename($validatorFile, '.php');
            if (in_array($baseName, ['AbstractValidator', 'Validator', 'Test'])) {
                continue;
            }
            $classes[$baseName] = "\\Osf\\Validator\\" . $baseName;
        }
        ksort($classes);
        
        $this->generateBuilder($classes, __DIR__ . '/../Validator/Validator.php', 'Validator', 'Osf\Validator', 'Validators general builder');
    }
}
