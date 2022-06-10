<?php
namespace Osf\Application;

use Locale as PhpLocale;

/**
 * I18n
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage locale
 */
class Locale extends PhpLocale
{
    public function getLangKey()
    {
        return $this->getPrimaryLanguage($this->getDefault());
    }
    
    public function getLangName()
    {
        return $this->getDisplayName($this->getDefault());
    }
}
