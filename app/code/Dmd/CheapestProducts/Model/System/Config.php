<?php
/**
 * @author Daniel Martinez <dani.dmd86@gmail.com>
 *
 * @package Dmd
 * @version 1
 */
namespace Dmd\CheapestProducts\Model\System;

/**
 * Class Config
 * @package Dmd\CheapestProducts\Model\System
 */
class Config
{
    const CATEGORY_ID           = 'dmdcheapestproducts/general/category_id';
    const NUMBER_OF_PRODUCTS    = 'dmdcheapestproducts/general/number_of_products';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Dependency injection.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @return void
     */
    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get the category Id to calculate the top cheapest products.
     *
     * @param null|string $store
     * @return integer
     */
    public function getCategoryId($store = null)
    {
        return $this->_scopeConfig->getValue( self::CATEGORY_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store );
    }

    /**
     * Get the number of items to display (top).
     *
     * @param null|string $store
     * @return integer
     */
    public function getNumberOfProducts($store = null)
    {
        return $this->_scopeConfig->getValue( self::NUMBER_OF_PRODUCTS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store );
    }
}