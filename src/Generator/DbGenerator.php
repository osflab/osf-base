<?php 
namespace Osf\Generator;

use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Adapter\Adapter;
use Osf\Container\AbstractContainer;
use Osf\Exception\ArchException;
use Osf\Stream\Text as T;
use Osf\Container\ZendContainer;
use Osf\Container\OsfContainer as C;

/**
 * Database models, forms and tables generator
 *
 * @version 1.0
 * @since OSF1_0 Thu Sep 23 10:42:28 GMT+01:00 2010
 * @author Guillaume Ponçon
 * @package openstates
 * @subpackage db
 */
class DbGenerator extends AbstractGenerator
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter = null;
    protected $params = [];
    protected $tablesComments = [];

    /**
     * @param Adapter $adapter
     * @param array $params
     * @throws ArchException
     */
    public function __construct(Adapter $adapter = null, $params = null)
    {
        if ($adapter === null) {
            $adapter = ZendContainer::getDbAdapter();
        }
        if (!$adapter || !($adapter instanceof Adapter)) {
            throw new ArchException('Adapter is required (auto detection failed), only pgsql for the moment');
        }
        $this->dbAdapter = $adapter;
        if ($params === null) {
            $params = [];
        }
        if (!isset($params['application_path'])) {
            $params['application_path'] = APP_PATH;
        }
        $this->params = $params;
    }

    public function generateClasses()
    {
        static $class = null;
        
        // Environnement des modèles
        if (isset($this->params['models_path'])) {
            $modelsPath = $this->params['models_path'];
        } else {
            $modelsPath = $this->params['application_path'] . '/backend/Sma/Db';
        }
        if (!is_dir($modelsPath)) {
            throw new ArchException('Models directory ' . $modelsPath . ' not found');
        }
        if (!is_writable($modelsPath)) {
            throw new ArchException('Models directory ' . $modelsPath . ' must be writable');
        }
        $generatedPath = $modelsPath . '/Generated';
        if (!is_dir($generatedPath)) {
            if (!@mkdir($generatedPath)) {
                throw new ArchException('Unable to create ' . $generatedPath . ' directory! Permission problem?');
            }
        }

        $metadata = new Metadata($this->dbAdapter);
        $comments = $this->dbAdapter->query('SELECT TABLE_NAME, COLUMN_NAME, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=? AND COLUMN_COMMENT <> \'\'', [$this->dbAdapter->getCurrentSchema()]);
        foreach ($comments->toArray() as $values) {
            $this->tablesComments[$values['TABLE_NAME']][$values['COLUMN_NAME']] = $values['COLUMN_COMMENT'];
        }
        
        // Récupération des contraintes sur clés étrangères basées sur un seul champ
        $internalConstraints = [];
        $externalConstraints = [];
        foreach ($metadata->getTableNames() as $table) {
            foreach ($metadata->getConstraints($table) as $constraint) {
                if ($constraint->getType() == 'FOREIGN KEY' && count($constraint->getColumns()) == 1) {
                    $internalConstraints[$table][$constraint->getName()] = $constraint;
                    $externalConstraints[$constraint->getReferencedTableName()][$constraint->getName()] = $constraint;
                }
            }
        }
        
        $currentSchema = $this->dbAdapter->getCurrentSchema();
        
        // Génération des tables
        $containerClasses = $this->generateFiles(
                $metadata->getTables(), $currentSchema, $generatedPath, 
                $metadata, $internalConstraints, $externalConstraints, 
                $modelsPath);
        
        // Génération des vues
        $containerClasses = array_merge($containerClasses, 
            $this->generateFiles($metadata->getViews(), $currentSchema, 
                    $generatedPath, $metadata, $internalConstraints, 
                    $externalConstraints, $modelsPath));
        
        // Conteneur de tables et de champs
        if ($class === null) {
            $class = $this->buildClass('AbstractDbContainer', 'AbstractDbContainerProxy', 'Table & View models container (NOT WRITABLE)');
            $class->setAbstract(true);
            $class->addProperty('instances', [], PropertyGenerator::FLAG_PROTECTED | PropertyGenerator::FLAG_STATIC);
            $class->addProperty('mockNamespace', AbstractContainer::MOCK_DISABLED, PropertyGenerator::FLAG_PROTECTED | PropertyGenerator::FLAG_STATIC);
        }
        foreach ($containerClasses as $tableName) {

            // getXxxTable()
            $classPath = "\\Sma\\Db\\" . $tableName . 'Table';
            $class->addMethod('get' . $tableName . 'Table', [], 
                PropertyGenerator::FLAG_PUBLIC | PropertyGenerator::FLAG_STATIC, 
                "return self::buildTableObject('" . $classPath . "');",
                '@return ' . $classPath);

            // buildXxxRow(...)
            $classPath = "\\Sma\\Db\\" . $tableName . 'Row';
            $p1 = new ParameterGenerator();
            $p1->setName('rowData')->setDefaultValue(null)->setType('array');
            $p2 = new ParameterGenerator();
            $p2->setName('rowExistsInDatabase')->setDefaultValue(false);
            $class->addMethod('build' . $tableName . 'Row', [$p1, $p2], 
                PropertyGenerator::FLAG_PUBLIC | PropertyGenerator::FLAG_STATIC, 
                "return self::buildRowObject('" . $classPath . "', \$rowData, \$rowExistsInDatabase);",
                '@return ' . $classPath);
        }
        $phpPrefix = "namespace Sma\\Db\\Generated;\n\n";
        $phpPrefix .= "use Osf\\Db\\AbstractDbContainerProxy;\n\n";
        $file = $modelsPath . '/Generated/AbstractDbContainer.php';
        $this->filePutClassContent($file, "<?php\n" . $phpPrefix . $class->generate(), 0644);

        $file = $modelsPath . '/DbContainer.php';
        if (!file_exists($file)) {
            $class = $this->buildClass('DbContainer', 'AbstractDbContainer', 'Table models container (writable)');
            $phpPrefix = "namespace Sma\\Db;\n\n";
            $phpPrefix .= "use Sma\\Db\\Generated\\AbstractDbContainer;\n\n";
            $this->filePutClassContent($file, "<?php\n" . $phpPrefix . $class->generate(), 0666);
        }
    }
    
    protected function generateFiles(
            $collection, 
            $currentSchema, 
            $generatedPath, 
            $metadata, 
            $internalConstraints, 
            $externalConstraints,
            $modelsPath)
    {
        $containerClasses = [];
        
        foreach ($collection as $tableOrView) {
            $isView = !($tableOrView instanceof TableObject);
            $tableType = 'Table'; // $isView ? 'View' : 'Table';
            $tableName = $tableOrView->getName();

            // Paramètres généraux
            $phpBegin = "<?php\n";

            $ucTable = T::camelCase($tableName);
            $containerClasses[] = $ucTable;
            $dbNamespacePrefix = "\\Sma\\Db\\";

            // Création du Table Gateway et du Row Gateway abstracts
            $generatedTableClass = 'Abstract' . $ucTable . $tableType;
            $generatedRowClass = 'Abstract' . $ucTable . 'Row';
            $longDescription = "WARNING: This class is generated automatically, do not update it manually!" .
            "\n         Please use " . $ucTable . $tableType . ' instead.';
            $classTable = $this->buildClass($generatedTableClass, 'Abstract' . $tableType . 'Gateway', $tableType . ' gateway for ' . $tableName, $longDescription);
            $longDescription = "WARNING: This class is generated automatically, do not update it manually!" .
                "\n         Please use " . $ucTable . 'Row instead.';
            $classRow = $this->buildClass($generatedRowClass, 'AbstractRowGateway', 'Row gateway for ' . $tableName, $longDescription);

            $classTable->setAbstract(true);
            $classRow->setAbstract(true);
            
            $classTable->addProperty('schemaKey', $this->getSchemaKey($currentSchema), PropertyGenerator::FLAG_PROTECTED);
            $classTable->addProperty('table', $tableName, PropertyGenerator::FLAG_PROTECTED);
            $classRow->addProperty('schemaKey', $this->getSchemaKey($currentSchema), PropertyGenerator::FLAG_PROTECTED);
            $classRow->addProperty('table', $tableName, PropertyGenerator::FLAG_PROTECTED);
            $fileTable = $generatedPath . '/' . $generatedTableClass . '.php';
            $fileRow   = $generatedPath . '/' . $generatedRowClass . '.php';

            // Récupération des clés primaires et des contraintes
            $primaries   = [];
            $constraints = [];
            foreach ($metadata->getConstraints($tableName) as $constraint) {
                if ($constraint->isPrimaryKey()) {
                    $primaries = array_merge($primaries, $constraint->getColumns());
                }
                $constraints[$constraint->getName()] = [
                    //'name' => $constraint->getName(),
                    'type'   => $constraint->getType(),
                    //'table'  => $constraint->getTableName(),
                    //'schema' => $constraint->getSchemaName(),
                    'referenced' => [
                        'schema'  => $constraint->getReferencedTableSchema() ? 'SCHEMAS::' . $this->getSchemaKey($constraint->getReferencedTableSchema()) : null,
                        'table'   => $constraint->getReferencedTableName(),
                        'columns' => $constraint->getReferencedColumns()
                    ],
                    'match'  => $constraint->getMatchOption(),
                    'update' => $constraint->getUpdateRule(),
                    'delete' => $constraint->getDeleteRule(),
                    'check'  => $constraint->getCheckClause()
                ];
            }
            $classRow->addProperty('primaryKeyColumn', $primaries, PropertyGenerator::FLAG_PROTECTED);

            // Récupération des triggers
            $triggers = [];
            foreach ($metadata->getTriggers() as $trigger) {
                //$trigger = new \Zend\Db\Metadata\Object\TriggerObject();
                //var_dump($trigger); exit;
                if ($trigger->getEventObjectTable() != $tableName) {
                    continue;
                }
                $triggers[$trigger->getName()] = [
                    'created' => (is_object($trigger->getCreated()) ? $trigger->getCreated()->getTimestamp() : null),
                    'event' => [
                        'manipulation' => $trigger->getEventManipulation(),
                        'catalog'      => $trigger->getEventObjectCatalog(),
                        //'schema'       => $trigger->getEventObjectSchema(),
                        //'table'        => $trigger->getEventObjectTable()
                    ],
                    'action' => [
                        'condition'   => $trigger->getActionCondition(),
                        'order'       => $trigger->getActionOrder(),
                        'orientation' => $trigger->getActionOrientation(),
                        'statement'   => $trigger->getActionStatement(),
                        'timing'      => $trigger->getActionTiming(),
                        'referenceNewRow'   => $trigger->getActionReferenceNewRow(),
                        'referenceNewTable' => $trigger->getActionReferenceNewTable(),
                        'referenceOldRow'   => $trigger->getActionReferenceOldRow(),
                        'referenceOldTable' => $trigger->getActionReferenceOldTable()
                    ]
                ];
            }

            // Row class pour l'hydrateur dans la table
            $rowClassValue = $dbNamespacePrefix . $ucTable . 'Row';
            $classTable->addProperty('rowClass', $rowClassValue, PropertyGenerator::FLAG_PROTECTED);

            // Champs et accesseurs
            $columns = $metadata->getColumns($tableName);
            $fields = [];
            $values = [];
            foreach ($columns as $column) {
                $maxLength = $column->getCharacterMaximumLength();
                $fields[$column->getName()]['isNullable'] = $column->isNullable();
                $fields[$column->getName()]['dataType'] = $column->getDataType();
                $fields[$column->getName()]['characterMaximumLength'] = $maxLength === null ? null : (int) $maxLength;
                $fields[$column->getName()]['comment'] = isset($this->tablesComments[$tableName][$column->getName()]) ? $this->tablesComments[$tableName][$column->getName()] : null;
                if ($column->getErrata('permitted_values')) {
                    $fields[$column->getName()]['permitted_values'] = $column->getErrata('permitted_values');
                }
                $values[$column->getName()] = $column->getColumnDefault();
                $classRow->addMethod('get' . T::camelCase($column->getName()), 
                                     [],
                                     MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_FINAL,
                                     'return $this->get(\'' . $column->getName() . '\');');
                $classRow->addMethod('set' . T::camelCase($column->getName()),
                                     ['value'],
                                     MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_FINAL,
                                     'return $this->set(\'' . $column->getName() . '\', $value);',
                                     "@return \\Sma\\Db\\" . $ucTable . 'Row');
                $classTable->addConstant('COL_' . strtoupper($column->getName()), $column->getName());
            }
            $classTable->addProperty('fields', $fields, PropertyGenerator::FLAG_PROTECTED);
            $classTable->addProperty('constraints', $constraints, PropertyGenerator::FLAG_PROTECTED);
            $classTable->addProperty('triggers', $triggers, PropertyGenerator::FLAG_PROTECTED);
            
            // Données spécifiques à la vue
            if ($isView) {
                $viewInfo = [
                    'definition' => $tableOrView->getViewDefinition(),
                    'check'      => $tableOrView->getCheckOption(),
                    'updatable'  => $tableOrView->getIsUpdatable()
                ];
                $classTable->addProperty('view', $viewInfo, PropertyGenerator::FLAG_PROTECTED);
            }

            // Méthodes d'accès aux enregistrements des clés étrangères internes
            if (array_key_exists($tableName, $internalConstraints)) {
                foreach ($internalConstraints[$tableName] as $constraint) {
                    //$constraint = new \Zend\Db\Metadata\Object\ConstraintObject();
                    $methodName  = 'getRelated' . T::camelCase($constraint->getReferencedTableName()) . 'RowFrom';
                    $methodName .= T::camelCase($constraint->getColumns()[0]) . 'Fk';
                    $tableGateway = "\\Sma\\Db\\DbContainer::get" . T::camelCase($constraint->getReferencedTableName()) . $tableType . '()';
                    $valueGetter = '$this->get' . T::camelCase($constraint->getColumns()[0]) . '()';
                    $methodBody = 'return $this->getInternalFkRow(' . $valueGetter . ', ' . $tableGateway . ', \'' . $constraint->getReferencedColumns()[0] . '\');';
                    $docBlock = "@return \\Sma\\Db\\" . T::camelCase($constraint->getReferencedTableName()) . 'Row';
                    $classRow->addMethod($methodName, [], MethodGenerator::FLAG_PUBLIC, $methodBody, $docBlock);
                }
            }

            // Méthodes d'accès aux enregistrements des clés étrangères externes
//            if (array_key_exists($tableName, $externalConstraints)) {
//                foreach ($externalConstraints[$tableName] as $constraint) {
//                    //$constraint = new \Zend\Db\Metadata\Object\ConstraintObject();
//                    $methodName  = 'getRelated' . T::camelCase($constraint->getTableName()) . 'RowsFrom';
//                    $methodName .= T::camelCase($constraint->getColumns()[0]) . 'Fk';
//                    $tableGateway = "\\Sma\\Db\\DbContainer::get" . T::camelCase($constraint->getTableName()) . $tableType . '()';
//                    $valueGetter = '$this->get' . T::camelCase($constraint->getReferencedColumns()[0]) . '()';
//                    $methodBody = 'return $this->getExternalFkRows(' . $tableGateway . ', \'' 
//                                . $constraint->getColumns()[0] . '\', ' 
//                                . $valueGetter . ', \'' . $constraint->getReferencedColumns()[0] . '\');';
//                    $docBlock = "@return \\Zend\\Db\\ResultSet\\HydratingResultSet";
//                    $classRow->addMethod($methodName, array(), MethodGenerator::FLAG_PUBLIC, $methodBody, $docBlock);
//                }
//            }

            // Méthode find
            $findMethod = new MethodGenerator();
            $docBlock   = new DocBlockGenerator();
            $returnType = $dbNamespacePrefix . $ucTable . 'Row';
            $docBlock->setTag(['name' => 'return', 'description' => $returnType]);
            $findMethod->setDocBlock($docBlock);
            $findMethod->setName('find');
            $findMethod->setParameter('id');
            $findMethod->setBody('return parent::find($id);');
            $classTable->addMethodFromGenerator($findMethod);

            //  Enregistrement des classes abstraites

            $phpPrefix = "namespace Sma\\Db\\Generated;\n\n";
            $usePrefix = "use Osf\\Db\\" . $tableType . "\\Abstract" . $tableType . "Gateway;\n\n";
            $this->filePutClassContent($fileTable, $phpBegin . $phpPrefix . $usePrefix . $classTable->generate(), 0644);

            $usePrefix = "use Osf\\Db\\Row\\AbstractRowGateway;\n\n";
            $this->filePutClassContent($fileRow, $phpBegin . $phpPrefix . $usePrefix . $classRow->generate(), 0644);

            // Création des classes filles si elles n'existent pas

            $file = $modelsPath . '/' . $ucTable . $tableType . '.php';
            if (!file_exists($file)) {
                $class = $this->buildClass($ucTable . $tableType, $generatedTableClass, $tableType . ' model for ' . strtolower($tableType) . ' ' . $tableName, "Use this class to complete " . $generatedTableClass);
                $phpPrefix = "namespace Sma\\Db;\n\n";
                $phpPrefix .= "use Sma\\Db\\Generated\\Abstract" . $ucTable . $tableType . ";\n\n";
                $this->filePutClassContent($file, $phpBegin . $phpPrefix . $class->generate(), 0666);
            }

            $file = $modelsPath . '/' . $ucTable . 'Row.php';
            if (!file_exists($file)) {
                $class = $this->buildClass($ucTable . 'Row', $generatedRowClass, 'Row model for table ' . $tableName, "Use this class to complete " . $generatedRowClass);
//                $class->addMethod('set', ['field', 'value', 'withFilters = true'], MethodGenerator::FLAG_PUBLIC, 'return parent::set($field, $value, $withFilters);', 'Put filters, validators and data cleaners here');
//                $class->addMethod('get', ['field'], MethodGenerator::FLAG_PUBLIC, 'return parent::get($field);', 'Put filters here');
                $phpPrefix = "namespace Sma\\Db;\n\n";
                $phpPrefix .= "use Sma\\Db\\Generated\\Abstract" . $ucTable . "Row;\n\n";
                $this->filePutClassContent($file, $phpBegin . $phpPrefix . $class->generate(), 0666);
            }
        }
        return $containerClasses;
    }
    
    
    /**
     * @param string $schema
     * @return string common / admin
     */
    protected function getSchemaKey(string $schema): string
    {
        return $this->getSchemaKeys()[$schema];
    }
    
    /**
     * @staticvar array $schemaKeys
     * @return array
     */
    protected function getSchemaKeys(): array
    {
        static $schemaKeys = [];
        
        if (!$schemaKeys) {
            $databases = C::getConfig()->getConfig('db');
            foreach ($databases as $key => $db) {
                $schemaKeys[$db['database']] = $key;
            }
        }
        return $schemaKeys;
    }
    
    /**
     * Cleaning
     * @param type $file
     * @param type $content
     * @param type $chmod
     */
    protected function filePutClassContent($file, $content, $chmod)
    {
        $from = [
            "/[\n]+(\n +const )/",
//            "/';\n(\n[ ]*protected)/",
            "/}\n([\n]*[ ]*protected)/",
            "/}\n[\n]+}/",
            "/array\([ \n]+\)/",
            "/'SCHEMAS::([^']+)'/"
        ];
        $to = [
            '$1',
//            "';$1",
            '}$1',
            "}\n}",
            '[]',
            "DB_SCHEMAS['$1']"
        ];
        foreach ($this->getSchemaKeys() as $db => $key) {
            $from[] = '/`' . $db . '`/';
            $to[] = "`' . DB_SCHEMAS['" . $key . "'] . '`";
        }
        $content = preg_replace($from, $to, $content);
        file_put_contents($file, trim($content));
        chmod($file, $chmod);
    }
}