<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\DataReader\CsvReader;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Countable;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataReaderException;
use Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface;
use SprykerTest\Zed\DataImport\DataImportBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataReader
 * @group CsvReader
 * @group CsvAdapterReaderTest
 * Add your own group annotations below this line
 */
class CsvAdapterReaderTest extends Unit
{
    protected const int EXPECTED_STANDARD_IMPORT_DATA_SET_COUNT = 3;

    protected const string FILE_NAME_STANDARD_IMPORT = 'import-standard.csv';

    protected const string FILE_NAME_NOT_EXISTING = 'not-existing.csv';

    protected const string FILE_NAME_HEADER_DATA_SET_LENGTH_MISSMATCH = 'import-header-dataset-length-missmatch.csv';

    protected DataImportBusinessTester $tester;

    public function testDataReaderCanBeUsedAsIteratorAndReturnsArrayObject(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . static::FILE_NAME_STANDARD_IMPORT);
        foreach ($csvReader as $dataSet) {
            $this->assertInstanceOf(DataSet::class, $dataSet);
        }
    }

    public function testReaderIsCountable(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . static::FILE_NAME_STANDARD_IMPORT);
        $this->assertInstanceOf(Countable::class, $csvReader);
    }

    public function testKeyReturnsCurrentDataSetPosition(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . static::FILE_NAME_STANDARD_IMPORT);
        $this->assertIsInt($csvReader->key());
    }

    public function testThrowsExceptionWhenFileInvalid(): void
    {
        $this->expectException(DataReaderException::class);

        $this->getCsvReader(Configuration::dataDir() . static::FILE_NAME_NOT_EXISTING);
    }

    public function testReadsSourcePathRelativeToFileSystemRoot(): void
    {
        $applicationRootPath = rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $fileName = substr(Configuration::dataDir() . static::FILE_NAME_STANDARD_IMPORT, strlen($applicationRootPath));

        $csvReader = $this->getCsvReader($fileName, $applicationRootPath);

        $this->assertSame(static::EXPECTED_STANDARD_IMPORT_DATA_SET_COUNT, $csvReader->count());
    }

    public function testThrowsExceptionWhenHeaderAndDataSetLengthDoesNotMatch(): void
    {
        $this->expectException(DataSetWithHeaderCombineFailedException::class);
        $csvReader = $this->getCsvReader(Configuration::dataDir() . static::FILE_NAME_HEADER_DATA_SET_LENGTH_MISSMATCH);

        $csvReader->current();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\DataImportConfig
     */
    protected function getConfigMock(): DataImportConfig
    {
        $configMock = $this->getMockBuilder(DataImportConfig::class)
            ->onlyMethods(['isDataImportFromOtherSourceEnabled'])
            ->getMock();

        $configMock->method('isDataImportFromOtherSourceEnabled')
            ->willReturn(true);

        return $configMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface
     */
    protected function getFlysystemMock(string $fileSystemRootPath = ''): DataImportToFlysystemServiceInterface
    {
        $flysystemServiceMock = $this->getMockBuilder(DataImportToFlysystemServiceInterface::class)->getMock();
        $flysystemServiceMock->expects($this->any())
            ->method('has')
            ->willReturnCallback(static function (string $filesystemName, string $path) use ($fileSystemRootPath): bool {
                return is_file($fileSystemRootPath . $path);
            });
        $flysystemServiceMock->expects($this->any())
            ->method('readStream')
            ->willReturnCallback(static function (string $filesystemName, string $path) use ($fileSystemRootPath) {
                return fopen($fileSystemRootPath . $path, 'rb');
            });

        return $flysystemServiceMock;
    }

    /**
     * @param string $fileName
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface|\Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface
     */
    protected function getCsvReader(string $fileName, string $fileSystemRootPath = '')
    {
        $configuration = $this->getCsvReaderConfigurationTransfer($fileName);

        $this->tester->setDependency(DataImportDependencyProvider::SERVICE_FLYSYSTEM, $this->getFlysystemMock($fileSystemRootPath));
        $dataImportBusinessFactory = $this->tester->getFactory()->setConfig($this->getConfigMock());

        return $dataImportBusinessFactory->createCsvReaderFromConfig($configuration);
    }

    protected function getCsvReaderConfigurationTransfer(
        string $fileName
    ): DataImporterReaderConfigurationTransfer {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration->setFileName($fileName);

        return $dataImporterReaderConfiguration;
    }
}
