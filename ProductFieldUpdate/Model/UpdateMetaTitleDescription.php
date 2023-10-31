<?php
namespace Aks\ProductFieldUpdate\Model;

/*use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Helper\Image as ProductImage;
use Aks\ProductFieldUpdate\Helper\Data as Helper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\ResourceConnection;*/
use Psr\Log\LoggerInterface;

class UpdateMetaTitleDescription
{
    private $logger;
    /*private $productRepository;
    private $stockRegistry;
    private $searchCriteria;
    private $productImage;
    private $helper;
    private $categoriesPaths;
    private $storeManager;
    private $state;
    private $categoryRepository;*/

    public function __construct (
        LoggerInterface $logger/*,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $criteria,
        ProductImage $productImage,
        StockRegistryInterface $stockRegistry,
        Helper $helper,
        StoreManagerInterface $storeManager,
        State $state,
        ResourceConnection $resourceConnection,
        CategoryRepository $categoryRepository*/
    )
    {
        $this->logger = $logger;
        /*$this->productRepository = $productRepository;
        $this->searchCriteria = $criteria;
        $this->productImage = $productImage;
        $this->stockRegistry = $stockRegistry;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->connection = $resourceConnection->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $this->categoriesPaths = $helper->getCategoriesPaths();*/
        //$this->state = $state->setAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB);
    }


    public function updateProductsMetaTitleDescription()
    {
        $bootstrap = Bootstrap::create(BP, $_SERVER);
        $objectManager = $bootstrap->getObjectManager();
        $state = $objectManager->get('Magento\Framework\App\State');
        $state->setAreaCode('frontend');
        $store = 0;
        $product_collections = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collections = $product_collections->create();
        $collections = $collections->addFieldToFilter('visibility', 4);
        echo "\n";
        echo count($collections);
        echo "\n";
        $this->logger->info('Update Products Meta Title And Description...');
        foreach ($collections as $product) {
            $productId  = $product->getId(); 
            $product    = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            $name       = $product->getName();
            $description = $product->getSize() ? ' Select your size '.$product->getSize().' World wide shipping available. Buy Now.' : ' World wide shipping available. Buy Now.';
            $customTile  = $product->getSize() ? $product->getName().' | Size '.$product->getSize().' | {Company Name}' : $product->getName().' | {Company Name}';
            $customDescription = 'Buy '.$product->getName(). ' at {Company Name}.'. $description;
            try{
                $product->setStoreId($store);
                $product->setMetaTitle($customTile);
                $product->setMetaDescription($customDescription);
                $product->save();
                echo '-';
       
            }catch (\Exception $e) {
                echo "\n";
                echo "Error Id : " . $productId;
                echo "\n";
                die($e->getMessage());
            }
        }
        $this->logger->info('Products Meta Title And Description Updated!');
    }

}
