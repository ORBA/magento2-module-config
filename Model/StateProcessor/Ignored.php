<?php
namespace Orba\Config\Model\StateProcessor;

use Orba\Config\Api\ConfigInterface;
use Orba\Config\Api\MappedConfigCollectionInterface;
use Orba\Config\Model\Config\OperationsRegistry;
use Orba\Config\Model\StateProcessorInterface;

/**
 * Class Ignored
 * Skip this config
 */
class Ignored implements StateProcessorInterface
{
    /**
     * @param ConfigInterface $fileConfig
     * @param MappedConfigCollectionInterface $databaseConfigs
     * @param OperationsRegistry $operationsRegistry
     */
    public function process(
        ConfigInterface $fileConfig,
        MappedConfigCollectionInterface $databaseConfigs,
        OperationsRegistry $operationsRegistry
    ): void {
        $operationsRegistry->addIgnored($fileConfig);
    }
}
