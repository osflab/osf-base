<?php
namespace Osf\Application;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

/**
 * High level ACL for OSF webapps
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage acl
 */
class Acl extends ZendAcl
{
    // Resource separator: controller_action
    const RSEP = '_';
    
    const ROLE_PUBLIC     = 'PUBLIC';    // Everyone
    const ROLE_LOGGED     = 'LOGGED';    // Only logged in users
    const ROLE_NOT_LOGGED = 'NOTLOGGED'; // Not logged in users
    const ROLE_ADMIN      = 'ADMIN';     // Administrators (no limit)
    
    protected $builded = false;
    protected $aclConfigFile;
    protected $config;
    
    /**
     * @param string $aclConfigFile
     */
    public function __construct(string $aclConfigFile)
    {
        $this->aclConfigFile = $aclConfigFile;
    }
    
    /**
     * @return array
     */
    protected function getConfig(): array
    {
        if (!$this->config) {
            $this->config = include $this->aclConfigFile;
        }
        return $this->config;
    }
    
    /**
     * Build acl if needed
     * @return $this
     */
    public function buildAcl()
    {
        // Lazy loading
        if ($this->builded) {
            return $this;
        }
        
        // Resources
        $config = $this->getConfig();
        
        // Roles communs
        $this->buildCommonRoles()->deny(self::ROLE_PUBLIC);
        
        // Controllers
        foreach ($config['controller'] as $controller => $roles) {
            $this->addResource($controller);
            $this->allows($roles, $controller);
        }
        
        // Actions
        foreach ($config['action'] as $controller => $actions) {
            foreach ($actions as $action => $roles) {
                $resource = $this->buildResource($controller, $action);
                if (!$roles) {
                    $this->addResource($resource, $controller);
                } else {
                    $this->addResource($resource);
                }
                $this->allows($roles, $resource);
            }
        }
        
        // Administrators
        foreach ($config['admin'] as $admin) {
            if (!$this->hasRole($admin)) {
                $this->addRole($admin, [self::ROLE_ADMIN, self::ROLE_LOGGED, self::ROLE_PUBLIC]);
            }
            $this->allow($admin);
        }
        
        $this->builded = true;
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function buildCommonRoles()
    {
        $this->addRole(new Role(self::ROLE_PUBLIC));
        $this->addRole(new Role(self::ROLE_LOGGED), self::ROLE_PUBLIC);
        $this->addRole(new Role(self::ROLE_NOT_LOGGED), self::ROLE_PUBLIC);
        $this->addRole(new Role(self::ROLE_ADMIN), [self::ROLE_LOGGED, self::ROLE_PUBLIC]);
        return $this;
    }
    
    /**
     * Multiple rules
     * @param array|string $roles
     * @param array|string $resources
     * @return $this
     */
    protected function allows($roles, $resources = null)
    {
        $roles = is_array($roles) ? $roles : [$roles];
        foreach ($roles as $role) {
            if (!$role) {
                continue;
            }
            if (!$this->hasRole($role)) {
                $this->addRole($role, 'LOGGED');
            }
            $this->allow($role, $resources);
        }
        return $this;
    }
    
    /**
     * Is current user (default) is admin ?
     * @param string $email
     * @return bool
     */
    public function isAdmin(?string $email = null): bool
    {
        if ($email === null) {
            return false;
        }
        return in_array($email, $this->getConfig()['admin']);
    }
    
    /**
     * Is current user (defaut) allowed to access request params ?
     * @param string $controller
     * @param string $action
     * @param string $role
     * @return bool
     */
    public function isAllowedParams(?string $controller = null, ?string $action = null, ?string $role = null): bool
    {
        return $this->isAllowed($role, $this->buildResource($controller, $action));
    }
    
    /**
     * Construit le nom de la ressource à partir du contrôleur et de l'action
     * @param string|null $controller
     * @param string|null $action
     * @return string|null
     */
    public function buildResource(?string $controller = null, ?string $action = null):?string
    {
        return $controller !== null ? $controller . ($action !== null ? self::RSEP . $action : '') : null;
    }
    
    // Zend Acl heritages
    
    /**
     * Returns true if and only if the Role has access to the Resource
     *
     * The $role and $resource parameters may be references to, or the string identifiers for,
     * an existing Resource and Role combination.
     *
     * If either $role or $resource is null, then the query applies to all Roles or all Resources,
     * respectively. Both may be null to query whether the ACL has a "blacklist" rule
     * (allow everything to all). By default, Zend\Permissions\Acl creates a "whitelist" rule (deny
     * everything to all), and this method would return false unless this default has
     * been overridden (i.e., by executing $acl->allow()).
     *
     * If a $privilege is not provided, then this method returns false if and only if the
     * Role is denied access to at least one privilege upon the Resource. In other words, this
     * method returns true if and only if the Role is allowed all privileges on the Resource.
     *
     * This method checks Role inheritance using a depth-first traversal of the Role registry.
     * The highest priority parent (i.e., the parent most recently added) is checked first,
     * and its respective parents are checked similarly before the lower-priority parents of
     * the Role are checked.
     *
     * @param  Role\RoleInterface|string            $role
     * @param  Resource\ResourceInterface|string    $resource
     * @param  string                               $privilege
     * @return bool
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        $this->buildAcl();
//        if (!$this->hasRole($role)) {
//            $role = self::ROLE_LOGGED;
//        }
        return (bool) parent::isAllowed($role, $resource, $privilege);
    }
    
    /**
     * Returns true if and only if $resource inherits from $inherit
     *
     * Both parameters may be either a Resource or a Resource identifier. If
     * $onlyParent is true, then $resource must inherit directly from
     * $inherit in order to return true. By default, this method looks
     * through the entire inheritance tree to determine whether $resource
     * inherits from $inherit through its ancestor Resources.
     *
     * @param  Resource\ResourceInterface|string    $resource
     * @param  Resource\ResourceInterface|string    inherit
     * @param  bool                              $onlyParent
     * @throws Exception\InvalidArgumentException
     * @return bool
     */
    public function inheritsResource($resource, $inherit, $onlyParent = false)
    {
        $this->buildAcl();
        return (bool) parent::inheritsResource($resource, $inherit, $onlyParent);
    }
    
    /**
     * Returns true if and only if $role inherits from $inherit
     *
     * Both parameters may be either a Role or a Role identifier. If
     * $onlyParents is true, then $role must inherit directly from
     * $inherit in order to return true. By default, this method looks
     * through the entire inheritance DAG to determine whether $role
     * inherits from $inherit through its ancestor Roles.
     *
     * @param  Role\RoleInterface|string    $role
     * @param  Role\RoleInterface|string    $inherit ! behind = not inherits
     * @param  bool                      $onlyParents
     * @return bool
     */
    public function inheritsRole($role, $inherit, $onlyParents = false)
    {
        $this->buildAcl();
        
//        if (!$this->hasRole($role)) {
//            $role = self::ROLE_LOGGED;
//        }
        if (is_string($inherit)) {
            if ($inherit[0] == '!') {
                return !parent::inheritsRole($role, substr($inherit, 1), $onlyParents);
            }
        }
        
        return parent::inheritsRole($role, $inherit, $onlyParents);
    }
    
    /**
     * Does the resource exists?
     * @return bool
     */
    public function hasResourceParams($controller, $action = null)
    {
        $this->buildAcl();
        $resource = $this->buildResource($controller, $action);
        return parent::hasResource($resource);
    }
}
