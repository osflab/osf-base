<?php
namespace Osf\Github;

use Osf\Exception\ArchException;
use Osf\Github\Sync\Components;
use Osf\Github\Sync\Component;
use Osf\Console\ConsoleHelper as Cli;

/**
 * Github sources generator and filter
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage github
 */
class Sync
{
    use Addon\Templates;
    
    protected static $composerFileNames = [];
    protected static $composerFiles = [];
    
    public static function run(): void
    {
        self::runComponents();
        self::runFramework();
    }
    
    /**
     * Build components for github repository
     * @return void
     */
    protected static function runComponents(): void
    {
		$components = [];
        $from = self::getFrom();
        $to = self::getTo();
		$files = glob($from . '/src/*');
        self::$composerFiles = [];
        
		foreach ($files as $file) {
			$component = basename($file, '.php');
			if (isset($components[$component])) {
				continue;
			}
            if (!in_array($component, Components::COMPONENT_LIST)) {
                self::display('[' . Cli::red() . 'IGN' . Cli::resetColor() . '] ' . $component . "\n");
                continue;
            }
			
            // Component name and dir
			$componentDir = $to . '/src/' . $component;
			if (!is_dir($componentDir)) {
                self::display('[' . Cli::blue() . 'CPN' . Cli::resetColor() . '] ' . $component . "\n");
				mkdir($componentDir, 0750, true);
			}
						
            // Copy the component base file and directory
			if (is_dir($from . '/src/' . $component)) {
				self::runDirectory('', $from . '/src/' . $component, $componentDir);
			}
            
            // Building composer.json
            self::buildComposer($component, $componentDir . '/composer.json');
            
            // Build readme
            $readmeFile = $componentDir . '/README.md';
            if (!file_exists($readmeFile)) {
                file_put_contents($readmeFile, self::buildReadme($component));
            }
            
            // Build gitignore
            $gitignoreFile = $componentDir . '/.gitignore';
            if (!file_exists($gitignoreFile)) {
                file_put_contents($gitignoreFile, "composer.lock\nvendor/\nnbproject\n");
            }
		}
        
        foreach (self::$composerFiles as $file => $composer) {
            if (!isset($composer['require']) || !$composer['require']) {
                continue;
            }
            foreach ($composer['require'] as $componentName => $version) {
                if (!preg_match('/^osflab.*$/', $componentName)) {
                    continue;
                }
                if (!isset(self::$composerFileNames[$componentName])) {
                    throw new ArchException('Component [' . $componentName . '] not found');
                }
                if (!isset(self::$composerFiles[self::$composerFileNames[$componentName]]['provide'])) {
                    self::$composerFiles[self::$composerFileNames[$componentName]]['provide'] = [];
                }
                self::$composerFiles[self::$composerFileNames[$componentName]]['provide'][$composer['name']] = Component::OSF_VERSION;
            }
        }
        
        foreach (self::$composerFiles as $file => $composer) {
            self::jsonRecordFile($file, array_filter($composer));
        }
	}
    
    /**
     * Build global framework parameters
     * @return void
     */
    protected static function runFramework(): void
    {
        // Composer
        $fwcomposer = self::getFrameworkComposerTemplate();
        foreach (self::$composerFiles as $composer) {
            $fwcomposer['replace'][$composer['name']] = 'self.version';
        }
        self::mergeDepends(self::$composerFiles, 'require', $fwcomposer);
        self::mergeDepends(self::$composerFiles, 'require-dev', $fwcomposer);
        self::mergeDepends(self::$composerFiles, 'conflict', $fwcomposer);
        self::mergeDepends(self::$composerFiles, 'provide', $fwcomposer);
        self::mergeDepends(self::$composerFiles, 'suggest', $fwcomposer);
        self::appendFrameworkComponents($fwcomposer);
        $fwcomposer = array_filter($fwcomposer);
        self::jsonRecordFile(self::getFrom() . '/composer.json', $fwcomposer);
        self::jsonRecordFile(self::getTo() . '/composer.json', $fwcomposer);
        
        // Bin directory
        $binFiles = glob(realpath(self::getFrom() . '/bin') . '/*');
        foreach ($binFiles as $binFile) {
            if (!is_executable($binFile) || is_dir($binFile)) {
                continue;
            }
            $binFileName = basename($binFile);
            if (in_array($binFileName, ['show-deps', 'sync-github'])) {
                continue;
            }
            self::display('[' . Cli::green() . 'BIN' . Cli::resetColor() . '] ' . $binFileName . "\n");
            $toFile = self::getTo() . '/bin/' . $binFileName;
            copy($binFile, $toFile);
            chmod($toFile, 0750);
        }
    }
    
