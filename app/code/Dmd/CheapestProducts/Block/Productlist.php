<?php
/**
 * @author Daniel Martinez <dani.dmd86@gmail.com>
 *
 * @package Dmd
 * @version 1
 */
namespace Dmd\CheapestProducts\Block;

/**
 * Class Productlist
 * @package Dmd\CheapestProducts\Block
 */
class Productlist extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Dmd\CheapestProducts\Model\System\Config
     */
    protected $_systemConfig;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $_stockFilter;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_productVisibility;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Number of product to be displayed in the top cheapest products.
     * @var integer
     */
    protected $numberProductsToDisplay;

    /**
     * Category where the cheapest products will be calculated.
     * @var \Magento\Catalog\Model\Category
     */
    protected $category;

    /**
     * Productlist constructor.
     * @param \Dmd\CheapestProducts\Model\System\Config $systemConfig
     * @param \Magento\CatalogInventory\Helper\Stock $stockFilter
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     * @return void
     */
    public function __construct(
        \Dmd\CheapestProducts\Model\System\Config $systemConfig,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    )
    {
        $this->_systemConfig        = $systemConfig;
        $this->_stockFilter         = $stockFilter;
        $this->_productVisibility   = $productVisibility;
        $this->_categoryFactory     = $categoryFactory;
        parent::__construct($context, $data);
    }

    /**
     * Load the category where the cheapest products will be calculated.
     *
     * @param $categoryId Identifier of the category to be load.
     * @return \Magento\Catalog\Model\Category
     */
    protected function getCategory($categoryId)
    {
        if( !$this->category )
        {
            // If the id of the category it's not set in the argument of the block we take the one in the backend.
            if( !$categoryId )
            {
                $categoryId = $this->_systemConfig->getCategoryId();
            }
            $this->category = $this->_categoryFactory->create()->load($categoryId);
        }
        return $this->category;
    }

    /**
     * Get the category name of the cheapest products.
     *
     * @param $categoryId Identifier of the category to be load.
     * @return string
     */
    public function getCategoryName($categoryId)
    {
        return $this->getCategory($categoryId)->getName();
    }

    /**
     * Get the number of products to display. Top X.
     * This value it's configured though the backend.
     *
     * @return integer
     */
    public function getNumberOfProductsToDisplay()
    {
        if( !$this->numberProductsToDisplay )
        {
            $this->numberProductsToDisplay = $this->_systemConfig->getNumberOfProducts();
        }
        return $this->numberProductsToDisplay;
    }

    /**
     * Get the top X cheapest products from a category.
     * The products must be in stock, visible or in a catalog and enabled.
     * The category it's defined in the backend.
     *
     * @param $categoryId Identifier of the category to be load.
     * @return Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts($categoryId)
    {
        $products = $this->getCategory($categoryId)->getProductCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
                        ->setVisibility($this->_productVisibility->getVisibleInSiteIds())
                        ->addAttributeToSort('price', 'ASC')
                        ->setPageSize($this->getNumberOfProductsToDisplay());
        $this->_stockFilter->addIsInStockFilterToCollection($products);
        return $products;
    }
}