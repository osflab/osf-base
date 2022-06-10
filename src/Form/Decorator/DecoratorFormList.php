<?php
namespace Osf\Form\Decorator;

/**
 * Decorate form : list
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 * @deprecated
 */
class DecoratorFormList extends DecoratorFormAbstract
{
    protected $beforeForm     = "\n<div class=\"osform\">\n";
    protected $afterForm      = "\n</div>\n";
    protected $beforeElements = "\n <dl>\n";
    protected $afterElements  = " </dl>\n";
    protected $beforeLabel    = "  <dt>";
    protected $afterLabel     = "</dt>\n";
    protected $beforeElement  = "  <dd>";
    protected $afterElement   = "</dd>\n";
    protected $beforeErrors   = "  <ul class=\"osformerrors\">\n";
    protected $afterErrors    = "  </ul>\n";
    protected $beforeError    = "   <li>";
    protected $afterError     = "<li>\n";
}