    protected static function mergeDepends(array $composers, string $key, array &$fwcomposer): void
    {
        foreach ($composers as $composer) {
            if (!isset($composer[$key])) {
                continue;
            }
            foreach ($composer[$key] as $item => $comment) {
                if (substr($item, 0, 7) !== 'osflab/') {
                    $fwcomposer[$key][$item] = $comment;
                }
            }
        }
        ksort($fwcomposer[$key]);
    }
    
    protected static function appendFrameworkComponents(array &$fwcomposer): void
    {
        $adds = Components::getFrameworkAdds();
        foreach ($adds as $name) {
            $fwcomposer['require'][$name] = Component::EXTERNALS[$name];
        }
        foreach (array_keys($fwcomposer['require']) as $name) {
            unset($fwcomposer['suggest'][$name]);
        }
        ksort($fwcomposer['require']);
    }
    
    protected static function runDirectory(string $dir, string $from, string $to): void
    {
        // self::display('-> ' . $dir . "\n");
        $files = glob(rtrim($from . '/' . $dir, '/') . '/*');
        is_dir($to) || mkdir($to, 0755, true);
        $toExistingFiles = glob(rtrim($to . '/' . $dir, '/') . '/*');
        $filesBaseNames = ['composer.json', '.gitignore', 'README.md', 'LICENSE', 'vendor', 'nbproject', 'composer.lock'];
        foreach ($files as $file) {
            $fileBaseName = basename($file);
            if (in_array($fileBaseName, ['composer.lock', 'nbproject'])) {
                self::display('[IGN] ' . $file . "\n");
                continue;
            }
            $filesBaseNames[] = $fileBaseName;
            $relFilePath = ltrim($dir . '/' . $fileBaseName, '/');
            $toFile = $to . '/' . $relFilePath;
            switch (true) {
                case substr($file, -4) === '.php' && preg_match('/^[A-Z]$/', basename($file)[0]) :
                    $fromFile = $from . '/' . $relFilePath;
                    file_put_contents($toFile, self::filterCode(file_get_contents($fromFile)));
                    self::display('[' . Cli::blue() . 'PHP' . Cli::resetColor() . '] ' . $relFilePath . "\n");
                    break;
                case is_dir($file) : 
                    is_dir($toFile) || mkdir($toFile, 0755, true);
                    self::display('[' . Cli::yellow() . 'DIR' . Cli::resetColor() . '] ' . $relFilePath . "\n");
                    self::runDirectory($relFilePath, $from, $to);
                    break;
                default : 
                    copy($from . '/' . $relFilePath, $toFile);
                    chmod($toFile, fileperms($from . '/' . $relFilePath));
                    self::display('[' . Cli::green() . 'CPY' . Cli::resetColor() . '] ' . $relFilePath . "\n");
                    break;
            }
        }
        // Cleaning
        foreach ($toExistingFiles as $file) {
            $fileBaseName = basename($file);
            if (!in_array($fileBaseName, $filesBaseNames)) {
                self::display('[' . Cli::red() . 'DEL' . Cli::resetColor() . '] ' . $file . "\n");
                passthru('rm -rf ' . escapeshellarg($file));
            }
        }
    }
    
    protected static function filterCode($code): string
    {
        $me = 'Guillaume Ponçon <guillaume.poncon@openstates.com>';
        
        if (substr($code, 0, 5) !== '<?php') {
            throw new ArchException('Not a PHP file');
        }
        $code = "<?php

/*
 * This file is part of the OpenStates Framework (osf) package.
 * (c) " . $me . "
 * For the full copyright and license information, please read the LICENSE file distributed with the project.
 */
" . substr($code, 5);
        $code = preg_replace("#([\n\t ]*// [^\n]+\n)+#s", "\n", $code);
        
        return trim($code) . "\n";
    }
    
    protected static function buildComposer(string $component, string $fileName): array
    {
        $composer = array_replace_recursive(
                self::getComponentComposerTemplate($component), 
                Components::getComponentInfo($component));
        self::$composerFiles[$fileName] = $composer;
        self::$composerFileNames[$composer['name']] = $fileName;
        return $composer;
    }
    
    /**
     * Default readme file if not exists
     * @param string $component
     * @return string
     */
    protected static function buildReadme(string $component): string
    {
        return $content = '# OSF ' . $component . ' component' . "\n\nUnder development\n";
    }
    
    protected static function display($txt): void
    {
        echo $txt;
    }
    
    /**
     * Save in json format with composer.json optimized parameters
     * @param string $file
     * @param array $data
     * @return bool
     */
    protected static function jsonRecordFile(string $file, array $data): bool
    {
        return (bool) file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Source framework root path
     * @return string
     */
    protected static function getFrom(): string
    {
        return realpath(__DIR__ . '/../..');
    }
    
    /**
     * Target framework root path
     * @return string
     */
    protected static function getTo(): string
    {
        return '/web/library/github';
    }
}
