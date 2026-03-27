<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication;

use Spryker\Zed\DataImport\Communication\Console\Executor\DataImportersDumpExecutor;
use Spryker\Zed\DataImport\Communication\Console\Executor\DataImportersDumpExecutorInterface;
use Spryker\Zed\DataImport\Communication\Console\Executor\DataImportExecutor;
use Spryker\Zed\DataImport\Communication\Console\Executor\DataImportExecutorInterface;
use Spryker\Zed\DataImport\Communication\Console\Mapper\DataImportConfigurationMapper;
use Spryker\Zed\DataImport\Communication\Console\Mapper\DataImportConfigurationMapperInterface;
use Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationParserInterface;
use Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationYamlParser;
use Spryker\Zed\DataImport\Communication\Console\ProgressBar\ProgressBarHelper;
use Spryker\Zed\DataImport\Communication\Console\ProgressBar\ProgressBarHelperInterface;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilDataReaderServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 */
class DataImportCommunicationFactory extends AbstractCommunicationFactory
{
    public function createDataImportConfigurationYamlParser(): DataImportConfigurationParserInterface
    {
        return new DataImportConfigurationYamlParser(
            $this->getUtilDataReaderService(),
            $this->createDataImportConfigurationMapper(),
        );
    }

    public function createDataImportConfigurationMapper(): DataImportConfigurationMapperInterface
    {
        return new DataImportConfigurationMapper();
    }

    public function createDataImportExecutor(): DataImportExecutorInterface
    {
        return new DataImportExecutor(
            $this->createDataImportConfigurationYamlParser(),
            $this->getFacade(),
        );
    }

    public function createDataImporterDumpExecutor(): DataImportersDumpExecutorInterface
    {
        return new DataImportersDumpExecutor(
            $this->createDataImportConfigurationYamlParser(),
            $this->getFacade(),
            $this->getConfig(),
        );
    }

    public function getUtilDataReaderService(): DataImportToUtilDataReaderServiceInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::SERVICE_UTIL_DATA_READER);
    }

    public function createProgressBarHelper(): ProgressBarHelperInterface
    {
        return new ProgressBarHelper();
    }
}
