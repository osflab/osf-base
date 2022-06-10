<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\Stream\Html;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;

/**
 * Generate html badge
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Badges
{
    protected $badges = [];
    
    /**
     * Add a badge (short string)
     * A null value remove all the badges
     * @param string $value
     * @param string $color par défaut, couleur de la box
     * @return $this
     */
    public function addBadge($label, $colorOrStatus = null, $toolTipLabel = null, $url = null)
    {
        if ($label === null) {
            $this->badges = [];
        } else {
            $this->badges[] = $this->getBadgeElementArray($label, $colorOrStatus, $toolTipLabel, $url);
        }
        return $this;
    }
    
    /**
     * Automatic percentage display
     * @param int $percentage
     * @param string $toolTipLabel
     * @return $this
     */
    public function addBadgePercentage($percentage, $toolTipLabel = null, $url = null)
    {
        $percentage = min(100, max(0, floor($percentage)));
        $color = AVH::getPercentageColor($percentage);
        return $this->addBadge($percentage . '%', $color, $toolTipLabel, $url);
    }
    
    /**
     * Add further badges
     * @param array $badges
     * @return $this
     * @throws \Osf\Exception\ArchException
     */
    public function addBadges(array $badges)
    {
        foreach ($badges as $badge) {
            if (!is_array($badge)) {
                throw new \Osf\Exception\ArchException('Bad badges value');
            }
            call_user_func_array([$this, 'addBadge'], $badge);
        }
        return $this;
    }
    
    protected function getBadgeElementArray($label, $colorOrStatus, $toolTipLabel, $url)
    {
        return [($label === null ? '' : trim($label)), 
                $colorOrStatus, 
                trim($toolTipLabel),
                (string) $url];
    }
    
    /**
     * Build HTML code of a badge
     * @param string $label
     * @param string $colorOrStatus
     * @param string $toolTipLabel
     * @return string
     */
    protected function getBadgeHtml($label, $colorOrStatus = null, $toolTipLabel = null, $url = null)
    {
        $element = 'span';
        if ($colorOrStatus === null) {
            $color = 'black';
        } else if (in_array($colorOrStatus, AVH::STATUS_LIST)) {
            Checkers::checkStatus($colorOrStatus, 'default');
            $color = AVH::STATUS_COLOR_LIST[$colorOrStatus];
        } else {
            Checkers::checkColor($colorOrStatus);
            $color = $colorOrStatus;
        }
        $attributes = ['class' => 'badge bg-' . $color];
        if ($toolTipLabel !== null) {
            $attributes['data-toggle'] = 'tooltip';
            $attributes['title'] = $toolTipLabel;
        }
        if ($url) {
            $element = 'a';
            $attributes['href'] = $url;
        }
        if (strlen($label) > 30) {
            Checkers::notice('Badge label [' . $label . '] is probably too long');
        }
        return Html::buildHtmlElement($element, $attributes, $label);
    }
    
    /**
     * @return bool
     */
    public function hasBadges()
    {
        return (bool) $this->badges;
    }
    
    /**
     * Build HTML code of badges
     * @param array $badges
     * @return string
     */
    protected function getBadgesHtml(array $badges = null)
    {
        if ($badges === null) {
            $badges = $this->badges;
        }
        $html = '';
        foreach ($badges as $badge) {
            $html .= $this->getBadgeHtml($badge[0], $badge[1], $badge[2], $badge[3]);
        }
        return $html;
    }
    
    protected function initBadges(array $vars)
    {
        $this->badges = [];
        if (isset($vars['badge'])) {
            $this->addBadge($vars['badge']);
        }
        if (isset($vars['badges'])) {
            $this->addBadges($vars['badges']);
        }
    }
}