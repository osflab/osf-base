<?php
namespace Osf\Github;

class SubtreeConfig
{
    const DEFAULT_COMPONENTS_PREFIX_DIR = 'src';
    
    protected $workDir;
    protected $currentBranch;
    protected $githubComponentsAddress;
    protected $githubContainerAddress;
    protected $componentsPrefixDir = self::DEFAULT_COMPONENTS_PREFIX_DIR;
    protected $components = [];
    
    /**
     * @param string $workDir
     * @return $this
     */
    public function setWorkDir(string $workDir)
    {
        $this->workDir = $workDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorkDir(): string
    {
        return $this->workDir;
    }
    
    /**
     * @param string $currentBranch
     * @return $this
     */
    public function setCurrentBranch(string $currentBranch)
    {
        $this->currentBranch = $currentBranch;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentBranch(): string
    {
        return $this->currentBranch;
    }
    
    /**
     * @param string $githubComponentsAddress
     * @return $this
     */
    public function setGithubComponentsAddress(string $githubComponentsAddress)
    {
        $this->githubComponentsAddress = $githubComponentsAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getGithubComponentsAddress(): string
    {
        return $this->githubComponentsAddress;
    }
    
    /**
     * @param string $githubContainerAddress
     * @return $this
     */
    public function setGithubContainerAddress(string $githubContainerAddress)
    {
        $this->githubContainerAddress = $githubContainerAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getGithubContainerAddress(): string
    {
        return $this->githubContainerAddress;
    }
    
    /**
     * @param string $componentsPrefixDir
     * @return $this
     */
    public function setComponentsPrefixDir(string $componentsPrefixDir)
    {
        $this->componentsPrefixDir = $componentsPrefixDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getComponentsPrefixDir(): string
    {
        return $this->componentsPrefixDir;
    }
    
    /**
     * @param array $components
     * @return $this
     */
    public function setComponents(array $components = [])
    {
        $this->components = $components;
        return $this;
    }

    /**
     * @return array
     */
    public function getComponents()
    {
        return $this->components;
    }
}
