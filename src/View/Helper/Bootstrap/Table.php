<?php 
namespace Osf\View\Helper\Bootstrap;

use Zend\Paginator\Adapter\Iterator as PaginatorIterator;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Paginator;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Db\Row\AbstractRowGateway;
use Osf\View\Helper\Addon\Header;
use Osf\View\Table as VT;
use Osf\View\Component\VueJs;
use Osf\Container\OsfContainer as Container;
use Osf\Stream\Html;
use Osf\Exception\ArchException;
use Iterator;

/**
 * Bootstrap table
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 * @task [TABLE] Refactoring des fonctionnalités et documentation
 */
class Table extends AVH
{
    const PR_KEY = 'tp';  // Pagination Request Key (Table Page)
    const AI_KEY = 'ai';  // Ajax Id key
    const NR_KEY = 'nr';  // No Refresh
    const JQ_CTN = '$(this).parents().eq(4)';
    
    const RTAG_LEFT  = '{{';
    const RTAG_RIGHT = '}}';
    
    use Header;
    use EltDecoration;
    
    /**
     * @var \Osf\View\Table
     */
    protected $table;
    protected $buttons;
    protected $actionButtons = [];
    protected $responsive = true;
    protected $itemTemplate;
    
    /**
     * @return \Osf\View\Helper\Bootstrap\Table
     */
    public function __invoke($data = null)
    {
        $this->initValues(get_defined_vars());
        $data !== null && $this->setTable($data);
        $this->setResponsive(true);
        return $this;
    }
    
    /**
     * @param array|Iterator $table
     * @return $this
     */
    public function setTable($table)
    {
        if (!($table instanceof VT)) {
            $table = new VT($table);
        }
        $this->table = $table;
        return $this;
    }

    /**
     * @return \Osf\View\Table
     */
    public function getTable():VT
    {
        return $this->table;
    }
    
    /**
     * Id attribute of table element
     * @return string
     */
    public function getId()
    {
        if (!isset($this->attributes['id'])) {
            $this->buildIdAttr(Container::getRequest()->getParam(self::AI_KEY));
        }
        return $this->attributes['id'];
    }
    
    /**
     * Id attribute of container box element
     * @return string
     */
    public function getBoxId()
    {
        return 'b' . $this->getId();
    }
    
    /**
     * @return $this
     */
    public function setItemTemplate($itemTemplate)
    {
        $this->itemTemplate = $itemTemplate === null ? null : (string) $itemTemplate;
        return $this;
    }

    public function getItemTemplate()
    {
        return $this->itemTemplate;
    }
    
