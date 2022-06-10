<?php 

namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Exception\ArchException;

/**
 * Bootstrap grid helper
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Grid extends AVH
{
    const STATUS_OK = 'ok';
    const STATUS_IN_CONTAINER = 'container';
    const STATUS_IN_ROW = 'row';
    const STATUS_IN_CELL = 'cell';
    const GRID_TYPE_RESPONSIVE = 'responsive';
    const GRID_TYPE_STATIC = 'static';
    const GRID_RESPONSIVE = [
        1  => [null, 12, 12, 12],
        2  => [null,  6, 12, 12],
        3  => [null,  4, 12, 12],
        4  => [null,  3,  6, 12],
        5  => [null,  3,  4,  6],
        6  => [null,  2,  4, 12],
        7  => [null,  2,  3,  6],
        8  => [null,  2,  3,  4],
        12 => [   1,  2,  4,  6]
    ];
    const GRID_STATIC = [
        1  => [null, null, null, 12],
        2  => [null, null, null,  6],
        3  => [null, null, null,  4],
        4  => [null, null, null,  3],
        6  => [null, null, null,  2],
        12 => [null, null, null,  1]
    ];
    
    const DEFAULT_COLS  = 4;
    const DEFAULT_FLUID = true;
    
    const CSS_CONTAINER_MID_PADDING = 'container-mid-padding'; // Espace réduit droite et gauche entre les cellules
    const CSS_CONTAINER_SPACED = 'container-spaced'; // Espace haut et bas entre les lignes
    
    protected $grid           = 'responsive';
    protected $cloned         = false;
    protected $containerCount = 0;
    protected $rowCount       = 0;
    protected $cellCount      = 0;
    protected $status         = self::STATUS_OK;
    protected $buffer         = '';
    protected $gridCount      = ['lg' => 0, 'md' => 0, 'sm' => 0, 'xs' => 0];
    
    protected $autoStarted    = false;
    protected $autoFluid      = self::DEFAULT_FLUID;
    protected $autoCols       = self::DEFAULT_COLS;
    protected $autoCurrentCol = 0;
    protected $autoContainer  = true;
    protected $autoContainerClasses = [];
    
    /**
     * New grid instance (each grid have a separate instance)
     * 
     * Example usage 1 (manual) : 
     * echo $grid->beginContainer()->beginRow();
     * echo $grid->beginCell4(); <DATA> $grid->endCell(); x4
     * echo $grid->endRow()->beginRow();
     * echo $grid->beginCell4(); <DATA> $grid->endCell(); x4
     * echo $gride->endRow()->endContainer();
     * 
     * Example usage 2 (auto) :
     * echo $grid->autoStart(4); // 4 cols max with large screen
     * foreach (...) {
     *     echo $grid->autoNewCell(); 
     *     <DATA>
     * }
     * echo $grid->autoStop(); 
     * 
     * @return \Osf\View\Helper\Bootstrap\Grid
     */
    public function __invoke(string $namespace = null)
    {
        if ($namespace !== null) {
            return self::getInstance($namespace);
        }
        $this->buffer = '';
        return $this;
    }
    
    protected static function getInstance(string $namespace)
    {
        static $instances = [];
        
        if (!isset($instances[$namespace])) {
            $instances[$namespace] = new self();
        }
        return $instances[$namespace];
    }
    
    /**
     * @return $this
     */
    public function gridResponsive()
    {
        $this->grid = self::GRID_TYPE_RESPONSIVE;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function gridStatic()
    {
        $this->grid = self::GRID_TYPE_STATIC;
        return $this;
    }
    
    /**
     * @return array
     * @throws ArchException
     */
    protected function getGrid()
    {
        switch ($this->grid) {
            case self::GRID_TYPE_RESPONSIVE : return self::GRID_RESPONSIVE;
            case self::GRID_TYPE_STATIC     : return self::GRID_STATIC;
        }
        throw new ArchException('Bad grid type');
    }
    
    /**
     * @param bool $fluid
     * @return $this
     */
    public function beginContainer(bool $fluid = self::DEFAULT_FLUID, bool $display = true, array $cssClasses = [])
    {
        if ($this->status != self::STATUS_OK) {
            Checkers::notice("Container already started");
        }
        $cssClasses[] = 'container' . ($fluid ? '-fluid' : '');
        $css = implode(' ', $cssClasses);
        $this->status = self::STATUS_IN_CONTAINER;
        $this->containerCount++;
        $this->gridReset();
        $this->html('<div class="' . $css . '">', $display);
        return $this;
    }
    
    /**
     * @return $this
     */
    public function endContainer(bool $display = true)
    {
        $this->containerCount--;
        if ($this->status != self::STATUS_IN_CONTAINER) {
            Checkers::notice("Container not complete, we are in a [" . $this->status . ']');
        }
        $this->status = self::STATUS_OK;
        $this->html('</div>', $display);
        return $this;
    }
    
    /**
     * @return $this
     */
    public function beginRow()
    {
        if ($this->status != self::STATUS_IN_CONTAINER && $this->status != self::STATUS_OK) {
            Checkers::notice("Row can't be started, we are in a [" . $this->status . ']');
        }
        $this->status = self::STATUS_IN_ROW;
        $this->rowCount++;
        $this->gridReset();
        $this->html('<div class="row">');
        return $this;
    }
    
    /**
     * @return $this
     */
    public function endRow()
    {
        if ($this->status != self::STATUS_IN_ROW) {
            Checkers::notice("Don't end row here, we are in a [" . $this->status . ']');
        }
        $this->status = $this->containerCount ? self::STATUS_IN_CONTAINER : self::STATUS_OK;
        $this->rowCount--;
        $this->html('</div>');
        return $this;
    }
    
    /**
     * @param int|null $lg
     * @param int|null $md
     * @param int|null $sm
     * @param int|null $xs
     * @return $this
     */
    public function beginCell($lg, $md, $sm, $xs, array $classes = [], array $attrs = [])
    {
        if ($this->status != self::STATUS_IN_ROW) {
            Checkers::notice("Don't start cell here, we are in a [" . $this->status . ']');
        }
        $this->status = self::STATUS_IN_CELL;
        $this->cellCount++;
        $this->gridIncr((int) $lg, (int) $md, (int) $sm, (int) $xs);
        
        $lg === null || $this->checkGridValue($lg);
        $md === null || $this->checkGridValue($md);
        $sm === null || $this->checkGridValue($sm);
        $xs === null || $this->checkGridValue($xs);
        $lg = $lg === 12 ? null : $lg;
        $md = $lg === null && $md === 12 ? null : $md;
        $lg && $classes[] = 'col-lg-' . $lg;
        $md && $classes[] = 'col-md-' . $md;
        $sm && $classes[] = 'col-sm-' . $sm;
        $xs && $classes[] = 'col-xs-' . $xs;
        $attrs['class'] = implode(' ', $classes);
        $attrValues = '';
        foreach ($attrs as $key => $value) {
            $attrValues .= ' ' . $key . '="' . $value . '"';
        }
        $this->html(' <div' . $attrValues . '>');
        return $this;
    }
    
    /**
     * @return $this
     */
    public function endCell()
    {
        if ($this->status != self::STATUS_IN_CELL) {
            Checkers::notice("Don't stop cell here, we are in a [" . $this->status . ']');
        }
        $this->status = self::STATUS_IN_ROW;
        $this->cellCount--;
        
        $this->html(' </div>');
        return $this;
    }
    
    /**
     * @return $this
     */
    public function beginCell1(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(1, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell2(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(2, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell3(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(3, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell4(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(4, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell5(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(5, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell6(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(6, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell7(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(7, $classes, $attrs);
    }

    /**
     * @return $this
     */
    public function beginCell8(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(8, $classes, $attrs);
    }
    
    /**
     * @return $this
     */
    public function beginCell12(array $classes = [], array $attrs = [])
    {
        return $this->callBeginCell(12, $classes, $attrs);
    }

    /**
     * @return $this
     */
    public function beginCellRest(array $classes = [], array $attrs = [])
    {
        $args = $this->gridRest();
        $args[] = $classes;
        $args[] = $attrs;
        return call_user_func_array([$this, 'beginCell'], $args);
    }
    
    /**
     * @param int $cols
     * @return $this
     */
    protected function callBeginCell(int $cols, array $classes = [], array $attrs = [])
    {
        $args = $this->getGrid()[$cols];
        $args[] = $classes;
        $args[] = $attrs;
        return call_user_func_array([$this, 'beginCell'], $args);
    }
    
    /**
     * @param int $value
     * @return boolean
     */
    protected function checkGridValue(int &$value) {
        if ($value < 1 || $value > 12) {
            Checkers::notice('Bad or out of bound grid value [' . $value . ']. Please use int between 1 and 12.');
            $value = 12;
            return false;
        }
        return true;
    }
    
    /**
     * Call this just before your loop. Inside the loop call autoNewCol() before output
     * @param int $nbColsByRow
     * @return $this
     */
    public function autoStart(int $nbColsByRow = 4, bool $container = true, bool $fluid = true, array $containerClasses = []):self
    {
        if (!isset($this->getGrid()[$nbColsByRow])) {
            Checkers::notice('Bad nb cols by row [' . $nbColsByRow . ']. Using ' . self::DEFAULT_COLS . ' by default.');
            $nbColsByRow = self::DEFAULT_COLS;
        }
        if ($this->status !== self::STATUS_OK) {
            Checkers::notice('Autostart: status is not correct.');
        }
        $this->autoStarted = true;
        $this->autoCols = $nbColsByRow;
        $this->autoCurrentCol = 0;
        $this->autoFluid = $fluid;
        $this->autoContainer = $container;
        $this->autoContainerClasses = $containerClasses;
        return $this;
    }
    
    /**
     * To call before each data cell
     * @param int $nbColForThisCell
     * @return string
     */
    public function autoNewCol(int $nbColForThisCell = 1, array $classes = [], array $attrs = []):string
    {
        !$this->autoStarted && $this->autoStart();
        $src = '';
        $this->status === self::STATUS_IN_CELL      && $src .= $this->endCell();
        $this->status === self::STATUS_OK           && $src .= $this->beginContainer($this->autoFluid, $this->autoContainer, $this->autoContainerClasses);
        $this->status === self::STATUS_IN_CONTAINER && $src .= $this->beginRow();
        if ($this->status !== self::STATUS_IN_ROW) {
            Checkers::notice('Bad status, expected: ' . self::STATUS_IN_ROW . ', detected: ' . $this->status);
//            if ($this->autoCurrentCol + $nbColForThisCell > $this->autoCols) {
//                Checkers::notice('Grid incoherence: too big cell in end of row.');
//            }
        }
        if ($this->autoCurrentCol + $nbColForThisCell > $this->autoCols) {
            $src .= $this->endRow()->beginRow();
            $this->autoCurrentCol = 0;
        }
        $src .= $this->callBeginCell($this->autoCols / $nbColForThisCell, $classes, $attrs);
        $this->autoCurrentCol += $nbColForThisCell;
        return $src;
    }
    
    /**
     * To call at the end of your grid
     * @return string
     */
    public function autoStop():string
    {
        $src = '';
        $this->status === self::STATUS_IN_CELL      && $src .= $this->endCell();
        $this->status === self::STATUS_IN_ROW       && $src .= $this->endRow();
        $this->status === self::STATUS_IN_CONTAINER && $src .= $this->endContainer($this->autoContainer);
        $this->autoCols = self::DEFAULT_COLS;
        $this->autoFluid = self::DEFAULT_FLUID;
        $this->autoCurrentCol = 0;
        $this->autoStarted = false;
        return $src;
    }
    
    // GRID COUNT
    
    /**
     * Réinitialisation des compteurs de grille
     * @return $this
     */
    protected function gridReset()
    {
        $this->gridCount = ['xs' => 0, 'sm' => 0, 'md' => 0, 'lg' => 0];
        return $this;
    }
    
    /**
     * Incrémentation des compteurs de grille
     * @param int $lg
     * @param int $md
     * @param int $sm
     * @param int $xs
     * @return $this
     */
    protected function gridIncr(int $lg, int $md, int $sm, int $xs)
    {
        $this->gridCount['lg'] += $lg;
        $this->gridCount['md'] += $md;
        $this->gridCount['sm'] += $sm;
        $this->gridCount['xs'] += $xs;
        return $this;
    }
    
    /**
     * Calcul et renvoi des restes par rapport au nombre maximum de colonnes (12)
     * @return array
     */
    protected function gridRest()
    {
        $rest = [
            'lg' => 12 - ($this->gridCount['lg'] % 12),
            'md' => 12 - ($this->gridCount['md'] % 12),
            'sm' => 12 - ($this->gridCount['sm'] % 12),
            'xs' => 12 - ($this->gridCount['xs'] % 12)
        ];
        return $rest;
    }

    /**
     * Decorate automatically an array of strings with a grid
     * @param array $cellsData
     * @param int $nbColsByRow
     * @return string
     */
    public function auto(
            array $cellsData, 
            int   $nbColsByRow = null, 
            bool  $container = false, 
            array $classes = [], 
            array $attrs = [], 
            array $containerClasses = [], 
            bool  $fluid = true):string
    {
        $nbColsByRow = $nbColsByRow ?? count($cellsData);
        $src = $this->autoStart($nbColsByRow, $container, $fluid, $containerClasses);
        foreach ($cellsData as $cell) {
            $src .= $this->autoNewCol(1, $classes, $attrs);
            $src .= (string) $cell;
        }
        $src .= $this->autoStop();
        return $src;
    }
    
    /**
     * Ajout de données
     * @param string $content
     * @return $this
     */
    public function append($content)
    {
        $this->html((string) $content);
        return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function __toString() {
        return $this->getHtml();
    }

    public function __destruct()
    {
        if ($this->status != self::STATUS_OK) {
            Checkers::notice('Incomplete grid detected, status is [' . $this->status . ']');
        }
        if ($this->containerCount != 0) {
            Checkers::notice('Grid container started but not stopped');
        }
        if ($this->rowCount != 0) {
            Checkers::notice('Row grid started but not stopped');
        }
        if ($this->cellCount != 0) {
            Checkers::notice('Cell grid started but not stopped');
        }
    }
}