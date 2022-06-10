<?php
namespace Osf\Github;

use Osf\Console\ConsoleHelper as Console;

class SubtreeManager extends Console
{
    /**
     * @var SubtreeConfig
     */
    protected static $config;
    
    /**
     * @var string
     */
    protected static $buffer = '';
    
    /**
     * Push commits to the current branch
     */
    protected static function pushAction()
    {
        $cmd = 'git subtree push '
             . '--prefix=' . self::$config->getComponentsPrefixDir() . '/[dir] '
             . self::$config->getGithubComponentsAddress() . '/[key].git ' . self::$config->getCurrentBranch();
        self::execComponents($cmd, 'Push on origin/' . self::$config->getCurrentBranch() . ': ' . self::$config->getComponentsPrefixDir() . '/[dir] -> [key]');
        self::exec('git push origin ' . self::$config->getCurrentBranch(), 'Push container on origin/' . self::$config->getCurrentBranch());
    }
    
    /**
     * Rebase origin/master with current branch
     */
    protected static function rebaseAction()
    {
        $currentBranch = self::$config->getCurrentBranch();
        self::exec('git checkout master', 'Switch to master branch');
        self::exec('git rebase ' . $currentBranch, 'Rebase from ' . $currentBranch);
        
        $cmd = 'git subtree push '
                . '--prefix=' . self::$config->getComponentsPrefixDir() . '/[dir] '
                . self::$config->getGithubComponentsAddress() . '/[key].git master';
        self::execComponents($cmd, 'Push on origin/master ' . self::$config->getComponentsPrefixDir() . '/[dir] -> [key] ');
        
        self::exec('git push origin master', 'Push container on origin master');
        self::exec('git checkout ' . $currentBranch, 'Switch to ' . $currentBranch);
    }
    
    /*
     * git checkout master;
git rebase 3.0;
git subtree push --prefix=src/Cache ssh://git@guillaume-xubuntu:2222/git-server/repos/Cache.git master;
git subtree push --prefix=src/Application ssh://git@guillaume-xubuntu:2222/git-server/repos/Application.git master;
git push;
git checkout 3.0;


     */
    
    protected static function prepend()
    {
        echo self::beginActionMessage('Parameters check');
        if (self::$config === null) {
            self::displayError('no configuration found');
        }
        if (!is_dir(self::$config->getWorkDir())) {
            self::displayError('work dir not found [' . self::$config->getWorkDir() . ']');
        }
        $workDirWithPrefix = self::$config->getWorkDir() . DIRECTORY_SEPARATOR . self::$config->getComponentsPrefixDir();
        if (!is_dir($workDirWithPrefix)) {
            self::displayError('work dir with prefix not found [' . $workDirWithPrefix . ']');
        }
        if (!self::$config->getComponents()) {
            self::displayError('no component found');
        }
        foreach (self::$config->getComponents() as $key => $value) {
            $cpnDir = $workDirWithPrefix . DIRECTORY_SEPARATOR . $value;
            if (!is_dir($cpnDir)) {
                self::displayError('component directory [' . $cpnDir . '] not found');
            }
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $key)) {
                self::displayError('component name [' . $key . '] syntax unknown');
            }
        }
        if (!preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_.-]+:[a-zA-Z0-9_-]+$/', self::$config->getGithubComponentsAddress())) {
            self::displayError('git component address syntax unknown');
        }
        if (!preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_.-]+:[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+$/', self::$config->getGithubContainerAddress())) {
            self::displayError('git container address syntax unknown');
        }
        echo self::endActionOK();
    }
    
    protected static function execComponents(string $cmd, string $msg)
    {
        foreach (self::$config->getComponents() as $key => $dir) {
            $cmdToExecute = str_replace(['[key]', '[dir]'], [$key, $dir], $cmd);
            $msgToDisplay = str_replace(['[key]', '[dir]'], [$key, $dir], $msg);
            self::exec($cmdToExecute, $msgToDisplay);
        }
    }
    
    protected static function exec(string $cmd, string $msg)
    {
        self::$buffer .= "\n" . str_repeat('-', 80) . "\n" . $cmd . "\n" . str_repeat('-', 80) . "\n";
        ob_start();
        $return = null;
        passthru('cd ' . escapeshellarg(self::$config->getWorkDir()) . ' && ' . $cmd, $return);
        self::$buffer .= ob_get_clean();
        echo self::beginActionMessage($msg);
        if ($return) {
            echo self::endActionFail();
            echo self::$buffer;
            exit($return);
        }
        echo self::endActionOK();
    }
    
    /**
     * Copy this function in classes extended classes
     * @return string
     */
    protected static function getCurrentClass()
    {
        return __CLASS__;
    }
    
    public static function run()
    {
        self::prepend();
        parent::run();
        $file = tempnam(sys_get_temp_dir(), 'gitsubtree-');
        file_put_contents($file, self::$buffer);
        self::display('Output: ' . $file);
    }
    
    /**
     * @param SubtreeConfig $config
     */
    public static function setConfig(SubtreeConfig $config)
    {
        self::$config = $config;
    }
}
