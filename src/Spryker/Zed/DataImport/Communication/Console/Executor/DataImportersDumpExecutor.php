<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Executor;

use Spryker\Zed\DataImport\Business\DataImportFacadeInterface;
use Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationParserInterface;
use Spryker\Zed\DataImport\DataImportConfig;

class DataImportersDumpExecutor implements DataImportersDumpExecutorInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationParserInterface
     */
    protected $dataImportConfigurationParser;

    /**
     * @var \Spryker\Zed\DataImport\Business\DataImportFacadeInterface
     */
    protected $dataImportFacade;

    /**
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected $dataImportConfig;

    public function __construct(
        DataImportConfigurationParserInterface $dataImportConfigurationParser,
        DataImportFacadeInterface $dataImportFacade,
        DataImportConfig $dataImportConfig
    ) {
        $this->dataImportConfigurationParser = $dataImportConfigurationParser;
        $this->dataImportFacade = $dataImportFacade;
        $this->dataImportConfig = $dataImportConfig;
    }

    /**
     * @return array<string>
     */
    public function executeDataImportersDump(): array
    {
        $configPath = $this->dataImportConfig->getDefaultYamlConfigPath();
        $dataImportConfigurationTransfer = $this->dataImportConfigurationParser->parseConfigurationFile($configPath);

        return $this->dataImportFacade->getImportersDumpByConfiguration($dataImportConfigurationTransfer);
    }
}
