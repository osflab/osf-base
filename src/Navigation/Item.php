<?php
namespace Osf\Navigation;

use Osf\Application\Acl;
use Osf\Container\OsfContainer as Container;
use Osf\Stream\Html;
use Osf\Controller\Router;
use Osf\Exception\ArchException;
use Osf\View\Helper\Bootstrap\Tools\Checkers;

/**
 * navigation item
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 25 sept. 2013
 * @package osf
 * @subpackage navigation
 */
class Item implements \Iterator, \Countable
{
    protected $id;     // Identifiant de l'item pour le menu
    protected $menuId; // Identifiant du menu (répliqué dans tous les items)
    protected static $ids = [];
    
    protected $label;
    protected $icon;
    protected $color;
    protected $request = [];
    protected $badges  = [];
    protected $childs  = [];
    
    /**
     * @var \Osf\Navigation\Item
     */
    protected $parent = null;
    
    public function __construct($menuId, $id) 
    {
        if (isset(self::$ids[$menuId][$id])) {
            throw new ArchException('Id [' . $id . '] is already setted for menu [' . $menuId . ']');
        }
        if (is_null($menuId)) {
            throw new ArchException('Menu id can not be null');
        }
        if (is_null($id)) {
            throw new ArchException('Menu item id can not be null');
        }
        $this->menuId = $menuId;
        $this->id = $id;
        self::$ids[$menuId][$id] = $this;
    }
    
    /**
     * Get a menu item by name
     * @param type $id
     * @return \Osf\Navigation\Item
     * @throws ArchException
     */
    public function getItem($id)
    {
        if (!isset(self::$ids[$this->menuId][$id])) {
            throw new ArchException('Menu item [' . $id . '] not found');
        }
        return self::$ids[$this->menuId][$id];
    }
    
    /**
     * Build a new menu child
     * @param string $menuId
     * @param string $id
     * @return \Osf\Navigation\Item
     */
    public function buildChild($id)
    {
        $newChild = new self($this->menuId, $id);
        $this->childs[] = $newChild;
        $newChild->parent = $this;
        return $newChild;
    }
    
    /**
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = trim($label);
        return $this;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = trim($icon);
        return $this;
    }
    
    /**
     * @param string $default
     * @return mixed
     */
    public function getIcon($default = null)
    {
        return $this->icon ? $this->icon : $default;
    }
    
    /**
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = trim($color);
        return $this;
    }
    
    /**
     * @param string $default
     * @return mixed
     */
    public function getColor($default = null)
    {
        return $this->color ? $this->color : $default;
    }
    
    /**
     * @param string $label
     * @param string $color
     * @return $this
     */
    public function addBadge(string $label, string $color = null)
    {
        $color && Checkers::checkColor($color);
        $this->badges[] = $color ? [$label, $color] : [$label];
        return $this;
    }
    
