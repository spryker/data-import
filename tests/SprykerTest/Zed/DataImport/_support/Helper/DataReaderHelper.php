<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Helper;

use Codeception\Module;
use Countable;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DataReaderHelper extends Module
{
    public function assertDataSetWithKeys(int $expectedRow, DataSetInterface $dataSet): void
    {
        $dataSetWithKeys = $this->getDataSetWithKeys($expectedRow);
        $this->assertEquals(new DataSet($dataSetWithKeys), $dataSet);
    }

    public function assertDataSetWithoutKeys(int $expectedRow, DataSetInterface $dataSet): void
    {
        $dataSetWithKeys = $this->getDataSetWithKeys($expectedRow);
        $this->assertEquals(new DataSet(array_values($dataSetWithKeys)), $dataSet);
    }

    public function assertDataSetCount(int $expectedNumberOfDataSets, Countable $reader): void
    {
        $givenCount = $reader->count();
        $this->assertSame($expectedNumberOfDataSets, $givenCount, sprintf(
            'Expected "%s" data sets found "%s".',
            $expectedNumberOfDataSets,
            $givenCount,
        ));
    }

    private function getDataSetWithKeys(int $expectedRow): array
    {
        return [
            'column1' => 'value-1-row-' . $expectedRow,
            'column2' => 'value-2-row-' . $expectedRow,
            'column3' => 'value-3-row-' . $expectedRow,
        ];
    }
}
