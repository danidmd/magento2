<?php
/**
 * @author Daniel Martinez <dani.dmd86@gmail.com>
 *
 * @package Dmd
 * @version 1
 */
namespace Dmd\CheapestProducts\Model\Config\Source;

/**
 * Class Categorylist
 *
 * @package Dmd\CheapestProducts\Model\Config\Source
 */
class Categorylist implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var \Magento\Catalog\Model\CategoryRepository
     */
    protected $_categoryRepository;

    /**
     * List of categories and subcategories.
     *
     * @var array
     */
    protected $categoryList;

    /**
     * Categorylist constructor.
     *
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Catalog\Model\CategoryRepository $categoryRepository
     * @return void
     */
    public function __construct(
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository
    )
    {
        $this->_categoryHelper = $catalogCategory;
        $this->_categoryRepository = $categoryRepository;
    }

    /**
     * Load all the categories and subcategories.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $arr = $this->getCategories();
        $ret = [];
        foreach( $arr as $key => $value )
        {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    /**
     * Get all the categories and subcategories.
     *
     * @return array
     */
    public function getCategories()
    {
        $categories = $this->_categoryHelper->getStoreCategories(true,false,true);
        foreach($categories as $category)
        {
            // Main categories
            $this->categoryList[$category->getEntityId()] = __($category->getName());
            // Subcategories
            $this->getSubcategories($category);
        }
        return $this->categoryList;
    }

    /**
     * Get the subcategories.
     *
     * @param \Magento\Framework\Data\Tree\Node $category
     * @return void
     */
    public function getSubcategories($category)
    {
        $categoryObj = $this->_categoryRepository->get($category->getId());

        // Add - to align the subcategorties under the category.
        $level = $categoryObj->getLevel();
        $arrow = '|'.str_repeat("--", $level-1) . '>';

        // Load the subcategories recursively.
        $subcategories = $categoryObj->getChildrenCategories();
        foreach($subcategories as $subcategory)
        {
            $this->categoryList[$subcategory->getEntityId()] = __($arrow.$subcategory->getName());
            if( $subcategory->hasChildren() )
            {
                $this->getSubcategories($subcategory);
            }
        }
    }
}