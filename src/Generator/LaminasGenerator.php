<?php

namespace Osf\Generator;

use Laminas\Code\Generator\AbstractMemberGenerator;
use Osf\Exception\ArchException;
use Laminas\Code\Generator\ParameterGenerator;
use Laminas\Code\Reflection\MethodReflection;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use ReflectionException;

/**
 * Laminas resources generation for quick and fast access
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage zend
 */
class LaminasGenerator extends AbstractGenerator
{
    protected const ZF_VENDOR_PATH = '/../../vendor/laminas';
    
    protected function getLaminasVendorPath(): string
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
    protected function cleanLaminasType(?string $type): ?string
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

    /**
     * @return void
     * @throws ArchException
     * @throws ReflectionException
     */
    public function generateViewHelpers()
    {
        $znamespaces = [
            [
                'name' => "\\Laminas\\View\\Helper\\",
                'prefix' => '',
                'pattern' => '/view-view/src/Helper/*.php'],
            [
                'name' => "\\Laminas\\Form\\View\\Helper\\",
                'prefix' => '',
                'pattern' => '/zend-form/src/View/Helper/*.php'],
            [
                'name' => "\\Laminas\\Form\\View\\Helper\\Captcha\\",
                'prefix' => 'captcha',
                'pattern' => '/zend-form/src/View/Helper/Captcha/*.php']
        ];
        $class = $this->buildClass('AbstractLaminasHelper', 'AbstractHelper', 'Laminas Helpers builders');
        $class->setFlags(ClassGenerator::FLAG_ABSTRACT);
        $docBlock = $class->getDocBlock();
        $docBlock->setWordWrap(false);
        $methods = [];
        foreach ($znamespaces as $znamespace) {
            $classesPattern = $this->getLaminasVendorPath() . $znamespace['pattern'];
            $files = glob($classesPattern);
            foreach ($files as $file) {
                $className = basename($file, '.php');
                $helperMethod = $znamespace['prefix']
                    ? $znamespace['prefix'] . $className
                    : strtolower($className[0]) . substr($className, 1);
                if (str_starts_with($className, 'Abstract')
                    || str_ends_with($className, 'Interface')
                    || $className == 'ViewModel') {
                    continue;
                }
                //include_once $file;
                $methods[$helperMethod] = $znamespace['name'] . $className;
                $reflectionMethod = new MethodReflection($znamespace['name'] . $className, '__invoke');
                $parametersPrototypes = [];
                //$method = new MethodGenerator();
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $parameterType = $this->cleanLaminasType($parameter->getType());
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
        $class->addProperty('availableLaminasHelpers', $methods, PropertyGenerator::FLAG_PROTECTED);
        $namespace = 'Osf\View\Generated';
        $uses = ['Osf\View\AbstractHelper'];
        $fileName = __DIR__ . '/../View/Generated/AbstractLaminasHelper.php';
        $this->writeFile($fileName, $namespace, $class->generate(), $uses);
    }

    /**
     * @param $filesGlobPattern
     * @param $osfTargetDir
     * @param $zendNamespace
     * @param $osfNamespace
     * @param $subClassPrefix
     * @param $classesPrefix
     * @param $classes
     * @param $overwrite
     * @return array|mixed
     */
    protected function generateSubClasses(
            $filesGlobPattern,   // Pattern to find Laminas Classes to inherit
            $osfTargetDir,       // Target diretory to generate new subclasses
            $zendNamespace,      // Laminas namespace writed in 'use' section (begin/end with \)
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
                $class = $this->buildClass($subClassPrefix . $className, $className, 'Laminas Class ' . $className . ' inheritor');
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
        $class->addProperty('classes', $classes, AbstractMemberGenerator::FLAG_PROTECTED | AbstractMemberGenerator::FLAG_STATIC);
        foreach ($classes as $name => $className) {
            $class->addMethod('new' . $name, ['...$args'],
                AbstractMemberGenerator::FLAG_PUBLIC | AbstractMemberGenerator::FLAG_STATIC,
                'return self::get(\'' . $name . '\', $args, false);',
                '@return ' . $className);
            $class->addMethod('get' . $name, ['...$args'],
                AbstractMemberGenerator::FLAG_PUBLIC | AbstractMemberGenerator::FLAG_STATIC,
                'return self::get(\'' . $name . '\', $args, true);',
                '@return ' . $className);
        }
        $classSrc = str_replace('$...', '...', $class->generate());
        $this->writeFile($targetFile, $namespace, $classSrc);
    }

    /**
     * @throws ArchException
     */
    public function generateFormElements()
    {
        $this->generateSubClasses(
            $this->getLaminasVendorPath() . '/zend-form/src/Element/*.php',
            __DIR__ . '/../Form/Laminas/',
            "Laminas\Form\\Element\\", 
            "Osf\\Form\\Laminas", 
            'LaminasElement');
    }

    /**
     * @throws ArchException
     */
    public function generateFilters()
    {
        // Génération des filtres Laminas principaux
        $classes = $this->generateSubClasses(
            $this->getLaminasVendorPath() . '/zend-filter/src/*.php',
            __DIR__ . '/../Filter/Laminas/',
            "Laminas\Filter\\", 
            "Osf\\Filter\\Laminas", 
            'LaminasFilter');
        $subClasses = ['Compress', 'Encrypt', 'File', 'Word'];
        
        // Génération des filtres Laminas situés dans des dossiers à part
        foreach ($subClasses as $subClass) {
            $classes = $this->generateSubClasses(
                $this->getLaminasVendorPath() . '/zend-filter/src/' . $subClass . '/*.php',
                __DIR__ . '/../Filter/Laminas/',
                "Laminas\Filter\\" . $subClass . "\\",
                "Osf\\Filter\\Laminas",
                'LaminasFilter' . $subClass,
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

    /**
     * @throws ArchException
     */
    public function generateValidators()
    {
        // Validateurs principaux
        $classes = $this->generateSubClasses(
            $this->getLaminasVendorPath() . '/zend-validator/src/*.php',
            __DIR__ . '/../Validator/Laminas/',
            "Laminas\\Validator\\", 
            "Osf\\Validator\\Laminas", 
            'LaminasValidator');
        $subClasses = ['Barcode', 'File', 'Hostname'];
        
        // Sous-classes de validation
        foreach ($subClasses as $subClass) {
            $classes = $this->generateSubClasses(
                $this->getLaminasVendorPath() . '/zend-validator/src/' . $subClass . '/*.php',
                __DIR__ . '/../Validator/Laminas/',
                "Laminas\Validator\\" . $subClass . "\\", 
                "Osf\\Validator\\Laminas", 
                'LaminasValidator' . $subClass, 
                $subClass, 
                $classes);
        }
        
        // Validations i18n
        $classes = $this->generateSubClasses(
            $this->getLaminasVendorPath() . '/zend-i18n/src/Validator/*.php',
            __DIR__ . '/../Validator/Laminas/',
            "Laminas\\I18n\\Validator\\", 
            "Osf\\Validator\\Laminas", 
            'LaminasValidatorI18n', 
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