    /**
     * @return string
     */
    public function render()
    {
        $table = $this->table;
        $fieldParams = $table->getFieldParams();
        $data = $table->getData();
        
        // Chargement du paginateur
        switch (true) {
            case is_array($data) : 
                $adapter = new ArrayAdapter($data);
                break;
//            case $data instanceof \Zend\Db\Sql\Select : 
//                $adapter = new \Zend\Paginator\Adapter\DbSelect($data);
//                break;
            case $data instanceof TableGateway : 
                $adapter = new DbTableGateway($data);
                break;
            case $data instanceof Iterator : 
                $adapter = new PaginatorIterator($data);
                break;
            default: 
                throw new ArchException('Not gered');
        }
        $paginator = new Paginator($adapter);
        
        $itemCount = count($paginator);
        if (!$itemCount) { return ''; }
        $paginator->setItemCountPerPage($table->getItemCountPerPage());
        $request = Container::getRequest();
        $paginator->setCurrentPageNumber((int) $request->getParam(self::PR_KEY));
        $paginated = $paginator->count() > 1;
        
        // Introduction pattern (header)
        if ($table->getIntroPattern() !== null) {
            $substitutionTable = ['%count%' => $paginator->getTotalItemCount()];
            $intro = str_replace(array_keys($substitutionTable), 
                                   array_values($substitutionTable), 
                                   $table->getIntroPattern());
        }
        
        $this->addCssClass('table');
        if (!Container::getDevice()->isMobileOrTablet()) {
            $this->addCssClass('table-hover');
        }
        
        // Data building
        $this->html('<table' . $this->getEltDecorationStr() . '>');

        $hasLinkExcludedRows = $table->getLinkPattern() && is_array($table->getLinkExcludedRows()) && count($table->getLinkExcludedRows());
        $tdStyle = null;
        $firstLine = true;
        foreach ($paginator as $lineKey => $line) {
            
            if ($line instanceof AbstractRowGateway) {
                $line = $line->toArray();
            }
            
            // Legend
            if ($firstLine && $table->getDisplayLabels()) {
                $this->html('<thead><tr>');
                if ($table->getDisplaySelectors()) {
                    $this->html('<th>&nbsp;</th>');
                }
                if ($this->getItemTemplate()) {
                    // @task [TABLE] Gérer les labels du tableau avec template
                    $this->html('<th>&nbsp;</th>');
                } else {
                    
                    // Colonnes des données principales
                    foreach (array_keys($line) as $key) {
                        if ($table->fieldParamExists($key, 'display') && $fieldParams[$key]['display'] == false) {
                            continue;
                        } elseif ($table->fieldParamExists($key, 'label')) {
                            $currentLabel = $fieldParams[$key]['label'];
                        } elseif ($table->getLabelPrefix()) {
                            $currentLabel = preg_replace('/^' . $table->getLabelPrefix() . '/', '', $key);
                        } else {
                            $currentLabel = $key;
                        }
                        $this->html('<th nowrap>' . $currentLabel . '</th>');
                    }
                }
                
                // Colonnes additionnelles
                foreach ($table->getColumns() as $column) {
                    $this->html('<th>' . $column->getLabel() . '</th>');
                }
                if ($table->getAction() || $table->getActionCallback()) {
                    $this->html('<th>&nbsp;</th>');
                }
                $this->html('</tr></thead>');

            }
            if ($firstLine) {
                $this->html('<tbody>');
            }
            
            // TR
            $attrs = [];
            
            // Appel des callbacks d'attributs
            foreach ($table->getTrAttrs() as $attrKey => $attrValue) {
                if (is_callable($attrValue)) {
                    $attrValue = call_user_func($attrValue, $line);
                }
                $attrs[$attrKey] = (string) $attrValue;
            }
            
            // Lien 
            if ($table->getLinkPattern()) {
                if (!is_string($table->getLinkPattern()) && is_callable($table->getLinkPattern())) {
                    $link = $table->getLinkPattern()($line);
                } else {
                    $linkKey = $table->getLinkFieldKey() !== null && isset($line[$table->getLinkFieldKey()]) ? $line[$table->getLinkFieldKey()] : $lineKey;
                    $link = str_replace('[key]', urlencode($linkKey), (string) $table->getLinkPattern());
                }
                if ($table->getLinkBlank()) {
                    $linkPattern = "window.open('" . $link . "', '_blank');";
                } else if ($table->getLinkAjaxDivId()) {
                    $linkPattern = '$(\'#' . $table->getLinkAjaxDivId() . '\').load(\'' . $link . '\');';
                } else {
                    $linkPattern = VueJs::buildJsLink($link);
                }
            }
            
            if ($table->getLinkPattern() 
                    && !$hasLinkExcludedRows 
                    && !($table->getAction() || $table->getActionCallback())) {
                $attrs['onclick'] = isset($attrs['onclick']) ? $linkPattern . $attrs['onclick'] : $linkPattern;
                $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' clickable' : 'clickable';
            }

            $this->html('<tr' . Html::buildAttrs($attrs) . '>');

            // TD
            
            // Style conditionnel 
            $tdStyle = '';
            if (isset($fieldParams[VT::FIELD_CONDITION][VT::FPC_STYLE])
            &&  isset($fieldParams[VT::FIELD_CONDITION][VT::FPC_PATTERN])
            &&  isset($fieldParams[VT::FIELD_CONDITION][VT::FPC_FIELD])) {
                if (preg_match($fieldParams[VT::FIELD_CONDITION][VT::FPC_PATTERN],
                         $line[$fieldParams[VT::FIELD_CONDITION][VT::FPC_FIELD]])) {
                    $tdStyle = $fieldParams[VT::FIELD_CONDITION][VT::FPC_STYLE];
                }
            }

            if ($table->getDisplaySelectors()) {
                $this->html('<td><input type="checkbox" name="selection" value="' . $lineKey . '"></td>');
            }
            
            // Champs
            if ($this->getItemTemplate()) {
                $lineContent = function ($row, $template) { return include $template; };
                $attrs = [];
                if ($table->getLinkPattern() && ($table->getAction() || $table->getActionCallback())) {
                    $attrs['onclick'] = $linkPattern;
                    $attrs['class'] = 'clickable';
                }
                $this->html('<td' . Html::buildAttrs($attrs) . '>' . $lineContent((array) $line, $this->getItemTemplate()) . '</td>');
            } else {
                foreach ($line as $key => $value) {
                    if ($table->fieldParamExists($key, VT::FP_DISPLAY)
                    && $fieldParams[$key][VT::FP_DISPLAY] == false) {
                        continue;
                    }
                    if ($table->fieldParamExists($key, VT::FP_CALLBACK)) {
                        $value = $fieldParams[$key][VT::FP_CALLBACK]($value);
                    }
                    if ($table->fieldParamExists($key, VT::FP_PATTERN)) {
                        $value = sprintf($fieldParams[$key][VT::FP_PATTERN], $value);
                    }
                    
                    // Attributs
                    $attrs = [];
                    if ($table->fieldParamExists($key, VT::FP_ATTRS)) {
                        $attrs = array_merge($attrs, $fieldParams[$key][VT::FP_ATTRS]);
                    }
                    $style = trim($tdStyle . ($table->fieldParamExists($key, VT::FP_STYLE) ? $fieldParams[$key][VT::FP_STYLE] : ''));
                    if ($table->getLinkPattern() && $hasLinkExcludedRows) {
                        $attrs['onclick'] = $linkPattern;
                        $attrs['class'] = 'clickable';
                    }
                    if ($style) {
                        $attrs['style'] = isset($attrs['style']) ? $attrs['style'] . $style : $style;
                    }
                    if ($firstLine && $table->fieldParamExists($key, VT::FP_WIDTH)) {
                        $attrs['width'] = $fieldParams[$key][VT::FP_WIDTH];
                    }

                    // Classes CSS
                    $css = $table->fieldParamExists($key, VT::FP_CSS) 
                            ? (is_array($fieldParams[$key][VT::FP_CSS])
                                ? $fieldParams[$key][VT::FP_CSS]
                                : [$fieldParams[$key][VT::FP_CSS]])
                            : [];

                    $this->html('<td' . Html::buildAttrs($attrs, $css) . '>');
                    if ($table->getLinkPattern()) {
                        $this->html("<a title=\"" . str_replace('"', '\"', strip_tags($value)) . "\" href=\"" . $link . "\"");
                        $this->html(' style="' . $style . '"', $style);
                        $this->html('>' . $value . '</a>');
                    } else {
                        $this->html($value);
                    }
                    $this->html('</td>');
                }
            }
            
            // Colonnes additionnelles
            foreach ($table->getColumns() as $column) {
                $patternValues = array();
                foreach ($column->getParams() as $param) {
                    $patternValues[] = $line[$param];
                }
                $this->html('<td');
                foreach ($column->getAttrs() as $attrKey => $attrValue) {
                    $this->html(' ' . $attrKey . '="' . str_replace('"', '\"', $attrValue) . '"');
                }
                $this->html('>' . $this->view->escape(vsprintf($column->getPattern(), $patternValues)) . '</td>');
            }
            
            // Boutons d'action
            if ($table->getAction()) {
                $this->html('<td class="text-right td-btn" nowrap>');
                $action = $table->getAction();
                $rKeys = array_keys($line);
                array_walk($rKeys, function (&$value) { $value = self::RTAG_LEFT . $value . self::RTAG_RIGHT; });
                $content = str_replace($rKeys, array_values($line), $action['content']);
                $this->html($content);
                $this->html('</td>');
            }
            
            // Boutons d'action via callback
            if ($table->getActionCallback()) {
                $this->html('<td class="text-right td-btn" nowrap>');
                [$callback, $callbackParams] = $table->getActionCallback();
                $callbackParams[] = $line;
                $this->html(call_user_func_array($callback, $callbackParams));
                $this->html('</td>');
            }
            
            $this->html('</tr>');
            $firstLine = false;
        }
        $this->html('</tbody></table>');

        // Action buttons
        if ($this->actionButtons) {
            $this->buttons .= implode('', $this->actionButtons);
        }
        
        // Pagination
        if ($table->getPaginate() && $paginated) {
            $mobile = Container::getDevice()->isMobile();
            $paginator->setPageRange(3);
            $pages = $paginator->getPages();
            $h = Container::getViewHelper();
            $buttons    = $mobile ? '' : (new ButtonGroup())->addCssClass('hidden-xs');
            $altButtons = (new ButtonGroup())->addCssClasses(['hidden-lg', 'hidden-md', 'hidden-sm']);
            if ($pages->current <= 1) {
                $mobile || $buttons->button(null, null, null, 'angle-double-left', false, true);
                $altButtons->button(null, null, null, 'angle-left', false, true);
            } else {
                $mobile || $link = $this->buildJsPaginUrl(1);
                $mobile || $button = (new Button())->icon('angle-double-left')
                                        ->url($link, self::JQ_CTN, null, true);
                $mobile || $buttons->addButton($button);
                $link = $this->buildJsPaginUrl($pages->previous);
                $button = (new Button())->icon('angle-left')
                                        ->url($link, self::JQ_CTN, null, true);
                $altButtons->addButton($button);
            }
            if (!$mobile) {
                foreach ($pages->pagesInRange as $pageNo) {
                    $isCurrent = $pageNo == $pages->current;
                    $link = $isCurrent ? null : $this->buildJsPaginUrl($pageNo);
                    $button = (new Button($pageNo))->status('default')->addCssClass('bg-navy', $isCurrent)->disable($isCurrent);
                    $link && $button->url($link, self::JQ_CTN, null, true);
                    $buttons->addButton($button);
                }
            }
            if ($pages->current >= $pages->pageCount || $pages->pageCount <= 1) {
                $mobile || $buttons->button(null, null, null, 'angle-double-right', false, true);
                $altButtons->button(null, null, null, 'angle-right', false, true);
            } else {
                $mobile || $link = $this->buildJsPaginUrl($pages->pageCount);
                $mobile || $button = (new Button())->icon('angle-double-right')
                                        ->url($link, self::JQ_CTN, null, true);
                $mobile || $buttons->addButton($button);
                $link = $this->buildJsPaginUrl($pages->next);
                $button = (new Button())->icon('angle-right')
                                        ->url($link, self::JQ_CTN, null, true);
                $altButtons->addButton($button);
            }
            $text = '';
            $mobile || $text = $pages->firstItemNumber . '-' . $pages->lastItemNumber . '/' . $pages->totalItemCount;
            $mobile || $text = $h->button($text)->statusDefault()->addCssClasses(['hidden-xs'])->marginRight();
            $this->buttons .= $text . $buttons . $altButtons;
        }

        return $this->getHtml();
    }
    
