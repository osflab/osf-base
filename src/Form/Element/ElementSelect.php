<?php
namespace Osf\Form\Element;

use Osf\View\Component;
use Osf\Exception\ArchException;
use Osf\View\Component\VueJs;
use Osf\Form\Element\ElementSelect\AutocompleteAdapterInterface;

/**
 * Select list element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 déc. 2013
 * @package osf
 * @subpackage form
 */
class ElementSelect extends ElementListAbstract
{
    use Addon\LeftRight;
    
    protected $multiple = false;
    protected $maxItems;
    protected $linkPattern;
    protected $create = false;
    protected $selectOnTab = false;
    protected $autocomplete;
    protected $autocompleteAdapter;
    protected $autocompleteLimit = null; // null = limite par défaut du composant
    
    /**
     * @param bool $trueOrFalse
     * @param int $maxItems
     * @return $this
     */
    public function setMultiple($trueOrFalse = true, $maxItems = null)
    {
        $this->multiple = (bool) $trueOrFalse;
        is_int($maxItems) && $this->maxItems = (int) $maxItems;
        return $this;
    }
    
    public function isMultiple()
    {
        return $this->multiple;
    }
    
    public function getMaxItems()
    {
        return $this->maxItems;
    }
    
    /**
     * Is it possible to put a value which is not in the option list ?
     * @param $create bool
     * @return $this
     */
    public function allowCreate($create = true)
    {
        $this->create = (bool) $create;
        return $this;
    }

    /**
     * @return bool
     */
    public function canCreate():bool
    {
        return $this->create;
    }
    
    /**
     * @param $selectOnTab bool
     * @return $this
     */
    public function setSelectOnTab($selectOnTab = true)
    {
        $this->selectOnTab = (bool) $selectOnTab;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSelectOnTab():bool
    {
        return $this->selectOnTab;
    }
    
    /**
     * Activate the ajax/json autocomplete
     * @param string $baseUrl Préfixe de l'url ajax qui sera suffixée par la chaîne de recherche 
     * @param string $itemTemplate Template d'un élément de la liste, ex: "'<div>' + escape(item.title) + '</div>'"
     * @param string $initialOptions Options initiales au format JSON
     * @param string $valueField Nom du champ correspondant à la valeur dans le flux JSON
     * @param string $labelField Nom du champ à afficher dans le input une fois sélectionné
     * @param string $searchField Nom du champ pour la recherche selectize
     * @param array $selectizeOptions Options supplémentaires de selectize (cf. doc selectize)
     * @return $this
     */
    public function setAutocomplete(
            string  $baseUrl, 
            ?string $itemTemplate = null,
            ?string $initialOptions = null,
            string  $valueField = 'id',
            string  $labelField = 'title',
            string  $searchField = 'search_content',
            array   $selectizeOptions = [])
    {
        $options = array_replace($selectizeOptions, [
            'valueField'  => $valueField,
            'labelField'  => $labelField,
            'searchField' => $searchField
        ]);
        $this->autocomplete = [
            'baseUrl'        => $baseUrl,
            'itemTemplate'   => $itemTemplate,
            'initialOptions' => $initialOptions,
            'options'        => $options
        ];
        return $this;
    }
    
    /**
     * Attach an adapter which decorate the element with an autocomplete process
     * 
     * Use this method at the element construction, in order to lazy load autocompletion. 
     * Particularly to take account of current element value during the options 
     * loading in autocompletion mode. 
     * 
     * @param AutocompleteAdapterInterface $autocompleteAdapter
     * @param int $limit nombre d'éléments initiaux à afficher
     * @return $this
     */
    public function setAutocompleteAdapter(AutocompleteAdapterInterface $autocompleteAdapter, ?int $limit = null)
    {
        $this->autocompleteAdapter = $autocompleteAdapter;
        $this->autocompleteLimit = $limit;
        return $this;
    }

    /**
     * @return \Osf\Form\Element\ElementSelect\AutocompleteAdapterInterface
     */
    public function getAutocompleteAdapter()
    {
        return $this->autocompleteAdapter;
    }
    
    /**
     * @param int|null $autocompleteLimit
     * @return $this
     */
    public function setAutocompleteLimit(?int $autocompleteLimit = null)
    {
        $this->autocompleteLimit = $autocompleteLimit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAutocompleteLimit(): ?int
    {
        return $this->autocompleteLimit;
    }
    
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }
    
    /**
     * Transform element to link
     * @param string $uriPattern Example: /ctrl/action/id/[key]/value/[value]
     * @return $this
     */
    public function setLinkPattern(string $uriPattern, $target = null)
    {
        if ($this->linkPattern) {
            throw new ArchException('UriPattern already setted');
        }
        $this->linkPattern = $uriPattern;
        Component::getVueJs()->registerLink(
                $this->getId(),
                $uriPattern,
                VueJs::URIPARAM_SELECT,
                VueJs::EVENT_CHANGE,
                $target);
        return $this;
    }
    
    public function __toString() {
        return parent::__toString();
    }
}