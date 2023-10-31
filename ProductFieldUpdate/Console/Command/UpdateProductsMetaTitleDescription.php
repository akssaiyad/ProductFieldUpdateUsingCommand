<?php
namespace Aks\ProductFieldUpdate\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Aks\ProductFieldUpdate\Model\UpdateMetaTitleDescription;
use Psr\Log\LoggerInterface;
 
class UpdateProductsMetaTitleDescription extends Command
{
    private $logger;
    private $productsDataUpdate;

    public function __construct(
        LoggerInterface $logger,
        UpdateMetaTitleDescription $productsDataUpdate
    ) {
        $this->logger = $logger;
        $this->productsDataUpdate = $productsDataUpdate;

        parent::__construct();
    }

    protected function configure()
    {
        $this ->setName('aks:updateproductsdata:meta-title-description')
              ->setDescription('Update Products Meta Title And Description.')
              ->setDefinition([]);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->productsDataUpdate->updateProductsMetaTitleDescription();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
