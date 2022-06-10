<?php 

function usage($error = null)
{
    if ($error) {
        echo 'Err: ' . $error . "\n";
    }
    echo "Usage : generate_prototypes.php <extension>\n";
    exit;
}

if (!isset($_SERVER['argv'][1])) {
    usage();
}
$extName = $_SERVER['argv'][1];
if (!extension_loaded($extName)) {
    usage('Extension ' . $extName . ' not found.');
}

$baseDir = __DIR__ . '/' . $extName;
if (is_dir($baseDir)) {
    exec('rm -rf ' . escapeshellarg($baseDir));
}
mkdir($baseDir);
$baseDir .= '/';

$extension = new ReflectionExtension($extName);

foreach ($extension->getClasses() as $class) {
    
    //$class = new ReflectionClass();
    
    $data = "<?php \n\n";
    $data .= "class " . $class->getName() . ($class->getParentClass() ? ' extends ' . $class->getParentClass()->getName() : '') . "\n"; 
    $data .= "{\n";
    
    foreach ($class->getConstants() as $constant => $value) {
        $data .= "    const $constant = $value;\n";
    }
    $data .= "\n";
    
    foreach ($class->getProperties() as $property) {
        //$property = new ReflectionProperty();
        if ($property->isPrivate() || $property->isProtected()) {
            continue;
        }
        $data .= '    public $' . $property->getName() . ";\n"; // . ($property->getValue() ? ' = ' . $property->getValue() : '') . ";\n";
    }
    $data .= "\n";
    
    foreach ($class->getMethods() as $method) {
        //$method = new ReflectionMethod(); 
        if ($method->isPrivate() || $method->isProtected()) {
            continue;
        }
        $data .= '    public function ' . $method->getName() . '(';
        $params = array();
        foreach ($method->getParameters() as $parameter) {
            //$parameter = new ReflectionParameter();
            $params[] = '$' . $parameter->getName();
        }
        $data .= implode(', ', $params);
        $data .= ') {}' . "\n";
    }
    
    $data .= "}\n";
    file_put_contents($baseDir . $class->getName() . '.php', $data);
}

echo "done...\n";