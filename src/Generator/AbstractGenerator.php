<?php
namespace Osf\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Osf\Generator\Zend\ParameterGenerator;

use ReflectionClass;
use ReflectionMethod;

/**
 * Generators super class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage generator
 */
abstract class AbstractGenerator
{
    /**
     * @param string $className
     * @param string $extends
     * @param string $description
     * @param string $longDescription
     * @return \Zend\Code\Generator\ClassGenerator
     */
    protected function buildClass($className, $extends, $description, $longDescription = null)
    {
        $class = new ClassGenerator();
        $class->setName($className);
        $class->setExtendedClass($extends);
        $docTab = ['shortDescription' => $description];
        $docTab['longDescription'] = $longDescription;
        $docTab['tags'] = [
            ['name' => 'version', 'description' => '1.0'],
            ['name' => 'author', 'description' => 'Guillaume Ponçon - OpenStates Framework PHP Generator'],
            ['name' => 'since', 'description' => 'OSF 3.0.0'/*date('d/m/Y H:i:s')*/],
            ['name' => 'package', 'description' => 'osf'],
            ['name' => 'subpackage', 'description' => 'generated'],
        ];
        $class->setDocblock(new DocBlockGenerator($docTab['shortDescription'], $docTab['longDescription'], $docTab['tags']));
        return $class;
    }
    
    /**
     * Write a class file
     * @param string $filePath
     * @param string $namespace
     * @param string $content
     * @param array $uses
     * @return number
     */
    protected function writeFile($filePath, $namespace, $content, array $uses = [])
    {
        $data = '<?php' . ($namespace ? "\nnamespace " . $namespace . ';' : '') . "\n\n";
        if (count($uses)) {
            foreach ($uses as $use) {
                $data .= "use " . $use . ";\n";
            }
            $data .= "\n";
        }
        $data .= trim(preg_replace("/\n\n\n+/", "\n\n", $content));
        return file_put_contents($filePath, $data);
    }
    
    protected function generateStaticClass(
            array  $classesToParse,
            array  $containerAccessors,
            array  $uses,
            string $namespace,
            string $className,
            string $fileName,
            string $comment,
            bool   $static,
            array  $blackList,
            string $parentClass = null
            )
    {
        if (count($classesToParse) !== count($containerAccessors)) {
            throw new \Exception('Each class to parse must have an container accessor');
        }
        foreach ($classesToParse as $class) {
            if (!class_exists($class)) {
                throw new \Exception('Class [' . $class . '] do not exists');
            }
        }
        
        if (!$parentClass) {
            $uses[] = 'Osf\Container\AbstractStaticContainer';
            $parentClass = 'AbstractStaticContainer';
        }
        $comment .= "\n\nThis class is generated, do not edit it";
        $class = $this->buildClass($className, $parentClass, $comment);
        $class->setFlags(ClassGenerator::FLAG_ABSTRACT);
        $docBlock = $class->getDocBlock();
        $docBlock->setWordWrap(false);
        
        foreach ($classesToParse as $key => $classToParse) {
            $reflectionClass = new ReflectionClass($classToParse);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $reflectionMethod) {
                if (substr($reflectionMethod->getShortName(), 0, 2) == '__'
                ||  in_array($reflectionMethod->getShortName(), $blackList)
                ||  $reflectionMethod->isAbstract()
                || ($static && !$reflectionMethod->isStatic())
                || (!$static && $reflectionMethod->isStatic())) {
                    continue;
                }
                $parametersPrototypes = [];
                $method = new MethodGenerator();
                $zparameters = [];
                $zparametersNames = [];
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $parameterType = $parameter->getType() ? $this->cleanType((string) $parameter->getType()) : null;
                    $zparameter = new ParameterGenerator($parameter->getName(), $parameterType);
                    if ($parameter->isDefaultValueAvailable()) {
                        $zparameter->setDefaultValue($parameter->getDefaultValue());
                    }
                    $parametersPrototypes[] = trim($zparameter->generate());
                    $zparameters[] = $zparameter;
                    $zparametersNames[] = (string) '$' . $parameter->getName();
                }
                $methodDocBlock = $reflectionMethod->getDocComment();
                if ($methodDocBlock) {
                    $methodDocBlock = trim(preg_replace('# *[/\n] *\*+ +#', "\n", $methodDocBlock), " /*\n");
                    $methodDocBlock = str_replace('$this', $classToParse, $methodDocBlock);
                }
                
                $class->addMethod(
                    $reflectionMethod->getShortName(), 
                    $zparameters, 
                    MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC, 
                    'return ' . $containerAccessors[$key] 
                              . ($static ? '::' : '->') 
                              . $reflectionMethod->getShortName() 
                              . '(' . implode(', ', $zparametersNames) . ');', 
                    $methodDocBlock);
                
                
            }
        }
        $this->writeFile($fileName, $namespace, $class->generate(), $uses);
    }
    
    /**
     * Data types cleaner
     * @param ?string $type
     * @return string|null
     */
    protected function cleanType(?string $type): ?string
    {
        if (!$type || in_array(strtolower($type), ['null', 'mixed']) || strpos($type, '|')) {
            return null;
        }
        $lowerType = strtolower($type);
        if (in_array(ltrim($lowerType, '?'), [
            'array', 'int', 'integer', 'bool', 'boolean', 'float', 'double', 
            'real', 'string', 'object', 'unset', 'callback'
        ])) {
            return $lowerType;
        }
        return '\\' . ltrim($type, '\\');
    }
}
