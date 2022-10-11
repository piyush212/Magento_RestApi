<?php
namespace Hello\RestApi\Api;
interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Hello\RestApi\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);
    /**
     * Set descriptions for the products.
     *
     * @param \Hello\RestApi\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);
    /**
     * GET product by its ID
     *
     * @api
     * @param string $id
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getProductById($id);

    
}