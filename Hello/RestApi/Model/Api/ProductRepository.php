<?php
namespace Hello\RestApi\Model\Api;
use Hello\RestApi\Api\ProductRepositoryInterface;
use Hello\RestApi\Api\RequestItemInterfaceFactory;
use Hello\RestApi\Api\ResponseItemInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
/**
 * Class ProductRepository
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Action
     */
    private $productAction;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var RequestItemInterfaceFactory
     */
    private $requestItemFactory;
    /**
     * @var ResponseItemInterfaceFactory
     */
    private $responseItemFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @param Action $productAction
     * @param CollectionFactory $productCollectionFactory
     * @param RequestItemInterfaceFactory $requestItemFactory
     * @param ResponseItemInterfaceFactory $responseItemFactory
     * @param StoreManagerInterface $storeManager
     */
  
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
       \Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

  
    public function getProductById($id)
    {
      
        return $this->productRepository->getById($id);
    }
   
    
    public function getItem(int $id) : mixed
    {
        $collection = $this->getProductCollection()
            ->addAttributeToFilter('entity_id', ['eq' => $id]);
        /** @var ProductInterface $product */
        $product = $collection->getFirstItem();
        if (!$product->getId()) {
            throw new NoSuchEntityException(__('Product not found'));
        }
        return $this->getResponseItemFromProduct($product);
    }
    /**
     * {@inheritDoc}
     *
     * @param RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products) : void
    {
        foreach ($products as $product) {
            $this->setDescriptionForProduct(
                $product->getId(),
                $product->getDescription()
            );
        }
    }
    /**
     * @return Collection
     */
    private function getProductCollection() : mixed
    {
        /** @var Collection $collection */
        $collection = $this->productCollectionFactory->create();
        $collection
            ->addAttributeToSelect(
                [
                    'entity_id',
                    ProductInterface::SKU,
                    ProductInterface::NAME,
                    'description'
                ],
                'left'
            );
        return $collection;
    }
    /**
     * @param ProductInterface $product
     * @return ResponseItemInterface
     */
    private function getResponseItemFromProduct(ProductInterface $product) : mixed
    {
        /** @var ResponseItemInterface $responseItem */
        $responseItem = $this->responseItemFactory->create();
        $responseItem->setId($product->getId())
            ->setSku($product->getSku())
            ->setName($product->getName())
            ->setDescription($product->getDescription());
        return $responseItem;
    }
    /**
     * Set the description for the product.
     *
     * @param int $id
     * @param string $description
     * @return void
     */
    private function setDescriptionForProduct(int $id, string $description) : void
    {
        $this->productAction->updateAttributes(
            [$id],
            ['description' => $description],
            $this->storeManager->getStore()->getId()
        );
    }
}
