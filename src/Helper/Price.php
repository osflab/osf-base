<?php
namespace Osf\Helper;

/**
 * Prices manipulations
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage helper
 */
class Price
{
    /**
     * HT to TTC transformation
     * @param float $price
     * @param float $tax
     * @param bool $taxIsPercent
     * @return float
     */
    public static function htToTtc($price, $tax, bool $taxIsPercent = false)
    {
        // Taxe exprimée en pourcentage, on divise par 100
        if ($taxIsPercent) {
            $tax = $tax / 100;
        }
        
        // Addition du prix HT à payer et de la part de taxe sur ce prix HT = prix TTC
        return $price + ($price * $tax);
    }
    
    /**
     * TTC to HT transformation
     * @param float $price
     * @param float $tax
     * @param bool $taxIsPercent
     * @return float
     */
    public static function TtcToHt($price, $tax, bool $taxIsPercent = false)
    {
        // Taxe exprimée en pourcentage, on divise par 100
        if ($taxIsPercent) {
            $tax = $tax / 100;
        }
        
        // Retrait de la part de la taxe dans le prix TTC = prix HT
        return $price / (1 + $tax);
    }
    
    /**
     * Get a price minus the discount
     * @param float $price
     * @param float $discount
     * @param bool $discountIsPercent
     * @return float
     */
    public static function priceWithDiscount($price, $discount, bool $discountIsPercent = false)
    {
        // Réduction exprimée en pourcentage, on divise par 100
        if ($discountIsPercent) {
            $discount = $discount / 100;
        }
        
        // Retrait de la part de réduction au tarif donné
        return $price - ($price * $discount);
    }
}
