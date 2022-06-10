<?php
namespace Osf\Pdf\Document\Config;

use Osf\Pdf\Document\Bean\InvoiceBean;

/**
 * Invoice PDF document configuration file
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 31 oct. 2013
 * @package osf
 * @subpackage pdf
 */
class InvoiceConfig extends BaseDocumentConfig
{
    protected $fontSize = 9;
    
    public function getColsParams($hasTax)
    {
        return [
            'code'     => [
                'label' => __("Code"),
                'size'  => 20,
                'align' => 'L'
            ],
            'title'     => [
                'label' => __("Désignation"),
                'size'  => 0, // auto
                'align' => 'L'
            ],
            'price'    => [
                'label' => ($hasTax ? __("P.U. HT") : __("Prix Unitaire")),
                'size'  => ($hasTax ? 18 : 20),
                'align' => 'R'
            ],
            'tax'      => [
                'label' => __("TVA"),
                'size'  => 13,
                'align' => 'R'
            ],
            'ttc'      => [
                'label' => __("P.U. TTC"),
                'size'  => 18,
                'align' => 'R'
            ],
            'quantity' => [
                'label' => __("Quantité"),
                'size'  => 15,
                'align' => 'R'
            ],
            'discount' => [
                'label' => __("Remise"),
                'size'  => 14,
                'align' => 'R'
            ],
            'tttc'      => [
                'label' => ($hasTax ? __("Total TTC") : __("Total")),
                'size'  => 18,
                'align' => 'R'
            ]
        ];
    }
    
    /**
     * Document title, displayed at top right
     * @param type $titleKey
     * @param bool $isCredit
     * @return string
     */
    public function getInvoiceTypeTitle($titleKey, bool $isCredit = false): string
    {
        $titles = [
            InvoiceBean::TYPE_QUOTE    => __("DEVIS"),
            InvoiceBean::TYPE_ORDER    => __("COMMANDE"),
            InvoiceBean::TYPE_INVOICE  => $isCredit ? __("FACTURE D'AVOIR") : __("FACTURE"),
        ];
        return $titles[$titleKey];
    }
}