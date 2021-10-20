<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataReader\CsvReader;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Countable;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataReaderException;
use Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfiguration;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use ValueError;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataReader
 * @group CsvReader
 * @group CsvReaderTest
 * Add your own group annotations below this line
 */
class CsvReaderTest extends Unit
{
    /**
     * @var int
     */
    public const EXPECTED_NUMBER_OF_DATA_SETS_IN_CSV = 3;

    /**
     * @var int
     */
    public const EXPECTED_NUMBER_OF_COLUMNS_IN_DATA_SET = 3;

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDataReaderCanBeUsedAsIteratorAndReturnsArrayObject(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        foreach ($csvReader as $dataSet) {
            $this->assertInstanceOf(DataSet::class, $dataSet);
        }
    }

    /**
     * @return void
     */
    public function testReaderIsCountable(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertInstanceOf(Countable::class, $csvReader);
    }

    /**
     * @return void
     */
    public function testDataReaderCountWithColumnHeader(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->tester->assertDataSetCount(static::EXPECTED_NUMBER_OF_DATA_SETS_IN_CSV, $csvReader);
    }

    /**
     * @return void
     */
    public function testDataReaderCountWithoutColumnHeader(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-without-header.csv', false);
        $this->tester->assertDataSetCount(static::EXPECTED_NUMBER_OF_DATA_SETS_IN_CSV, $csvReader);
    }

    /**
     * @return void
     */
    public function testDataReaderCanBeConfiguredToUseNewFileAfterInstantiation(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-semicolon-delimiter.csv');
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName(Configuration::dataDir() . 'import-standard.csv');

        $csvReader->configure($dataImportReaderConfigurationTransfer);
        $currentRow = $csvReader->current();

        $this->tester->assertDataSetWithKeys(1, $currentRow);
    }

    /**
     * @return void
     */
    public function testDataReaderCanBeConfiguredToUseNewFileAndCsvControlAfterInstantiation(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName(Configuration::dataDir() . 'import-semicolon-delimiter.csv')
            ->setCsvDelimiter(';')
            ->setCsvEnclosure(CsvReaderConfiguration::DEFAULT_ENCLOSURE)
            ->setCsvEscape(CsvReaderConfiguration::DEFAULT_ESCAPE)
            ->setCsvFlags(CsvReaderConfiguration::DEFAULT_FLAGS)
            ->setCsvHasHeader(CsvReaderConfiguration::DEFAULT_HAS_HEADER);

        $csvReader->configure($dataImportReaderConfigurationTransfer);
        $currentRow = $csvReader->current();

        $this->tester->assertDataSetWithKeys(1, $currentRow);
    }

    /**
     * @return void
     */
    public function testDataReaderReturnSubsetOfTheDataSetsStartingAtGivenPositionWhenOffsetIsSet(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv', true, 2);

        $csvReader->rewind();

        $this->tester->assertDataSetWithKeys(2, $csvReader->current());
        $csvReader->next();
        $this->assertTrue($csvReader->valid(), 'Expected that DataReaderInterface::valid() returns true because no limit was set and after received data set there is still one.');
    }

    /**
     * @return void
     */
    public function testDataReaderReturnSubsetOfTheDataSetsWhenOffsetAndLimitIsSet(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv', true, 2, 1);

        $csvReader->rewind();

        $this->tester->assertDataSetWithKeys(2, $csvReader->current());
        $csvReader->next();
        $this->assertFalse($csvReader->valid(), 'Expected that DataReaderInterface::valid() returns false because we limited the data set to one.');
    }

    /**
     * @return void
     */
    public function testDataReaderReturnSubsetOfTheDataSetsWhenLimitIsSet(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv', true, null, 1);

        $csvReader->rewind();

        $this->tester->assertDataSetWithKeys(1, $csvReader->current());
        $csvReader->next();
        $this->assertFalse($csvReader->valid(), 'Expected that DataReaderInterface::valid() returns false because we limited the data set to one.');
    }

    /**
     * @return void
     */
    public function testEachDataSetShouldHaveCsvColumnNamesAsKeys(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');

        $firstRow = $csvReader->current();
        $this->tester->assertDataSetWithKeys(1, $firstRow);
        $csvReader->next();

        $secondRow = $csvReader->current();
        $this->tester->assertDataSetWithKeys(2, $secondRow);
        $csvReader->next();

        $thirdRow = $csvReader->current();
        $this->tester->assertDataSetWithKeys(3, $thirdRow);
    }

    /**
     * @return void
     */
    public function testEachDataSetShouldNotHaveCsvColumnNamesAsKeys(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-without-header.csv', false);

        $firstRow = $csvReader->current();
        $this->tester->assertDataSetWithoutKeys(1, $firstRow);
        $csvReader->next();

        $secondRow = $csvReader->current();
        $this->tester->assertDataSetWithoutKeys(2, $secondRow);
        $csvReader->next();

        $thirdRow = $csvReader->current();
        $this->tester->assertDataSetWithoutKeys(3, $thirdRow);
    }

    /**
     * @return void
     */
    public function testKeyReturnsCurrentDataSetPosition(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertIsInt($csvReader->key());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenFileInvalid(): void
    {
        $this->expectException(DataReaderException::class);
        $configuration = $this->getCsvReaderConfigurationTransfer(Configuration::dataDir() . 'not-existing.csv');

        $this->tester->getFactory()->createCsvReaderFromConfig($configuration);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenHeaderAndDataSetLengthDoesNotMatch(): void
    {
        $exceptionClass = version_compare(PHP_VERSION, '8.0.0', '<')
            ? DataSetWithHeaderCombineFailedException::class
            : ValueError::class;
        $this->expectException($exceptionClass);
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-header-dataset-length-missmatch.csv');

        $csvReader->current();
    }

    /**
     * @param string $fileName
     * @param bool $hasHeader
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface|\Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface
     */
    protected function getCsvReader(string $fileName, bool $hasHeader = true, ?int $offset = null, ?int $limit = null)
    {
        $configuration = $this->getCsvReaderConfigurationTransfer($fileName, $hasHeader, $offset, $limit);
        $csvReader = $this->tester->getFactory()->createCsvReaderFromConfig($configuration);

        return $csvReader;
    }

    /**
     * @param string $fileName
     * @param bool $hasHeader
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer
     */
    protected function getCsvReaderConfigurationTransfer(
        string $fileName,
        bool $hasHeader = true,
        ?int $offset = null,
        ?int $limit = null
    ): DataImporterReaderConfigurationTransfer {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration
            ->setFileName($fileName)
            ->setCsvHasHeader($hasHeader)
            ->setOffset($offset)
            ->setLimit($limit);

        return $dataImporterReaderConfiguration;
    }
}
