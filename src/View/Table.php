<?php
namespace Osf\View;

/**
 * Data table for Osf\View\Helper\Bootstrap\Table
 *
 * This class manage a collection of data and a configuration
 * in order to be displayed with the Bootstrap Table helper.
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2010
 * @version 2.0
 * @since OSF-1.0
 * @package osf
 * @subpackage view
 */
class Table
{
    const FP_DISPLAY  = 'display';
    const FP_PATTERN  = 'pattern';
    const FP_LABEL    = 'label';
    const FP_WIDTH    = 'width';
    const FP_ATTRS    = 'attrs';
    const FP_CSS      = 'css';
    const FP_STYLE    = 'style';
    const FP_CALLBACK = 'callback';
    
    const FIELD_CONDITION = 'condition';
    const FPC_FIELD       = 'field';   // Field to apply style
    const FPC_PATTERN     = 'pattern'; // Regex pattern to match
    const FPC_STYLE       = 'style';   // Style to apply if field value match
    
    const ITEM_PER_PAGE_IF_NO_PAGINATE = 10000;
    const ITEM_PER_PAGE_DEFAULT        = 10;
    
    /**
     * Data or link to data of the table to display
     */
    protected $data = null;

    /**
     * Field parameters (see constants)
     */
    protected $fieldParams = array();

    /**
     * Pattern for the link of each records
     */
    protected $linkPattern = null;

    /**
     * Field name for link pattern
     */
    protected $linkFieldKey = null;

    /**
     * Prefix of labels to substitute
     */
    protected $labelPrefix = null;

    /**
     * Use pagination ?
     */
    protected $paginate = true;

    /**
     * Set hovered (if no link pattern)
     */
    protected $hovered = false;
    
    /**
     * Item count for pagination
     */
    protected $itemCountPerPage = self::ITEM_PER_PAGE_DEFAULT;

    /**
     * Id of ajax div element thats contains the table (jquery)
     */
    protected $ajaxDivId = null;

    /**
     * Id of target ajax div element for parametred links
     */
    protected $linkAjaxDivId = null;
    
    /**
     * Colonnes exclues par le lien
     */
    protected $linkExcludedRows = null;
    
    /**
     * Lien blank (ouvert avec window.open)
     */
    protected $linkBlank = false;
    
    /**
     * Display somme checkbox selectors
     */
    protected $displaySelectors = false;

    /**
     * Is table cols sortable via jquery tablesorter ?
     */
    protected $sortable = false;

    /**
     * Additional columns
     */
    protected $columns = [];
    
    /**
     * Introduction pattern
     */
    protected $introPattern = null;
    
    /**
     * Display labels or not
     */
    protected $displayLabels = false;
    
    /**
     * Action buttons (last column)
     */
    protected $action;
    
    /**
     * Action buttons built with callback
     * @var array
     */
    protected $actionCallback;
    
    /**
     * Clés de paramètres (routeur) à filtrer (null = on prend toutes les clés)
     * @var array
     */
    protected $paramsKeys;
    
    /**
     * Attributs des balises 'tr'
     * @var array
     */
    protected $trAttrs = [];
    
    /**
     * @param array|\Laminas\Db\Sql\Select|Iterator $data
     */
    public function __construct($data = null)
    {
        $this->setData($data);
    }

    /**
     * @return array|\Laminas\Db\Sql\Select|Iterator $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data or link to data of the table to display
     * @param array|\Laminas\Db\Sql\Select|Iterator $data $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get field parameters
     * @return array
     */
    public function getFieldParams()
    {
        return $this->fieldParams;
    }

    /**
     * Set field parameters
     * 
     * First key : field name
     * Second key : name of parameter (label, width, pattern or eval)
     * Value : label, html width, sprintf pattern, php eval
     *
     * @param array $fieldParams
     * @return $this
     */
    public function setFieldParams(array $fieldParams)
    {
        $this->fieldParams = $fieldParams;
        return $this;
    }
    
    /**
     * Merge field parameters
     * 
     * First key : field name
     * Second key : name of parameter (label, width, pattern or eval)
     * Value : label, html width, sprintf pattern, php eval
     *
     * @param array $fieldParams
     * @return $this
     */
    public function mergeFieldParams(array $fieldParams)
    {
        $this->fieldParams = array_merge($this->fieldParams, $fieldParams);
        return $this;
    }

