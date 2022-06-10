<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\Title;
use Osf\View\Helper\Addon\Content;
use Osf\View\Helper\Addon\Footer;
use Osf\View\Helper\Addon\Header;
use Osf\View\Helper\Addon\Prepend;
use Osf\View\Helper\Addon\Append;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap multifunction box
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Box extends AVH
{
    use Title;
    use Content;
    use Footer;
    use Header;
    use Prepend;
    use Append;
    use EltDecoration;
    use Addon\Icon;
    use Addon\Status;
    use Addon\Badges;
    use Addon\Bufferize;
    use Addon\Removable;
    
    protected $coloredTitleBox  = false;
    protected $collapsable      = false;
    protected $expandable       = false;
    protected $stackContainer   = false;
    protected $largeHeader      = false;
    protected $footerAlignRight = true;
    protected $containerId      = null;

    /**
     * Information box with link
     * @param string $title
     * @param string $content
     * @param string $type
     * @param string $badge
     * @param bool $coloredTitleBox
     * @param bool $collapsable
     * @param bool $expandable
     * @param bool $removable
     * @return \Osf\View\Helper\Bootstrap\Box
     */
    public function __invoke(
            $title, 
            $content = null, 
            $badge   = null, 
            bool $coloredTitleBox = false, 
            bool $collapsable     = false, 
            bool $expandable      = false, 
            bool $removable       = false)
    {
        // Réinitialisation des valeurs car l'ensemble des boxes qui ne 
        // sont pas bufferisées utilisent la même instance pour des raisons 
        // de performances
        $this->initValues(get_defined_vars());
        $this->iconIsNullable();
        $this->statusIsNullable();
        $this->setFooterClasses(['box-footer']);
        $this->stackContainer = false;
        $this->containerId = null;
        $this->coloredTitleBox($coloredTitleBox)
             ->collapsable($collapsable)
             ->expandable($expandable);
        return $this;
    }
    
    /**
     * Full colored title box
     * @param bool $trueOrFalse
     * @return $this
     */
    public function coloredTitleBox($trueOrFalse = true)
    {
        $this->coloredTitleBox = (bool) $trueOrFalse;
        return $this;
    }
    
    /**
     * Expandable (displayed collapsed first)
     * @param bool $trueOrFalse
     * @return $this
     */
    public function expandable($trueOrFalse = true)
    {
        $this->expandable = (bool) $trueOrFalse;
        if ($this->expandable && $this->collapsable) {
            trigger_error('Expandable cancel the collapsable property', E_USER_NOTICE);
            $this->collapsable = false;
        }
        return $this;
    }
    
    /**
     * Collapsable box
     * @param bool $trueOrFalse
     * @return $this
     */
    public function collapsable($trueOrFalse = true)
    {
        $this->collapsable = (bool) $trueOrFalse;
        if ($this->collapsable && $this->expandable) {
            trigger_error('Collapsable cancel the expandable property', E_USER_NOTICE);
            $this->expandable = false;
        }
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setStackContainer($stackContainer = true)
    {
        $this->stackContainer = (bool) $stackContainer;
        return $this;
    }

    /**
     * Add nav in content
     * @param \Osf\View\Helper\Bootstrap\Nav $nav
     * @return $this
     */
    public function addNav(Nav $nav)
    {
        $nav->setStacked();
        $this->setContent((string) $nav);
        $this->setStackContainer();
        return $this;
    }
    
    /**
     * @param \Osf\View\Helper\Bootstrap\Table $table
     * @return $this
     */
    public function addTable(Table $table)
    {
        $this->setContent((string) $table->render());
        $this->setFooter($table->getButtons());
        if (!$this->hasBadges()) {
            $this->setHeader($table->getButtons() . $table->getHeader());
        }
        if (($table->getButtons() || $table->getHeader()) && !$this->hasBadges()) {
           $this->setLargeHeader();
        }
        $this->setContentHtmlTable((bool) $table->getResponsive());
        $this->containerId = $table->getBoxId();
        return $this;
    }
    
    /**
     * Content is html table
     * @param bool $responsive
     * @return $this
     */
    public function setContentHtmlTable(bool $responsive = true)
    {
        $this->setStackContainer();
        $this->addCssClass($responsive ? 'table-responsive' : 'table');
        return $this;
    }
    
    /**
     * @param $largeHeader bool
     * @return $this
     */
    public function setLargeHeader($largeHeader = true)
    {
        $this->largeHeader = (bool) $largeHeader;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLargeHeader():bool
    {
        return $this->largeHeader;
    }
    
    /**
     * @param $footerAlignRight bool
     * @return $this
     */
    public function setFooterAlignRight($footerAlignRight = true)
    {
        $this->footerAlignRight = (bool) $footerAlignRight;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFooterAlignRight():bool
    {
        return $this->footerAlignRight;
    }
    
    /**
     * Render the box at the end of configuration
     * @return string
     */
    public function render()
    {
        $this->addCssClass('box');
        $this->addCssClass('box-' . $this->getStatus(), $this->getStatus());
        $this->addCssClass('collapsed-box', $this->expandable);
        $this->addCssClass('box-solid', $this->coloredTitleBox);
        $boxTools = isset($this->badges[0]) || $this->expandable || $this->collapsable || $this->removable;
        $badges =  isset($this->badges[0]) ? $this->getBadgesHtml() : '';
        return $this
            ->html('<div id="' . $this->containerId . '">', $this->containerId)
            ->html($this->getPrepend(), $this->hasPrepend())
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('  <div class="box-header with-border">')
            ->html('    <div class="lg-head">', $this->largeHeader)
            ->html('    ' . $this->getIconHtml(), $this->icon)
            ->html('    <h3 class="box-title">' . $this->title . '</h3>')
            ->html('    </div>', $this->largeHeader)
            ->html('    <div class="box-tools pull-right">', $boxTools)
            ->html($badges)
            ->html('      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>', $this->expandable)
            ->html('      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>', $this->collapsable)
            ->html('      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>', $this->removable)
            ->html('    </div>', $boxTools)
            ->html('    <div class="pull-right">' . $this->header . '</div>', $this->header)
            ->html('  </div>')
            ->html('  <div class="box-body' . ($this->stackContainer ? ' no-padding' : '') . '">' . $this->content . '</div>')
            ->html('  <div class="' . implode(' ', $this->footerClasses) . '">', $this->footer)
            ->html('    <div class="pull-right">', $this->footer && $this->footerAlignRight)
            ->html($this->footer, $this->footer)
            ->html('    </div>', $this->footer && $this->footerAlignRight)
            ->html('  </div>', $this->footer)
            ->html('</div>')
            ->html($this->getAppend(), $this->hasAppend())
            ->html('</div>', $this->containerId)
            ->getHtml();
    }
}
