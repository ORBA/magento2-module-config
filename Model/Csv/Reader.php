<?php
/**
 * @copyright Copyright (c) 2020 Orba Sp. z o.o. (http://orba.co)
 */

namespace Orba\Config\Model\Csv;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Orba\Config\Model\Csv\Config\ConfigFactory;
use Orba\Config\Model\Csv\Validator\RequiredColumnsValidator;
use Orba\Config\Model\MappedConfigCollection;
use Orba\Config\Model\MappedConfigCollectionFactory;

class Reader
{
    /** @var Csv */
    private $csv;

    /** @var ConfigFactory */
    private $configFactory;

    /** @var RequiredColumnsValidator */
    private $requiredColumnsValidator;

    /** @var MappedConfigCollection */
    private $mappedConfigCollection;

    /**
     * Reader constructor.
     * @param Csv $csv
     * @param ConfigFactory $configFactory
     * @param RequiredColumnsValidator $requiredColumnsValidator
     * @param MappedConfigCollectionFactory $mappedConfigCollectionFactory
     */
    public function __construct(
        Csv $csv,
        ConfigFactory $configFactory,
        RequiredColumnsValidator $requiredColumnsValidator,
        MappedConfigCollectionFactory $mappedConfigCollectionFactory
    ) {
        $this->csv = $csv;
        $this->configFactory = $configFactory;
        $this->requiredColumnsValidator = $requiredColumnsValidator;
        $this->mappedConfigCollection = $mappedConfigCollectionFactory->create();
    }

    /**
     * @param string $path
     * @param string|null $env
     * @return MappedConfigCollection
     * @throws LocalizedException
     */
    public function readConfigFile(string $path, ?string $env = null): MappedConfigCollection
    {
        try {
            $data = $this->csv->getData($path);
        } catch (Exception $e) {
            throw new LocalizedException(
                __('File %1 can not be read', $path)
            );
        }
        $this->requiredColumnsValidator->validate($data);

        // remove headers from data
        $headers = $data[0];
        $data = array_slice($data, 1);

        foreach ($data as $row) {
            $config = $this->configFactory->create($headers, $row, $env);
            $this->mappedConfigCollection->add($config);
        }
        return $this->mappedConfigCollection;
    }
}