    /**
     * Get url pattern used to each records
     * @return string
     */
    public function getLinkPattern()
    {
        return $this->linkPattern;
    }

    /**
     * Set url with [key] keyword
     * @param type $linkPattern
     * @param type $fieldKey
     * @param type $ajaxId
     * @param type $excludedRows
     * @param bool $blank
     * @return $this
     */
    public function setLinkPattern($linkPattern, $fieldKey = null, $ajaxId = null, $excludedRows = null, bool $blank = false)
    {
        $this->linkPattern = $linkPattern;
        if ($fieldKey !== null) {
            $this->setLinkFieldKey($fieldKey);
        }
        $this->linkAjaxDivId = $ajaxId;
        $this->linkExcludedRows = $excludedRows;
        $this->linkBlank = $blank;
        return $this;
    }

    /**
     * Name of the field used by link pattern
     * @return string
     */
    public function getLinkFieldKey()
    {
        return $this->linkFieldKey;
    }
    
    /**
     * Ajax target div id for links
     * @return string
     */
    public function getLinkAjaxDivId()
    {
        return $this->linkAjaxDivId;
    }

    /**
     * @return bool
     */
    public function getLinkBlank(): bool
    {
        return $this->linkBlank;
    }
    
    /**
     * Set the link field key used by link pattern
     * @param string $linkFieldKey
     * @return $this
     */
    public function setLinkFieldKey($linkFieldKey)
    {
        if (!is_string($linkFieldKey)) {
            $msg = "Link field key must be a string";
            throw new OpenStates_View_Exception($msg);
        }
        $this->linkFieldKey = $linkFieldKey;
        return $this;
    }
    
    public function getLinkExcludedRows()
    {
        return $this->linkExcludedRows;
    }

    /**
     * @param array $linkExcludedRows
     * @return $this
     */
    public function setLinkExcludedRows(array $linkExcludedRows)
    {
        $this->linkExcludedRows = $linkExcludedRows;
        return $this;
    }
    
    /**
     * Common label prefix
     * @return string
     */
    public function getLabelPrefix()
    {
        return $this->labelPrefix;
    }

    /**
     * Set the common label prefix
     * @param string $labelPrefix
     * @return $this
     */
    public function setLabelPrefix($labelPrefix)
    {
        if (!is_string($labelPrefix)) {
            $msg = "Label prefix must be a string";
            throw new OpenStates_View_Exception($msg);
        }
        $this->labelPrefix = $labelPrefix;
        return $this;
    }

    /**
     * Do paginate ?
     * @return boolean
     */
    public function getPaginate()
    {
        return (boolean) $this->paginate;
    }

    /**
     * Set pagination
     * @param boolean $paginate
     * @return $this
     */
    public function setPaginate($paginate, $itemCountPerPage = null, array $paramsKeys = null)
    {
        $this->paginate = (bool) $paginate;
        if ($itemCountPerPage !== null) {
            $this->setItemCountPerPage($itemCountPerPage);
        }
        if (is_array($paramsKeys)) {
            $this->setParamsKeys($paramsKeys);
        }
        return $this;
    }

    /**
     * Record count by page. 
     * 
     * ITEM_PER_PAGE_IF_NO_PAGINATE if paginate is false.
     *
     * @return integer
     */
    public function getItemCountPerPage()
    {
        if (!$this->paginate) {
            return self::ITEM_PER_PAGE_IF_NO_PAGINATE;
        }
        return (integer) $this->itemCountPerPage;
    }

    /**
     * Set record count by page
     * @param integer $itemCountPerPage
     * @return $this
     */
    public function setItemCountPerPage($itemCountPerPage)
    {
        $this->itemCountPerPage = (integer) $itemCountPerPage;
        return $this;
    }

    /**
     * Get ajax <div id="?">
     * @return string
     */
    public function getAjaxDivId()
    {
        return $this->ajaxDivId;
    }

