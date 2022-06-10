<?php
namespace Osf\Pdf\Document\Bean\Addon;

use Osf\Pdf\Document\Bean\ProductBean;

/**
 * Product list management
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
trait Products
{
    protected $products = [];
    
    /**
     * @param ProductBean $product
     * @return $this
     */
    public function addProduct(ProductBean $product)
    {
        $this->products[] = $product;
        return $this;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
}