    /**
     * @param array $badges
     * @return $this
     */
    public function setBadges(array $badges)
    {
        foreach ($badges as $badge) {
            $this->addBadge($badge[0], isset($badge[1]) ? $badge[1] : '');
        }
        $this->badges = $badges;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getBadges():array
    {
        return $this->badges;
    }
    
    /**
     * @return string
     */
    public function getId():string
    {
        return $this->id;
    }
    
    /**
     * @return array
     */
    public function getRequest():array
    {
        return $this->request;
    }
    
    /**
     * Build link from view helpers
     * @return string
     */
    public function getLink(array $linkAttributes = [])
    {
        $helpers = Container::getViewHelper();
        $safeLabel = $helpers->htmlEscape($this->label);
        $linkAttributes['href'] = $this->getUrl();
        if ($linkAttributes['href']) {
            return Html::buildHtmlElement('a', $linkAttributes, $safeLabel);
        }
        return $safeLabel;
    }
    
    /**
     * Get builded url from params
     * @return string
     */
    public function getUrl()
    {
        if (isset($this->request['url'])) {
            return $this->request['url'];
        } elseif (isset($this->request['params'])) {
            $action = isset($this->request['params']['action']) 
                    ? $this->request['params']['action'] 
                    : Router::getDefaultActionName();
            $controller = isset($this->request['params']['controller']) 
                        ? $this->request['params']['controller'] 
                        : Router::getDefaultControllerName();
            $params = $this->request['params'];
            unset($params['controller']);
            unset($params['action']);
            return Container::getViewHelper()->url($controller, $action, $params);
        }
        return null;
    }
    
    public function __toString()
    {
        return $this->getLink();
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function hasChild()
    {
        return isset($this->childs[0]);
    }
    
    /**
     * Request params for MVC uris
     * @param array $params
     * @return $this
     */
    public function setRequestParams(array $params)
    {
        $this->request['params'] = $params;
        return $this;
    }
    
    /**
     * Url for external link or calculated link
     * @param string $url
     * @return $this
     */
    public function setRequestUrl($url)
    {
        $this->request['url'] = $url;
        return $this;
    }
    
    public function isActive()
    {
        static $currentParams = null;
        
        if (!isset($this->getRequest()['params'])) {
            return false;
        }
        if ($currentParams === null) {
            $currentParams = Container::getRequest()->getParams(true);
            foreach ($currentParams as $key => $value) {
                if (!is_string($value)) { 
                    unset($currentParams[$key]);
                }
            }
        }
        $params = $this->getRequest()['params'];
//        var_dump($params);
        return $params == array_intersect($params, $currentParams);
    }
    
    /**
     * Recursive import of items from array
     * @param array $childs
     * @return $this
     */
    public function importChilds(
            array   $childs, 
            ?Acl    $acl = null, 
            array   $parentParams = [], 
            array   $apps = [],
            ?string $role = null)
    {
        // Pour chaque élément de menu...
        foreach ($childs as $key => $value) {
            
            // Si l'application n'est pas activée, on passe
            if ($apps && isset($value['app']) && !in_array($value['app'], $apps)) {
                continue;
            }
            
            // Si l'élément de menu requiert un administrateur et que l'utilisateur courant n'est pas admin, on passe
            if ($acl !== null && isset($value['acl']['role']) && $value['acl']['role'] === 'ADMIN' && !$acl->isAdmin()) {
                continue;
            }
            
            // Si l'élément de menu requiert un role spécifique et que le role de l'utilisateur courant n'est pas 
            // ce role ou n'hérite pas de ce role, on passe
            if ($acl !== null && isset($value['acl']['role']) && $value['acl']['role'] !== $role && !$acl->inheritsRole($role, $value['acl']['role'])) {
                continue;
            }
            
            // Si l'élément de menu est attaché à des paramètres request et que le role de l'utilisateur 
            // courant n'a pas accès à ces paramètres, on passe
            if ($acl !== null && (isset($value['params']) || $parentParams)) {
                $params = array_merge($parentParams, (isset($value['params']) ? $value['params'] : []));
                $controller = isset($params['controller']) ? $params['controller'] : null;
                $action     = isset($params['action'])     ? $params['action']     : null;
                $hasCAResource = !$controller || !$action || $acl->hasResource($controller . '_' . $action);
                if ($hasCAResource && !$acl->isAllowedParams($controller, $action, $role)) {
                    continue;
                }
            }
            
            // Sinon, on importe l'élément de menu
            $newItem = $this->buildChild($key);
            $value = is_string($value) ? ['label' => $value] : $value;
            isset($value['label'])  && $newItem->setLabel($value['label']);
            isset($value['icon'])   && $newItem->setIcon($value['icon']);
            isset($value['color'])  && $newItem->setColor($value['color']);
            isset($value['badges']) && $newItem->setBadges($value['badges']);
            if (isset($value['params'])) {
               $params = isset($this->getRequest()['params']) 
                    ? array_merge($this->getRequest()['params'], $value['params'])
                    : $value['params'];
                $newItem->setRequestParams($params);
            }
            if (isset($value['items'])) {
                $newItem->importChilds($value['items'], $acl, $params);
            }
        }
        return $this;
    }
    
    public function toArray()
    {
        // Label detection
        if (!$this->hasChild() && !count($this->getRequest())) {
            return $this->getLabel();
        }
        
        // Array of the element
        $array = \Osf\Helper\Tab::newArray();
        $array->addItemIfNotEmpty('label',  $this->getLabel())
              ->addItemIfNotEmpty('icon',   $this->getIcon())
              ->addItemIfNotEmpty('color',  $this->getColor())
              ->addItemIfNotEmpty('badges', $this->getBadges())
              ->addItemIfNotEmpty('params', 
                    is_array($this->getRequest()) && 
                        isset($this->getRequest()['params']) 
                            ? $this->getRequest()['params'] : null);
        if ($this->hasChild()) {
            $childsTab = [];
            foreach ($this as $child) {
                $childsTab[(string) $child->getId()] = $child->toArray();
            }
            $array->addItemIfNotEmpty('items', $childsTab);
        }
        return $array->getArray();
    }
    
    /**
     * Clean menu for reconstruction
     * @return $this
     */
    public function clean()
    {
        self::$ids = [$this->id];
        $this->childs = [];
        return $this;
    }
    
    // Interfaces
    
    public function current()
    {
        return current($this->childs);
    }
    
    public function key()
    {
        return key($this->childs);
    }
    
    public function next()
    {
        return next($this->childs);
    }
    
    public function valid()
    {
        return isset($this->childs[$this->key()]);
    }
    
    public function rewind()
    {
        return reset($this->childs);
    }
    
    public function count()
    {
        return count($this->childs);
    }
}