    /**
     * Set ajax <div id="?">
     * @param string $ajaxDivId
     */
    public function setAjaxDivId($ajaxDivId)
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $ajaxDivId)) {
            throw new OpenStates_View_Exception("Ajax div id syntax error");
        }
        $this->ajaxDivId = (string) $ajaxDivId;
    }

    /**
     * Display checkbox selectors ?
     * @return boolean
     */
    public function getDisplaySelectors()
    {
        return (boolean) $this->displaySelectors;
    }

    /**
     * Display checkbox selectors ?
     * @param boolean $displaySelectors
     * @return $this
     */
    public function setDisplaySelectors($displaySelectors)
    {
        $this->displaySelectors = (boolean) $displaySelectors;
        return $this;
    }

    /**
     * Is javascript sortable ?
     * @return boolean
     */
    public function getSortable()
    {
        return (boolean) $this->sortable;
    }

    /**
     * Is javascript sortable ?
     * @param boolean $sortable
     * @return $this
     */
    public function setSortable($sortable)
    {
        $this->sortable = (boolean) $sortable;
        return $this;
    }
    
    /**
     * Is specified field param exists ? 
     * @param string $key
     * @param string $param
     * @return boolean
     */
    public function fieldParamExists($key, $param)
    {
        //return is_array($this->fieldParams)
        //&& array_key_exists($key, $this->fieldParams)
        //&& is_array($this->fieldParams[$key])
        //&& array_key_exists($param, $this->fieldParams[$key]);
        return @array_key_exists($param, $this->fieldParams[$key]);
    }
    
    /**
     * @param \Osf\View\OpenStates_View_Table_Column $column
     * @task [TABLE] finir la migration
     * @return $this
     */
    public function addColumn(OpenStates_View_Table_Column $column)
    {
        $this->columns[] = $column;
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }
    
    public function setHovered(bool $hovered = true)
    {
        $this->hovered = (bool) $hovered;
        return $this;
    }
    
    public function getHovered()
    {
        return $this->hovered;
    }
    
    /**
     * Introduction pattern, to display behind table
     * 
     * Substitution keys : 
     * %count% : row count
     *
     * @param string $introPattern
     */
    public function setIntroPattern($introPattern)
    {
        $this->introPattern = (string) $introPattern;
    }
    
    public function getIntroPattern()
    {
        return $this->introPattern;
    }
    
    /**
     * @return $this
     */
    public function setDisplayLabels($displayLabels = true)
    {
        $this->displayLabels = (bool) $displayLabels;
        return $this;
    }

    public function getDisplayLabels()
    {
        return $this->displayLabels;
    }
    
    /**
     * Set the action button(s).
     * 
     * Example : if field is "id", {{id}} tag is replaced by the value of id
     * 
     * @param string $content
     * @param string $field
     */
    public function setAction(string $content, string $field = 'id')
    {
        $this->action = [
            'content' => $content,
            'field' => $field
        ];
    }
    
    /**
     * Action buttons
     * @return array|null
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * @param mixed $actionCallback
     * @return $this
     */
    public function setActionCallback(callable $actionCallback, array $firstArgs = [])
    {
        $this->actionCallback = [$actionCallback, $firstArgs];
        return $this;
    }
    
    /**
     * @return array|null
     */
    public function getActionCallback(): ?array
    {
        return $this->actionCallback;
    }
    
    /**
     * @param array $paramsKeys
     * @return $this
     */
    public function setParamsKeys(array $paramsKeys = [])
    {
        $this->paramsKeys = $paramsKeys;
        return $this;
    }

    /**
     * @return array
     */
    public function getParamsKeys()
    {
        return $this->paramsKeys;
    }
    
    /**
     * @param array $trAttrs
     * @return $this
     */
    public function setTrAttrs(array $trAttrs = [])
    {
        $this->trAttrs = $trAttrs;
        return $this;
    }

    /**
     * @return array
     */
    public function getTrAttrs()
    {
        return $this->trAttrs;
    }
    
    /**
     * Ajout d'un attribut aux balises TR
     * @param string $attrName
     * @param callback|string $attrValue
     * @return $this
     */
    public function setTrAttr(string $attrName, $attrValue)
    {
        $this->trAttrs[$attrName] = $attrValue;
        return $this;
    }
}
