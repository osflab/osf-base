<?php
namespace Osf\Generator;

use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Reflection\MethodReflection;
use Zend\Code\Generator\ClassGenerator;

/**
 * Osf resources generation for quick and fast access
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage zend
 */
class OsfGenerator extends AbstractGenerator
{
    protected $namespaces = [
            [
                'name' => "\\Osf\\View\\Helper\\",
                'prefix' => '',
                'pattern' => '/View/Helper/*.php'
            ],
            [
                'name' => "\\Osf\\View\\Helper\\Bootstrap\\",
                'prefix' => '',
                'pattern' => '/View/Helper/Bootstrap/*.php'
            ],
            [
                'name' => "\\Osf\\Form\\Helper\\",
                'prefix' => '',
                'pattern' => '/Form/Helper/*.php'
            ],
        ];
    
    protected $blackList = [
            'Test',
            'AbstractHelper',
            'AbstractViewHelper'
        ];
    
    protected $namespace = "Osf\\View\\Generated";
    protected $classFile = __DIR__ . '/../View/Generated/AbstractGeneratedViewHelper.php';
    protected $classUses = ['\Osf\View\AbstractHelper'];
    protected $classExtends = 'AbstractHelper';
    protected $staticClassFile = __DIR__ . '/../View/Generated/StaticGeneratedViewHelper.php';
    protected $staticClassUses = ['\Osf\View\AbstractStaticHelper'];
    protected $staticClassExtends = 'AbstractStaticHelper';

    public function generateAll()
    {
        $this->generateViewHelpers();
    }

    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    public function generateViewHelpers()
    {
        $blackList = $this->blackList;
        $namespaces = $this->namespaces;
        $class = $this->buildClass('AbstractGeneratedViewHelper', $this->classExtends, 'Osf Helpers builders');
        $class->setFlags(ClassGenerator::FLAG_ABSTRACT);
        $staticClass = $this->buildClass('StaticGeneratedViewHelper', $this->staticClassExtends, 'Static helpers (quick access)');
        $staticClass->setFlags(ClassGenerator::FLAG_ABSTRACT);
        $docBlock = $class->getDocBlock();
        $docBlock->setWordWrap(false);
        $methods = [];
        $propertiesBlocs = [];
        $methodsBlocs = [];
        foreach ($namespaces as $namespace) {
            $classesPattern = $this->getBasePath() . $namespace['pattern'];
            $files = glob($classesPattern);
            foreach ($files as $file) {
                $className = basename($file, '.php');
                if (in_array($className, $blackList)) {
                    continue;
                }
                $helperMethod = $namespace['prefix'] ? $namespace['prefix'] . $className : strtolower($className{0}) . substr($className, 1);
                if (substr($className, 0, 8) == 'Abstract' 
                        || substr($className, - 9) == 'Interface' 
                        || in_array($className, ['ViewModel'])) {
                    continue;
                }
                include_once $file;
                $methods[$helperMethod] = $namespace['name'] . $className;
                $reflectionMethod = new MethodReflection($namespace['name'] . $className, '__invoke');
                $parametersPrototypes = [];
                $method = new MethodGenerator();
                $zparameters = [];
                $zparametersNames = [];
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $parameterType = $this->cleanType($parameter->getType());
                    $zparameter = new ParameterGenerator($parameter->getName(), $parameterType);
                    if ($parameter->isDefaultValueAvailable()) {
                        $zparameter->setDefaultValue($parameter->getDefaultValue());
                    }
                    $parametersPrototypes[] = trim($zparameter->generate());
                    $zparameters[] = $zparameter;
                    $zparametersNames[] = (string) '$' . $parameter->getName();
                }
                $propertyPrototype = $namespace['name'] . $className . ' $' . $helperMethod;
                $propertiesBlocs[] = [
                    'name' => 'property',
                    'description' => $propertyPrototype
                ];
                $methodDocBlock = $reflectionMethod->getDocBlock();
                if (is_object($methodDocBlock)) {
                    $methodType = preg_replace('/^.*@return ([a-zA-Z\\\\_]+).*$/s', '$1', $methodDocBlock->getContents());
                }
                $methodType = $methodType ? $methodType : 'string';
                $methodPrototype =  $methodType . ' ' . $helperMethod;
                $methodPrototype .= '(' . implode(', ', $parametersPrototypes) . ')';
                $methodsBlocs[] = [
                    'name' => 'method',
                    'description' => $methodPrototype
                ];
                
                $staticClass->addMethod(
                    $helperMethod, 
                    $zparameters, 
                    MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC, 
                    'return self::callHelper(\'' . $helperMethod . '\', [' . implode(', ', $zparametersNames) . ']);', 
                    '@return ' . $methodType);
            }
        }
        foreach ($propertiesBlocs as $tag) {
            $docBlock->setTag($tag);
        }
        foreach ($methodsBlocs as $tag) {
            $docBlock->setTag($tag);
        }
        $methodsBody = 'return array_merge(parent::getAvailableHelpers(), ' . var_export($methods, true) . ');';
        $class->addMethod('getAvailableHelpers', [], MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC, $methodsBody);
        $namespace = $this->namespace;
        $this->writeFile(
                $this->classFile, 
                $namespace, 
                $class->generate(), 
                $this->classUses);
        $this->writeFile(
                $this->staticClassFile, 
                $namespace, 
                $staticClass->generate(), 
                $this->staticClassUses);
    }
}