    protected function buildJsPaginUrl($pageNo)
    {
        $h = Container::getViewHelper();
        $r = Container::getRequest();
        $p = is_array($this->table->getParamsKeys()) 
            ? array_intersect_key($r->getParams(), array_flip($this->table->getParamsKeys())) 
            : $r->getParams();
        unset($p[self::NR_KEY]); // Pas de rafraichissement de la page lors de la pagination
        return $h->url($r->getController(), 
            $r->getAction(), 
            array_merge($p, [self::AI_KEY => $this->getId(),
                             self::PR_KEY => $pageNo]));
    }
    
    /**
     * @return string|null
     */
    public function getButtons()
    {
        return $this->buttons;
    }
    
    /**
     * @return $this
     */
    public function setActionButtons(array $actionButtons)
    {
        $this->actionButtons = $actionButtons;
        return $this;
    }
    
    /**
     * Button to display at the end of the table
     * @param \Osf\View\Helper\Bootstrap\Button $button
     * @return $this
     */
    public function addActionButton($button)
    {
        $this->actionButtons[] = $button;
        return $this;
    }

    /**
     * @return array
     */
    public function getActionButtons():array
    {
        return $this->actionButtons;
    }
    
    /**
     * @return $this
     */
    public function setResponsive($responsive = true)
    {
        $this->responsive = (bool) $responsive;
        return $this;
    }

    public function getResponsive():bool
    {
        return $this->responsive;
    }
}
