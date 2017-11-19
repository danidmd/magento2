Dmd_CheapestProducts module calculate the top cheapest products from a category.

To see the result of the module, go to http://magento2.local/dmdcheapestproducts/index/display

NOTE:
As I did not know the user level, the module can be configured in 2 different ways.
1-  The user can define the category through the backend of Magento.
    Stores / Configuration -> DMD / Cheapest Products -> Settings:
        Category Id - Category where it will be applied the cheapest product.
        Number of products to show - Top X products. 
2-  If the user it is advanced enough, the module can be used in the design section of the CMS pages, categories, etc. 
    The category_id can be set as an argument of the block.
    EX: 
    <block class="Dmd\CheapestProducts\Block\Productlist" name="product-list-cat-22" template="cheapest_products.phtml">
        <arguments>
            <argument name="category_id" xsi:type="number">22</argument>
        </arguments>
    </block>
    In case there is no argument "category_id", it will take the category of the backend.
    
*The 2nd ways has preference over the 1st one. 
If the module it is configure in the backend of Magento and in the layout, it will be applied the layout configuration.